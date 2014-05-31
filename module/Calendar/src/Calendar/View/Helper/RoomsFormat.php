<?php

namespace Calendar\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RoomsFormat extends AbstractHelper
{

    public function __invoke(array $rooms)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="default-table middle-table">';

        foreach ($rooms as $room) {
            $html .= $view->calendarRoomFormat($room);
        }

        $html .= '</table>';

        return $html;
    }

}