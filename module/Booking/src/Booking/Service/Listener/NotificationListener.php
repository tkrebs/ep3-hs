<?php

namespace Booking\Service\Listener;

use Base\Manager\OptionManager;
use DateTime;
use IntlDateFormatter;
use User\Service\MailService;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\I18n\View\Helper\DateFormat;

class NotificationListener extends AbstractListenerAggregate
{

    protected $optionManager;
    protected $dateFormat;
    protected $mailService;

    public function __construct(OptionManager $optionManager, DateFormat $dateFormat, MailService $mailService)
    {
        $this->optionManager = $optionManager;
        $this->dateFormat = $dateFormat;
        $this->mailService = $mailService;
    }

    public function attach(EventManagerInterface $events)
    {
        $events->attach('book', array($this, 'onBook'));
    }

    public function onBook(Event $event)
    {
        if ($this->optionManager->get('service.notify.booking', 'false') == 'true') {
            $t = $this->optionManager->getTranslator();

            $booking = $event->getTarget();
            $bill = $booking->getExtra('bill');

            $paymentMethod = 'Unknown';
            $paymentStatus = 'Unknown';

            if ($bill) {
                switch ($bill->getMeta('payment.method')) {
                    case 'paypal':
                        $paymentMethod = $t->translate('PayPal');
                        break;
                    case 'invoice':
                        $paymentMethod = $t->translate('Invoice');
                        break;
                }

                $paymentStatus = $t->translate($bill->getStatus());
            }

            $subject = $t->translate('New booking');

            $break = "\r\n";
            $doubleBreak = "\r\n\r\n";

            $text = sprintf("%s%s%s: %s%s%s: %s - %s%s%s: %s%s%s: %s%s%s: %s%s%s: %s%s%s: %s%s%s",
                $subject,
                $doubleBreak,
                $t->translate('User'),
                $booking->needExtra('user')->need('alias'),
                $break,
                $t->translate('Room'),
                $booking->needExtra('room')->get('rnr'),
                $booking->needExtra('room')->getMeta('name'),
                $doubleBreak,
                $t->translate('Arrival'),
                $this->dateFormat->__invoke(new DateTime($booking->need('date_arrival')), IntlDateFormatter::LONG),
                $break,
                $t->translate('Departure'),
                $this->dateFormat->__invoke(new DateTime($booking->need('date_departure')), IntlDateFormatter::LONG),
                $break,
                $t->translate('Persons'),
                $booking->need('quantity'),
                $doubleBreak,
                $t->translate('Payment method'),
                $paymentMethod,
                $break,
                $t->translate('Payment status'),
                $paymentStatus,
                $doubleBreak,
                $t->translate('Login to your booking system for all details.'));

            $this->mailService->notify($subject, $text);
        }
    }

}