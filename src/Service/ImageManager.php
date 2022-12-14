<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageManager
{
    private $imageDirectory;
    private $imagine;

    private const MAX_WIDTH = 1024;
    private const MAX_HEIGHT = 768;

    public function __construct(
        $imageDirectory
    ) {
        $this->imageDirectory = $imageDirectory;
        $this->imagine = new Imagine();
    }

    public function upload(UploadedFile $file)
    {

        // Répertoire de destination des images
        $imageDirectory = null;

        $fileName = 'assets/images/' . uniqid() . '.' . $file->getClientOriginalExtension();
        $imageDirectory = $this->getImageDirectory();

        try {
            $file->move($imageDirectory, $fileName);
        } catch (FileException $e) {
        }

        return $fileName;
    }

    public function getImageDirectory()
    {
        return $this->imageDirectory;
    }

    public function resize(string $filename, $width): void
    {
        list($iwidth, $iheight) = getimagesize($filename);

        $ratio = $iwidth / $iheight;
        // if ($width / $height > $ratio) {
        //     $width = $height * $ratio;
        // } else {
        //     $height = $width / $ratio;
        // }

        $height = $width / $ratio;

        $photo = $this->imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($filename);
    }

    public function deleteImage(string $fileName): void
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($fileName);
    }
}
