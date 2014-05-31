<?php

namespace Backend\View\Helper\Booking;

use Zend\View\Helper\AbstractHelper;

class BookingsFormat extends AbstractHelper
{

    public function __invoke(array $bookings)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th style="width: 16px;">%s</th>',
            $view->t('ID'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Room'));

        $html .= sprintf('<th>%s</th>',
            $view->t('User'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Arrival'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Departure'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Persons'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/booking/edit'), $view->t('New booking'));

        foreach ($bookings as $booking) {
            $html .= $view->backendBookingFormat($booking);
        }

        $html .= '</table>';

        if (! $bookings) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No bookings found'));
        }

        return $html;
    }

}