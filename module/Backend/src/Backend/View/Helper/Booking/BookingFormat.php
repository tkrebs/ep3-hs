<?php

namespace Backend\View\Helper\Booking;

use Booking\Entity\Booking;
use DateTime;
use IntlDateFormatter;
use Zend\View\Helper\AbstractHelper;

class BookingFormat extends AbstractHelper
{

    public function __invoke(Booking $booking)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $booking->need('bid'));

        if ($booking->getExtra('room')) {
            $roomLabel = $booking->needExtra('room')->getName($view);
        } else {
            $roomLabel = $view->t('All rooms');
        }

        $html .= sprintf('<td>%s</td>',
            $roomLabel);

        $html .= sprintf('<td><a href="%s" class="symbolic symbolic-user symbolic-link" target="_blank">%s</a></td>',
            $view->url('backend/user/edit', ['uid' => $booking->needExtra('user')->need('uid')]),
            $booking->needExtra('user')->need('alias'));

        $html .= sprintf('<td>%s</td>',
            $view->dateFormat(new DateTime($booking->need('date_arrival')), IntlDateFormatter::LONG));

        $html .= sprintf('<td>%s</td>',
            $view->dateFormat(new DateTime($booking->need('date_departure')), IntlDateFormatter::LONG));

        $html .= sprintf('<td>%s</td>',
            $booking->need('quantity'));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/booking/edit', ['bid' => $booking->need('bid')]), $view->t('Edit'),
            $view->url('backend/booking/delete', ['bid' => $booking->need('bid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}