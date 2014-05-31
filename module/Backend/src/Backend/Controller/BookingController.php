<?php

namespace Backend\Controller;

use Booking\Entity\Booking;
use DateTime;
use RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class BookingController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $bookings = $bookingManager->getAll('bid DESC');

        $roomManager->getByBookings($bookings);
        $userManager->getByBookings($bookings);

        return array(
            'bookings' => $bookings,
        );
    }

    public function editAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        if ($bid) {
            $booking = $bookingManager->get($bid);

            $userId = $booking->get('uid');

            if ($userId) {
                $booking->setExtra('user', $userManager->get($userId));
            }
        } else {
            $booking = null;
        }

        $editBookingForm = $formElementManager->get('Backend\Form\Booking\EditBookingForm');

        if ($this->getRequest()->isPost()) {
            $editBookingForm->setData($this->params()->fromPost());

            if ($editBookingForm->isValid()) {
                $ebd = $editBookingForm->getData();

                if (! $booking) {
                    $booking = new Booking();
                }

                /* Determine room */

                if ($ebd['ebf-rid']) {
                    $rid = $ebd['ebf-rid'];
                } else {
                    $rid = null;
                }

                /* Determine user */

                preg_match('/\(([0-9]+)\)$/', $ebd['ebf-user'], $matches);

                if (! (isset($matches[1]) && is_numeric($matches[1]))) {
                    throw new RuntimeException('Invalid user passed');
                }

                $uid = $matches[1];

                $userManager->get($uid);

                /* Set properties */

                $booking->set('rid', $rid);
                $booking->set('uid', $uid);
                $booking->set('status', $ebd['ebf-status']);
                $booking->set('date_arrival', (new DateTime($ebd['ebf-date-arrival']))->format('Y-m-d'));
                $booking->set('date_departure', (new DateTime($ebd['ebf-date-departure']))->format('Y-m-d'));
                $booking->set('quantity', $ebd['ebf-quantity']);

                $booking->setMeta('notes', $ebd['ebf-notes']);

                /* Save booking */

                $bookingManager->save($booking);

                $this->flashMessenger()->addSuccessMessage('Booking has been saved');

                /* Edit bill or redirect back */

                if ($ebd['ebf-edit-bill']) {
                    $billManager = $serviceManager->get('Bill\Manager\BillManager');
                    $bills = $billManager->getBy(array('booking' => $booking->need('bid')));

                    if ($bills) {
                        return $this->redirect()->toRoute('backend/bill/edit', ['bid' => key($bills)]);
                    } else {
                        return $this->redirect()->toRoute('backend/bill/edit', [], ['query' => ['booking' => $booking->need('bid'), 'user' => $uid]]);
                    }
                } else {
                    return $this->redirect()->toRoute('backend/booking');
                }
            }
        } else {
            if ($booking) {
                if ($booking->getExtra('user')) {
                    $bookingUserLabel = sprintf('%s (%s)',
                        $booking->needExtra('user')->need('alias'),
                        $booking->needExtra('user')->need('uid'));
                } else {
                    $bookingUserLabel = null;
                }

                $editBookingForm->setData(array(
                    'ebf-rid' => $booking->get('rid'),
                    'ebf-user' => $bookingUserLabel,
                    'ebf-status' => $booking->need('status'),
                    'ebf-date-arrival' => $this->dateFormat($booking->get('date_arrival')),
                    'ebf-date-departure' => $this->dateFormat($booking->get('date_departure')),
                    'ebf-quantity' => $booking->need('quantity'),
                    'ebf-notes' => $booking->getMeta('notes'),
                ));
            } else {
                $editBookingForm->setData(array(
                    'ebf-rid' => $this->params()->fromQuery('rid'),
                    'ebf-date-arrival' => $this->params()->fromQuery('date-arrival'),
                    'ebf-date-departure' => $this->params()->fromQuery('date-departure'),
                    'ebf-edit-bill' => true,
                ));
            }
        }

        return array(
            'editBookingForm' => $editBookingForm,
            'booking' => $booking,
        );
    }

    public function deleteAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');

        $bid = $this->params()->fromRoute('bid');

        $booking = $bookingManager->get($bid);

        $bills = $billManager->getBy(array('booking' => $bid));

        if ($bills) {
            throw new RuntimeException('Booking cannot be deleted while there are bills for it');
        }

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $bookingManager->delete($booking);

            $this->flashMessenger()->addSuccessMessage('Booking has been deleted');

            return $this->redirect()->toRoute('backend/booking');
        }

        return array(
            'booking' => $booking,
        );
    }

}