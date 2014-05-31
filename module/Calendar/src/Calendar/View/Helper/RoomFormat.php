<?php

namespace Calendar\View\Helper;

use Room\Entity\Room;
use Zend\View\Helper\AbstractHelper;

class RoomFormat extends AbstractHelper
{

    public function __invoke(Room $room)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $rid = $room->need('rid');

        /* Room Picture */

        $html .= '<td>';
        $html .= '<div class="panel centered-text" style="margin-bottom: 0px; position: relative;">';

        $picture = $room->getPictureUrl();
        $thumbnail = $room->getThumbnailUrl();

        if ($picture) {
            $html .= sprintf('<a href="%s" target="_blank" rel="pretty-photo" style="opacity: 1.0;"><img src="%s" class="rounded" style="max-height: 64px;"></a>',
                $view->basePath($picture), $view->basePath($thumbnail));

            $pictures = $room->getPictureNumbers();

            if (count($pictures) > 1) {
                $html .= sprintf('<a href="%s" target="_blank" class="rounded overlay small-text unlined">+%s %s</a>',
                    $view->url('room', ['rid' => $rid]), count($pictures) - 1, $view->t('more'));
            }
        } else {
            $html .= sprintf('<a href="%s" target="_blank" class="symbolic symbolic-booking" style="background-position: center center;"></a>',
                $view->url('room', ['rid' => $rid]));
        }

        $html .= '</div>';
        $html .= '</td>';

        /* Room Preview */

        $html .= '<td>';
        $html .= '<div class="panel" style="margin-bottom: 0px;">';

        $html .= $view->roomPreview($room, true);

        $html .= '</div>';
        $html .= '</td>';

        /* Booking Button */

        $html .= '<td>';

        $html .= sprintf('<div class="panel" style="margin-bottom: 0px;"><a href="%s" class="default-button"><span class="symbolic symbolic-plus">%s</span></a></div>',
            $view->bookingUrl('booking/customize', ['room' => $rid]),
            $view->t('Book this room'));

        $html .= '</td>';

        $html .= '</tr>';

        return $html;
    }

}