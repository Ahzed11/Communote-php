<?php

namespace App\Service;

use App\Entity\Note;
use App\Utils\StringUtils;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\Visibility;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class S3Helper
{
    private RequestStackContext $requestStackContext;
    private LoggerInterface $logger;
    private Filesystem $fileSystem;
    private S3ClientInterface $client;
    private string $publicAssetsBaseUrl;
    private string $bucket;

    public function __construct(string $bucket, string $accessId, string $accessSecret, string $region,
                                RequestStackContext $requestStackContext, LoggerInterface $logger,
                                string $uploadedAssetsBaseUrl)
    {
        $this->publicAssetsBaseUrl = $uploadedAssetsBaseUrl;
        $this->requestStackContext = $requestStackContext;
        $this->logger = $logger;
        $this->bucket = $bucket;

        $this->client = new S3Client([
            'credentials' => [
                'key' => $accessId,
                'secret' => $accessSecret,
            ],
            'region' => $region,
            'version' => '2006-03-01'
        ]);
        $adapter = new AwsS3V3Adapter(
            $this->client,
            $bucket,
            '',
            new PortableVisibilityConverter(
                Visibility::PUBLIC
            )
        );
        $this->fileSystem = new Filesystem($adapter);
    }

    public function uploadFile(UploadedFile $uploadedFile, string $path): string
    {
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = StringUtils::slugify($originalFileName) . '.' . $uploadedFile->guessExtension();

        $stream = fopen($uploadedFile->getPathname(), 'r');

        try {
            $this->fileSystem->writeStream(
                $path . '/' . $newFileName,
                $stream
            );
        } catch (FilesystemException $e) {
            $this->logger->error($e);
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFileName;
    }

    public function uploadNoteFile(UploadedFile $uploadedFile, string $path, ?string $existingFilename): string
    {
        $newFileName = $this->uploadFile($uploadedFile, $path);

        if($existingFilename)
        {
            try {
                $this->fileSystem->delete($path . '/' . $existingFilename);
            } catch (FilesystemException $e) {
                $this->logger->error($e);
            }
        }

        return $newFileName;
    }

    public function getNoteFilePublicPath(Note $note) : string
    {
        $fullPath = $this->publicAssetsBaseUrl . '/' . $note->getPath() .'/'. $note->getNoteFile()->getFileName();
        if(str_contains($fullPath, '://')){
            return $fullPath;
        }

        return $this->requestStackContext->getBasePath() . $fullPath;
    }

    public function readStream(string $path)
    {
        try {
            return $this->fileSystem->readStream($path);
        } catch (FilesystemException $e) {
            $this->logger->error($e);
        }
        return new Stream(null);
    }

    public function deleteNoteFile(Note $note): Response
    {
        try {
            $this->fileSystem->delete($note->getPath() .'/'. $note->getNoteFile()->getFileName());
        } catch (FilesystemException $e) {
            $this->logger->error($e);
        }
        return new Response(null, 204);
    }

    public function getDownloadRedirectResponse(Note $note) : RedirectResponse
    {
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $note->getNoteFile()->getOriginalFilename(),
            'download'
        );

        $command = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $note->getPath() . '/' . $note->getNoteFile()->getFilename(),
            'ResponceContentType' => $note->getNoteFile()->getMimeType(),
            'ResponseContentDisposition' => $disposition
        ]);

        $request = $this->client->createPresignedRequest($command, '+5 minutes');
        return new RedirectResponse((string) $request->getUri());
    }
}