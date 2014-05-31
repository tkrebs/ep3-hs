<?php

namespace Room\View\Helper;

use Room\Entity\Room;
use Zend\View\Helper\AbstractHelper;

class RoomPreview extends AbstractHelper
{

    public function __invoke(Room $room, $link = false)
    {
        $view = $this->getView();
        $html = '';

        $capacity = $room->need('capacity');

        if ($capacity == 1) {
            $capacityLabel = $view->t('Person');
        } else {
            $capacityLabel = $view->t('Persons');
        }

        $html .= sprintf('<div class="small-text gray">%s</div>',
            sprintf($view->t('Room for %s %s'), $capacity, $capacityLabel));

        $html .= sprintf('<div class="large-text">%s</div>',
            $room->getName($view));

        $info = $room->getMeta('info');

        if ($info) {
            $html .= sprintf('<div class="symbolic symbolic-info" style="margin: 4px 8px 0 0;">%s</div>',
                $info);
        }

        $description = $room->getMeta('description');

        if ($description && $link) {
            $html .= sprintf('<div class="symbolic symbolic-info" style="margin-top: 4px;"><a href="%s" target="_blank" class="gray">%s</a></div>',
                $view->url('room', ['rid' => $room->need('rid')]),
                $view->t('See description'));
        }

        return $html;
    }

}