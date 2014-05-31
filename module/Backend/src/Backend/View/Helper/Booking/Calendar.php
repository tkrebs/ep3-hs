<?php

namespace Backend\View\Helper\Booking;

use DateTime;
use IntlDateFormatter;
use Room\Entity\Room;
use Zend\View\Helper\AbstractHelper;

class Calendar extends AbstractHelper
{

    protected $lastBid;
    protected $lastColor;

    protected $colorCollisions = array();

    public function __invoke(DateTime $dateStart, DateTime $dateEnd, array $rooms, array $bookings = array(), $controls = true)
    {
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= $this->renderHeadRow($dateStart, $dateEnd, $controls);

        foreach ($rooms as $room) {
            $html .= $this->renderRoomRow($dateStart, $dateEnd, $room, $bookings);
        }

        $html .= '</table>';

        return $html;
    }

    protected function renderHeadRow(DateTime $dateStart, DateTime $dateEnd, $controls)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= '<td class="centered-text" style="width: 175px;">';
        $html .= $this->renderDatepicker($dateStart, $controls);
        $html .= '</td>';

        $dateWalker = clone $dateStart;
        $dateWalker->setTime(0, 0, 0);

        while ($dateWalker < $dateEnd) {
            $html .= sprintf('<td colspan="2" class="centered-text" style="%s">',
                $this->determineStyle($dateWalker));

            $fullDate = $view->dateFormat($dateWalker, IntlDateFormatter::FULL);
            $fullDateParts = explode(', ', $fullDate);

            $dayName = $fullDateParts[0];
            $dayNameParts = substr($dayName, 0, 2);

            $shortDate = $view->dateFormat($dateWalker, IntlDateFormatter::SHORT);
            $shortDateParts = trim(substr($shortDate, 0, -2), '/');

            $html .= sprintf('<div>%s.</div><div>%s</div>',
                $dayNameParts, $shortDateParts);

            $html .= '</td>';

            $dateWalker->modify('+1 days');
        }

        $html .= '</tr>';

