<?php

namespace App\Service;

use App\Utils\Slugger;
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

    public function __construct(string $bucket, string $accessId, string $accessSecret, string $region,
                                RequestStackContext $requestStackContext, LoggerInterface $logger)
    {
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
        $newFileName = Slugger::slug($originalFileName) . '.' . $uploadedFile->guessExtension();

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

    public function uploadNoteFile(UploadedFile $noteFile, string $path, ?string $existingFilename): string
    {
        $newFileName = $this->uploadFile($noteFile, $path);

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

    public function getPublicPath(string $path) : string
    {
        if(str_contains($path, '://')){
            return $path;
        }

        return $this->requestStackContext->getBasePath().$path;
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