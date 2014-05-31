<?php

namespace Room\Entity;

use Base\Entity\AbstractEntity;
use RuntimeException;
use Zend\View\Renderer\PhpRenderer;

class Room extends AbstractEntity
{

    protected $rid;
    protected $rid_prototype;
    protected $rnr;
    protected $status;
    protected $capacity;

    protected $primary = 'rid';

    /**
     * The possible status options.
     *
     * @var array
     */
    public static $statusOptions = array(
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
    );

    /**
     * Returns the status string.
     *
     * @return string
     */
    public function getStatus()
    {
        $status = $this->need('status');

        if (isset(self::$statusOptions[$status])) {
            return self::$statusOptions[$status];
        } else {
            return 'Unknown';
        }
    }

    public function getName(PhpRenderer $view)
    {
        return $this->getMeta('name', sprintf('%s %s',
            $view->t('Room'), $this->need('rnr')));
    }

    public function getPictures()
    {
        return $this->getMeta('pictures');
    }

    public function setPictures($pictures)
    {
        if (is_array($pictures)) {
            $pictures = implode(', ', $pictures);
        }

        $this->setMeta('pictures', $pictures);
    }

    public function getPictureNumbers()
    {
        $pictures = $this->getPictures();

        if ($pictures) {
            $pictures = explode(',', $pictures);

            for ($i = 0; $i < count($pictures); $i++) {
                $pictures[$i] = trim($pictures[$i]);
            }

            return $pictures;
        }

        return array();
    }

    public function getPictureNumber()
    {
        $pictures = $this->getPictureNumbers();

        if (isset($pictures[0])) {
            return $pictures[0];
        } else {
            return null;
        }
    }

    public function hasPictureNumber($number)
    {
        return in_array($number, $this->getPictureNumbers());
    }

    public function addPictureNumber($number = null)
    {
        if (! $number) {
            $number = $this->getNextPictureNumber();
        }

        if ($this->hasPictureNumber($number)) {
            throw new RuntimeException('Picture number does already exist');
        }

        $pictures = $this->getPictureNumbers();

        $pictures[] = $number;

        $this->setPictures($pictures);

        return $number;
    }

    public function removePictureNumber($number)
    {
        $pictures = $this->getPictureNumbers();

        for ($i = 0; $i < count($pictures); $i++) {
            if ($pictures[$i] == $number) {
                unset ($pictures[$i]);
            }
        }

        $this->setPictures($pictures);
    }

    public function getNextPictureNumber()
    {
        $nextPictureNumber = 0;

        foreach ($this->getPictureNumbers() as $pictureNumber) {
            if (is_numeric($pictureNumber)) {
                if ((int) $pictureNumber > $nextPictureNumber) {
                    $nextPictureNumber = $pictureNumber;
                }
            }
        }

        return ($nextPictureNumber + 1);
    }

    public function getPictureUrl($number = null, $extension = '.jpg')
    {
        if (! $number) {
            $number = $this->getPictureNumber();
        }

        if ($this->hasPictureNumber($number)) {
            return sprintf('/imgs-client/upload/room/%s/%s%s',
                $this->need('rid'), $number, $extension);
        } else {
            return null;
        }
    }

    public function getThumbnailUrl($number = null)
    {
        return $this->getPictureUrl($number, '.tn.jpg');
    }

}