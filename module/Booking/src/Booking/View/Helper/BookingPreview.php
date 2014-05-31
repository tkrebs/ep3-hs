<?php

namespace Booking\View\Helper;

use Booking\Entity\Booking;
use DateTime;
use IntlDateFormatter;
use Room\Manager\RoomManager;
use User\Manager\UserManager;
use Zend\View\Helper\AbstractHelper;

class BookingPreview extends AbstractHelper
{

    protected $roomManager;
    protected $userManager;

    public function __construct(RoomManager $roomManager, UserManager $userManager)
    {
        $this->roomManager = $roomManager;
        $this->userManager = $userManager;
    }

    public function __invoke(Booking $booking)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="compact-table">';

        /* Display room */

        $rid = $booking->get('rid');

        if ($rid) {
            $roomLabel = $this->roomManager->get($rid)->getName($view);
        } else {
            $roomLabel = $view->t('All rooms');
        }

        $html .= sprintf('<tr><td class="gray" style="width: 120px;">%s:</td><td>%s</td></tr>',
            $view->t('Room'),
            $roomLabel);

        /* Display user */

        $uid = $booking->get('uid');

        if ($uid) {
            $userLabel = $this->userManager->get($uid)->need('alias');

            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('User'),
                $userLabel);
        }

        /* Display arrival date */

        if ($booking->get('date_arrival')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Arrival'),
                $view->dateFormat(new DateTime($booking->get('date_arrival')), IntlDateFormatter::FULL));
        }

        /* Display departure date */

        if ($booking->get('date_departure')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Departure'),
                $view->dateFormat(new DateTime($booking->get('date_departure')), IntlDateFormatter::FULL));
        }

        /* Display quantity */

        $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
            $view->t('Persons'),
            $booking->need('quantity'));

        /* Display notes */

        if ($booking->getMeta('notes')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Notes'),
                $view->escapeHtml($booking->getMeta('notes')));
        }

        $html .= '</table>';

        return $html;
    }

}