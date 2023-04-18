<?php

namespace App\Service;

use Container9MMQajI\get_Console_Command_ValidatorDebug_LazyService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UploadedHelper
{
    public function __construct(
        #[Autowire('%app.file_dir%')]
        private string $projectDir,
    )
    {
    }

    public function uploadMovieImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->projectDir;
        $newFileName =uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $uploadedFile->move(
                $destination,
                $newFileName
            );
        }
        catch (FileException $e) {
            return $e->getMessage();
        }


        return $newFileName;
    }
}