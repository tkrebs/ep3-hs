<?php

namespace Booking\View\Helper;

use DateTime;
use IntlDateFormatter;
use Zend\View\Helper\AbstractHelper;

class BookingDatePreview extends AbstractHelper
{

    public function __invoke(DateTime $dateArrival, DateTime $dateDeparture, $capacity = null)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="compact-table">';

        $html .= sprintf('<tr><td class="gray" style="width: 120px;">%s:</td><td>%s</td></tr>',
            $view->t('Arrival'),
            $view->dateFormat($dateArrival, IntlDateFormatter::FULL));

        $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
            $view->t('Departure'),
            $view->dateFormat($dateDeparture, IntlDateFormatter::FULL));

        $bookingPeriod = $dateArrival->diff($dateDeparture);

        $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
            $view->t('Night stays'),
            $bookingPeriod->format('%a'));

        /* Display capacity field */

        if ($capacity) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Persons'), $capacity);
        }

        $html .= '</table>';

        return $html;
    }

}