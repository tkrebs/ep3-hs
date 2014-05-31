<?php

namespace Backend\View\Helper\Room;

use Zend\View\Helper\AbstractHelper;

class RoomsFormat extends AbstractHelper
{

    public function __invoke(array $rooms)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th style="width: 16px;">%s</th>',
            $view->t('ID'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Number'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Name'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Persons'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Status'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/room/edit'), $view->t('New room'));

        foreach ($rooms as $room) {
            $html .= $view->backendRoomFormat($room);
        }

        $html .= '</table>';

        if (! $rooms) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No rooms found'));
        }

        return $html;
    }

}