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
use Symfony\Component\HttpFoundation\Response;

class UploaderHelper
{
    private RequestStackContext $requestStackContext;
    private LoggerInterface $logger;
    private Filesystem $fileSystem;
    private string $publicAssetsBaseUrl;

    public function __construct(string $bucket, string $accessId, string $accessSecret, string $region,
                                RequestStackContext $requestStackContext, LoggerInterface $logger,
                                string $uploadedAssetsBaseUrl)
    {
        $this->publicAssetsBaseUrl = $uploadedAssetsBaseUrl;
        $this->requestStackContext = $requestStackContext;
        $this->logger = $logger;

        /**
         * @var $client S3ClientInterface
         */
        $client = new S3Client([
            'credentials' => [
                'key' => $accessId,
                'secret' => $accessSecret,
            ],
            'region' => $region,
            'version' => '2006-03-01'
        ]);
        $adapter = new AwsS3V3Adapter(
            $client,
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

    public function deleteFile(string $path): Response
    {
        try {
            $this->fileSystem->delete($path);
        } catch (FilesystemException $e) {
            $this->logger->error($e);
        }
        return new Response(null, 204);
    }
}