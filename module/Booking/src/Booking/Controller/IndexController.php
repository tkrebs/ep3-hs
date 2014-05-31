<?php

namespace Booking\Controller;

use RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    public function customizeAction()
    {
        extract( $this->determineBookingParams() );

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');

        $bundles = $bundleManager->getByBooking($dateArrival, $dateDeparture, $room);
        $bundleMessage = null;

        if ($this->getRequest()->isPost()) {
            $bundleParam = $this->params()->fromPost('bf-bundle');

            if ($bundleParam == 'coupon') {
                $couponCode = $this->params()->fromPost('bf-coupon-code');

                $couponBundles = $bundleManager->getByBooking($dateArrival, $dateDeparture, $room, $couponCode);

                if ($couponBundles) {
                    $bundles = $couponBundles;
                } else {
                    $bundleMessage = 'This coupon code is invalid';
                }
            } else {
                $bundle = $bundleManager->get($bundleParam);

                $bundleItemsCode = $this->determineBookingBundleItemsCode($bundle);

                return $this->redirectToBookingRoute('booking/register', $dateArrival, $dateDeparture, $capacity, $room, $bundle, $bundleItemsCode);
            }
        }

        return array(
            'dateArrival' => $dateArrival,
            'dateDeparture' => $dateDeparture,
            'capacity' => $capacity,
            'room' => $room,
            'bundles' => $bundles,
            'bundleSelected' => $bundle,
            'bundleMessage' => $bundleMessage,
        );
    }

    public function registerAction()
    {
        extract( $this->determineBookingParams(true) );

        $serviceManager = $this->getServiceLocator();
        $userSessionManager = $serviceManager->get('User\Manager\UserSessionManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $registrationForm = $formElementManager->get('Booking\Form\RegistrationForm');
        $registrationMessage = null;

        if ($this->getRequest()->isPost()) {
            $registrationForm->setData($this->params()->fromPost());

            if ($registrationForm->isValid()) {
                $registrationData = $registrationForm->getData();

                $bookingSession = $userSessionManager->getSessionContainer('BookingSession');
                $bookingSession->registrationData = $registrationData;

                /* Determine payment method */

                try {
                    switch ($registrationData['rf-payment']) {
                        case 'paypal':
                            $bookingSession->payment = 'paypal';

                            return $this->redirectToPayPal($dateArrival, $dateDeparture, $capacity, $room, $bundle, $bundleItemsCode, $bookingSession);
                        case 'invoice':
                            $bookingSession->payment = 'invoice';

                            return $this->redirectToBookingRoute('booking/confirm', $dateArrival, $dateDeparture, $capacity, $room, $bundle, $bundleItemsCode);
                    }
                } catch (RuntimeException $e) {
                    $registrationMessage = $e->getMessage();
                }
            }
        } else {
            $bookingSession = $userSessionManager->getSessionContainer('BookingSession');

            if ($bookingSession->registrationData) {
                $registrationForm->setData($bookingSession->registrationData);
            }
        }

        return array(
            'dateArrival' => $dateArrival,
            'dateDeparture' => $dateDeparture,
            'capacity' => $capacity,
            'room' => $room,
            'bundle' => $bundle,
            'bundleItemsCode' => $bundleItemsCode,
            'registrationForm' => $registrationForm,
            'registrationMessage' => $registrationMessage,
        );
    }

    public function confirmAction()
    {
        extract( $this->determineBookingParams(true) );

        $serviceManager = $this->getServiceLocator();
        $bookingService = $serviceManager->get('Booking\Service\BookingService');

        $bookingSession = $this->determineBookingSession();

        /* Receive payment information */

        if ($bookingSession->payment == 'paypal') {
            $payerId = $this->params()->fromQuery('PayerID');

            if ($payerId && ctype_alnum($payerId)) {
                $bookingSession->paymentPayerId = $payerId;
            }
        }

        $user = $bookingService->createUser($bookingSession->registrationData);
        $user->setExtra('notes', $bookingSession->registrationData['rf-notes']);

        $msg = null;

        if ($this->getRequest()->isPost()) {
            if ($this->option('subject.rules.document.file')) {
                if (! $this->params()->fromPost('bf-accept-rules-document')) {
                    $msg = $this->t('Please read and accept our rules and conditions');
                }
            }

            if ($this->option('subject.rules.text')) {
                if (! $this->params()->fromPost('bf-accept-rules-text')) {
                    $msg = $this->t('Please read and accept our rules and conditions');
                }
            }

            if (! $msg) {
                return $this->redirectToBookingRoute('booking/complete', $dateArrival, $dateDeparture, $capacity, $room, $bundle, $bundleItemsCode);
            }
        }

        return array(
            'dateArrival' => $dateArrival,
            'dateDeparture' => $dateDeparture,
            'capacity' => $capacity,
            'room' => $room,
            'bundle' => $bundle,
            'bundleItems' => $bundleItems,
            'bundleItemsCode' => $bundleItemsCode,
            'user' => $user,
            'msg' => $msg,
        );
    }

    public function completeAction()
    {
        extract( $this->determineBookingParams(true) );

        $serviceManager = $this->getServiceLocator();
        $bookingService = $serviceManager->get('Booking\Service\BookingService');
        $userSessionManager = $serviceManager->get('User\Manager\UserSessionManager');

        $bookingSession = $this->determineBookingSession();

        /* Execute payment */

        if ($bookingSession->payment == 'paypal') {
            $serviceManager->get('Booking\Service\PayPalService')->executePayment(
                $bookingSession->paymentAccess, $bookingSession->paymentPaymentId, $bookingSession->paymentPayerId);

            $billStatus = 'paid';
        } else {
            $billStatus = 'pending';
        }

        $user = $bookingService->createUser($bookingSession->registrationData);

        $booking = $bookingService->createBooking($dateArrival, $dateDeparture, $capacity, $room, $user, $bundle, $bundleItems, array(
            'notes' => $bookingSession->registrationData['rf-notes'],
        ), $billStatus, array(
            'payment.method' => $bookingSession->payment,
        ));

        if (! $userSessionManager->getSessionUser()) {
            $userSessionManager->getSessionManager()->destroy();
        }

        return array(
            'dateArrival' => $dateArrival,
            'dateDeparture' => $dateDeparture,
            'capacity' => $capacity,
            'room' => $room,
            'bundle' => $bundle,
            'bundleItems' => $bundleItems,
            'booking' => $booking,
            'user' => $booking->getExtra('user'),
        );
    }

}