<?php

namespace Base\Service;

use Imagick;

class PictureService extends AbstractService
{

    public function uploadPicture($source, $destination)
    {
        $destinationPath = $this->generateDestinationPath($destination);

        $destinationDir = dirname($destinationPath);

        $thumbnailPath = preg_replace('/\.jpg$/', '.tn.jpg', $destinationPath);

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        move_uploaded_file($source, $destinationPath);

        $this->generatePicture($destinationPath, $destinationPath);
        $this->generateThumbnail($destinationPath, $thumbnailPath);
    }

    public function removePicture($destination)
    {
        $destinationPath = $this->generateDestinationPath($destination);

        $thumbnailPath = preg_replace('/\.jpg$/', '.tn.jpg', $destinationPath);

        if (is_file($destinationPath)) {
            unlink($destinationPath);
        }

        if (is_file($thumbnailPath)) {
            unlink($thumbnailPath);
        }
    }

    public function generatePicture($sourcePath, $destinationPath)
    {
        $image = new Imagick($sourcePath);

        $dimensions = $image->getImageGeometry();
        $width = $dimensions['width'];
        $height = $dimensions['height'];

        if ($width >= $height) {
            if ($width > 768) {
                $image->adaptiveresizeimage(768, 0);
            }
        } else {
            if ($height > 768) {
                $image->adaptiveresizeimage(0, 768);
            }
        }

        $image->writeimage($destinationPath);
        $image->destroy();
    }

    public function generateThumbnail($sourcePath, $destinationPath)
    {
        $thumbnail = new Imagick($sourcePath);

        $thumbnail->cropthumbnailimage(160, 90);

        $thumbnail->writeimage($destinationPath);
        $thumbnail->destroy();
    }

    protected function generateDestinationPath($destination)
    {
        return sprintf('%s/public/imgs-client/upload/%s',
            getcwd(), ltrim($destination, '/'));
    }

}