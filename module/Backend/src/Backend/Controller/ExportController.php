<?php

namespace Backend\Controller;

use DateTime;
use Zend\Http\Headers;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ExportController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $billItemManager = $serviceManager->get('Bill\Manager\BillItemManager');
        $billNightManager = $serviceManager->get('Bill\Manager\BillNightManager');
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $exportForm = $formElementManager->get('Backend\Form\Export\ExportForm');

        if ($this->getRequest()->isPost()) {
            $exportForm->setData($this->params()->fromPost());

            if ($exportForm->isValid()) {
                $ed = $exportForm->getData();

                if ($ed['ef-date-start']) {
                    $dateStart = new DateTime($ed['ef-date-start']);
                    $dateStart->setTime(0, 0, 0);
                } else {
                    $dateStart = null;
                }

                if ($ed['ef-date-end']) {
                    $dateEnd = new DateTime($ed['ef-date-end']);
                    $dateEnd->setTime(0, 0, 0);
                } else {
                    if ($dateStart) {
                        $dateEnd = clone $dateStart;
                        $dateEnd->modify('+999 days');
                    } else {
                        $dateEnd = null;
                    }
                }

                if ($dateStart) {
                    $bookings = $bookingManager->getBetween($dateStart, $dateEnd);
                } else {
                    $bookings = $bookingManager->getAll('date_arrival ASC');
                }

                $bills = $billManager->getByBookings($bookings);

                $billItemManager->getByBills($bills);
                $billNightManager->getByBills($bills);

                $roomManager->getByBookings($bookings);
                $userManager->getByBookings($bookings);

                $headers = new Headers();
                $headers->addHeaderLine('Content-Type', 'text/csv')
                        ->addHeaderLine('Content-Disposition', 'attachment; filename="bookings.csv"');

                $this->getResponse()->setHeaders($headers);

                $viewModel = new ViewModel();
                $viewModel->setTemplate('backend/export/bookings.phtml');
                $viewModel->setTerminal(true);
                $viewModel->setVariables(array(
                    'bookings' => $bookings,
                ));

                return $viewModel;
            }
        } else {
            $exportForm->get('ef-date-start')->setValue($this->dateFormat(new DateTime()));
        }

        return array(
            'exportForm' => $exportForm,
        );
    }

}