        return $html;
    }

    protected function renderDatepicker(DateTime $dateStart, $controls)
    {
        $view = $this->getView();
        $html = '';

        $html .= sprintf('<form method="get" action="%s">',
            $view->url('backend/dashboard'));

        $html .= $this->renderDatepickerPrev($dateStart, $controls);

        $html .= '<div class="inline-element">';

        $html .= sprintf('<label class="inline-label symbolic symbolic-date" for="bc-date-start"><span style="display: none;">%s</span></label>',
            $view->t('Calendar date'));

        if ($controls) {
            $dateInputClass = 'datepicker datepicker-autosubmit';
            $dateInputAttr = null;
        } else {
            $dateInputClass = null;
            $dateInputAttr = 'readonly="readonly"';
        }

        $html .= sprintf('<input type="text" value="%s" id="bc-date-start" name="date-start" class="inline-label-container %s" %s style="padding-left: 28px; width: 100px;">',
            $view->dateFormat($dateStart, IntlDateFormatter::MEDIUM), $dateInputClass, $dateInputAttr);

        $html .= '</div>';

        $html .= $this->renderDatepickerNext($dateStart, $controls);

        $html .= '</form>';

        return $html;
    }

    protected function renderDatepickerPrev(DateTime $dateStart, $controls)
    {
        $view = $this->getView();

        $datePrev = clone $dateStart;
        $datePrev->modify('-1 days');

        if ($controls) {
            $style = null;
        } else {
            $style = 'visibility: hidden;';
        }

        return sprintf('<a href="%s" class="unlined gray" style="margin-right: 8px; %s" data-tooltip="%s">&laquo;</a>',
            $view->url('backend/dashboard', [], ['query' => ['date-start' => $datePrev->format('Y-m-d')]]),
            $style,
            $view->t('Previous day'));
    }

    protected function renderDatepickerNext(DateTime $dateStart, $controls)
    {
        $view = $this->getView();

        $dateNext = clone $dateStart;
        $dateNext->modify('+1 days');

        if ($controls) {
            $style = null;
        } else {
            $style = 'visibility: hidden;';
        }

        return sprintf('<a href="%s" class="unlined gray" style="margin-left: 8px; %s" data-tooltip="%s">&raquo;</a>',
            $view->url('backend/dashboard', [], ['query' => ['date-start' => $dateNext->format('Y-m-d')]]),
            $style,
            $this->getView()->t('Next day'));
    }

    protected function renderRoomRow(DateTime $dateStart, DateTime $dateEnd, Room $room, array $bookings = array())
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $room->getName($view));

        $dateWalker = clone $dateStart;
        $dateWalker->setTime(0, 0, 0);

        while ($dateWalker < $dateEnd) {
            $html .= $this->renderCell($dateWalker, $room, $bookings);

            $dateWalker->modify('+1 days');
        }

        $html .= '</tr>';

        return $html;
    }

    protected function renderCell(DateTime $dateWalker, Room $room, array $bookings = array())
    {
        $html = '';

        $style = $this->determineStyle($dateWalker);

        $html .= sprintf('<td class="centered-text" style="%s">',
            $style);

        $bookings = $this->determineBookings($dateWalker, $room, $bookings);

        if ($bookings['forenoon']) {
            $html .= $this->renderBookings($bookings['forenoon']);
        } else {
            $html .= $this->renderFree($dateWalker, $room);
        }

        $html .= '</td>';

        $html .= sprintf('<td class="centered-text" style="%s">',
            $style);

        if ($bookings['afternoon']) {
            $html .= $this->renderBookings($bookings['afternoon']);
        } else {
            $html .= $this->renderFree($dateWalker, $room);
        }

        $html .= '</td>';

        return $html;
    }

    protected function renderFree(DateTime $dateWalker, Room $room)
    {
        $view = $this->getView();

        return sprintf('<a href="%s" class="unlined" style="display: inline-block; width: 16px;" data-tooltip="%s">&middot;</a>',
            $view->url('backend/booking/edit', [], ['query' => [
                'rid' => $room->need('rid'),
                'date-arrival' => $view->dateFormat($dateWalker, IntlDateFormatter::MEDIUM),
                'date-departure' => $view->dateFormat($dateWalker, IntlDateFormatter::MEDIUM)]]),
            $view->t('New booking'));
    }

    protected function renderBookings(array $bookings)
    {
        $view = $this->getView();
        $html = '';

        foreach ($bookings as $booking) {
            $bid = $booking->need('bid');

            $backgroundColors = array('B58900', 'CB4B16', 'DC322F', 'D33682', '6C71C4', '268BD2', '2AA198', '859900');
            $background = $backgroundColors[$bid % count($backgroundColors)];

            // Check if the current booking has the same color than the previous one
            if ($bid != $this->lastBid) {
                if ($background == $this->lastColor) {

                    // Then add the current booking to the list of collisions
                    if (! in_array($bid, $this->colorCollisions)) {
                        $this->colorCollisions[] = $bid;
                    }
                }
            }

            // If current booking is in the list of collisions, increase color index
            if (in_array($bid, $this->colorCollisions)) {
                $background = $backgroundColors[($bid + 1) % count($backgroundColors)];
            }

            // Update last bid and color so that the color collision detection above works
            $this->lastBid = $bid;
            $this->lastColor = $background;

            if ($booking->need('status') == 'disabled') {
                $backgroundOpacity = 'opacity: 0.25;';
            } else {
                $backgroundOpacity = null;
            }

            if ($booking->getExtra('user')) {
                $tooltip = sprintf('<span class=gray>%s:</span> %s (%s)',
                    $view->t('Booked by'),
                    $booking->getExtra('user')->need('alias'),
                    $booking->getExtra('user')->need('uid'));
            } else {
                $tooltip = null;
            }

            $html .= sprintf('<a href="%s" class="unlined" style="display: inline-block; width: 16px; %s background-color: #%s;" data-tooltip="%s">&nbsp;</a>',
                $view->url('backend/booking/edit', ['bid' => $bid]),
                $backgroundOpacity,
                $background,
                $tooltip);
        }

        return $html;
    }

    protected function determineBookings(DateTime $dateWalker, Room $room, array $bookings = array())
    {
        $bookingMatches = array(
            'forenoon' => array(),
            'afternoon' => array(),
        );

        foreach ($bookings as $booking) {
            if (! $booking->get('rid') || $booking->get('rid') == $room->need('rid')) {
                $bookingDateArrival = new DateTime($booking->need('date_arrival'));
                $bookingDateDeparture = new DateTime($booking->need('date_departure'));

                if ($dateWalker == $bookingDateArrival) {
                    $bookingMatches['afternoon'][] = $booking;
                } else if ($dateWalker == $bookingDateDeparture) {
                    $bookingMatches['forenoon'][] = $booking;
                } else {
                    if ($dateWalker > $bookingDateArrival && $dateWalker < $bookingDateDeparture) {
                        $bookingMatches['forenoon'][] = $booking;
                        $bookingMatches['afternoon'][] = $booking;
                    }
                }
            }
        }

        return $bookingMatches;
    }

    protected function determineStyle(DateTime $dateWalker)
    {
        switch ($dateWalker->format('N')) {
            case '6':
                return 'background-color: #EEE;';
            case '7':
                return 'background-color: #DDD;';
            default:
                return null;
        }
    }

}