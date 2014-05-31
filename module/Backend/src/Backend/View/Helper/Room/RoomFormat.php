<?php

namespace Backend\View\Helper\Room;

use Room\Entity\Room;
use Zend\View\Helper\AbstractHelper;

class RoomFormat extends AbstractHelper
{

    public function __invoke(Room $room)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $room->need('rid'));

        $html .= sprintf('<td>%s</td>',
            $room->need('rnr'));

        $html .= sprintf('<td>%s</td>',
            $room->getMeta('name', '-'));

        $html .= sprintf('<td>%s</td>',
            $room->need('capacity'));

        $html .= sprintf('<td>%s</td>',
            $view->t($room->getStatus()));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/room/edit', ['rid' => $room->need('rid')]), $view->t('Edit'),
            $view->url('backend/room/delete', ['rid' => $room->need('rid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}