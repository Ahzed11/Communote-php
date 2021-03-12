<?php


namespace App\Twig;


use App\Entity\Note;
use App\Service\S3Helper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private S3Helper $uploaderHelper;

    public function __construct(S3Helper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath'])
        ];
    }

    public function getUploadedAssetPath(Note $note): string
    {
        return $this->uploaderHelper->getNoteFilePublicPath($note);
    }
}