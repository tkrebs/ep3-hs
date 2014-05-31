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

class ConfirmationListener extends AbstractListenerAggregate
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
        $t = $this->optionManager->getTranslator();

        $booking = $event->getTarget();
        $bill = $booking->getExtra('bill');
        $user = $booking->getExtra('user');

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

        $subject = sprintf($t->translate('Your booking for room %s'),
            $booking->needExtra('room')->getMeta('name'));

        $break = "\r\n";
        $doubleBreak = "\r\n\r\n";

        $confirmationText = $this->optionManager->get('subject.confirmation.text');

        if ($confirmationText) {
            $confirmationText = $doubleBreak . $confirmationText;
        }

        $text = sprintf("%s%s%s: %s - %s%s%s: %s%s%s: %s%s%s: %s%s%s: %s%s%s: %s%s",
            $t->translate('Thank you very much for your booking! We are looking forward to welcome you!'),
            $doubleBreak,
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
            $confirmationText);

        $this->mailService->send($user, $subject, $text);
    }

}