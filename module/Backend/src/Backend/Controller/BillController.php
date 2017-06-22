<?php

namespace Backend\Controller;

use Bill\Entity\Bill;
use Bill\Entity\BillItem;
use Bill\Entity\BillNight;
use DateTime;
use RuntimeException;
use Zend\I18n\Filter\NumberFormat;
use Zend\Mvc\Controller\AbstractActionController;

class BillController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $bills = $billManager->getAll('bid DESC');

        $bookingManager->getByBills($bills);
        $userManager->getByBills($bills);

        return array(
            'bills' => $bills,
        );
    }

    public function editAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        if ($bid) {
            $bill = $billManager->get($bid);

            $bookingId = $bill->get('booking');

            if ($bookingId) {
                $bill->setExtra('booking', $bookingManager->get($bookingId));
            }

            $bill->setExtra('user', $userManager->get($bill->need('user')));
        } else {
            $bill = null;
        }

        $editBillForm = $formElementManager->get('Backend\Form\Bill\EditBillForm');

        if ($this->getRequest()->isPost()) {
            $editBillForm->setData($this->params()->fromPost());

            if ($editBillForm->isValid()) {
                $editBillData = $editBillForm->getData();

                if (! $bill) {
                    $bill = new Bill();
                    $bill->setExtra('created', true);
                }

                $bill->set('bnr', $editBillData['ebf-bnr']);
                $bill->set('status', $editBillData['ebf-status']);

                /* Determine booking */

                $bookingId = $editBillData['ebf-booking'];

                if ($bookingId) {
                    $bookingManager->get($bookingId);

                    $bill->set('booking', $bookingId);
                }

                /* Determine user */

                preg_match('/\(([0-9]+)\)$/', $editBillData['ebf-user'], $matches);

                if (! (isset($matches[1]) && is_numeric($matches[1]))) {
                    throw new RuntimeException('Invalid user passed');
                }

                $uid = $matches[1];

                $userManager->get($uid);

                $bill->set('user', $uid);

                /* Save bill */

                $billManager->save($bill);

                $this->flashMessenger()->addSuccessMessage('Bill has been saved');

                if ($bill->getExtra('created')) {
                    return $this->redirect()->toRoute('backend/bill/edit/component', ['bid' => $bill->need('bid')]);
                } else {
                    return $this->redirect()->toRoute('backend/bill');
                }
            }
        } else {
            if ($bill) {
                $editBillForm->setData(array(
                    'ebf-bnr' => $bill->get('bnr'),
                    'ebf-status' => $bill->need('status'),
                    'ebf-booking' => $bill->get('booking'),
                    'ebf-user' => sprintf('%s (%s)',
                        $bill->needExtra('user')->need('alias'),
                        $bill->needExtra('user')->need('uid')),
                ));
            } else {
                $presetBookingId = $this->params()->fromQuery('booking');

                if ($presetBookingId) {
                    $presetBooking = $bookingManager->get($presetBookingId);

                    $editBillForm->setData(array(
                        'ebf-booking' => $presetBooking->need('bid'),
                    ));
                }

                $presetUserId = $this->params()->fromQuery('user');

                if ($presetUserId) {
                    $presetUser = $userManager->get($presetUserId);

                    $editBillForm->setData(array(
                        'ebf-user' => sprintf('%s (%s)',
                            $presetUser->need('alias'),
                            $presetUser->need('uid')),
                    ));
                }
            }
        }

        return array(
            'editBillForm' => $editBillForm,
            'bill' => $bill,
        );
    }

    public function componentAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $billItemManager = $serviceManager->get('Bill\Manager\BillItemManager');
        $billNightManager = $serviceManager->get('Bill\Manager\BillNightManager');
        $productManager = $serviceManager->get('Product\Manager\ProductManager');

        $bid = $this->params()->fromRoute('bid');

        $bill = $billManager->get($bid);
        $billItems = $billItemManager->getBy(array('bid' => $bid), 'priority DESC, biid ASC');
        $billNights = $billNightManager->getBy(array('bid' => $bid), 'bnid ASC');

        $productManager->getByBillItems($billItems);

        return array(
            'bill' => $bill,
            'billItems' => $billItems,
            'billNights' => $billNights,
        );
    }

    public function editItemAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $billItemManager = $serviceManager->get('Bill\Manager\BillItemManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        $bill = $billManager->get($bid);

        $biid = $this->params()->fromRoute('biid');

        if ($biid) {
            $billItem = $billItemManager->get($biid);
        } else {
            $billItem = null;
        }

        $editBillItemForm = $formElementManager->get('Backend\Form\Bill\Item\EditBillItemForm');

        if ($this->getRequest()->isPost()) {
            $editBillItemForm->setData($this->params()->fromPost());

            if ($editBillItemForm->isValid()) {
                $ebid = $editBillItemForm->getData();

                if (! $billItem) {
                    $billItem = new BillItem();
                    $billItem->set('bid', $bid);
                    $billItem->set('priority', 0);
                }

                $oldProductName = $billItem->get('pid_name');
                $newProductName = $ebid['ebif-pid-name'];

                if ($oldProductName != $newProductName) {
                    $billItem->set('pid', null);
                }

                $billItem->set('pid_name', $newProductName);
                $billItem->set('amount', $ebid['ebif-amount']);
                $billItem->set('price', $ebid['ebif-price'] * 100);
                $billItem->set('rate', $ebid['ebif-rate']);
                $billItem->set('gross', $ebid['ebif-gross']);

                $billItemManager->save($billItem);

                $this->flashMessenger()->addSuccessMessage('Product bill has been saved');

                return $this->redirect()->toRoute('backend/bill/edit/component', ['bid' => $bid]);
            }
        } else {
            if ($billItem) {
                $editBillItemForm->setData(array(
                    'ebif-pid-name' => $billItem->need('pid_name'),
                    'ebif-amount' => $billItem->need('amount'),
                    'ebif-price' => (New NumberFormat())->filter($billItem->need('price') / 100),
                    'ebif-rate' => $billItem->need('rate'),
                    'ebif-gross' => $billItem->need('gross'),
                ));
            }
        }

        return array(
            'bill' => $bill,
            'billItem' => $billItem,
            'editBillItemForm' => $editBillItemForm,
        );
    }

    public function deleteItemAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billItemManager = $serviceManager->get('Bill\Manager\BillItemManager');

        $bid = $this->params()->fromRoute('bid');
        $biid = $this->params()->fromRoute('biid');

        $billItemManager->delete($biid);

        $this->flashMessenger()->addSuccessMessage('Product bill has been deleted');

        return $this->redirect()->toRoute('backend/bill/edit/component', ['bid' => $bid]);
    }

    public function editNightAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');
        $billNightManager = $serviceManager->get('Bill\Manager\BillNightManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        $bill = $billManager->get($bid);

        $bnid = $this->params()->fromRoute('bnid');

        if ($bnid) {
            $billNight = $billNightManager->get($bnid);
        } else {
            $billNight = null;
        }

        $editBillNightForm = $formElementManager->get('Backend\Form\Bill\Night\EditBillNightForm');

        if ($this->getRequest()->isPost()) {
            $editBillNightForm->setData($this->params()->fromPost());

            if ($editBillNightForm->isValid()) {
                $ebnd = $editBillNightForm->getData();

                if (! $billNight) {
                    $billNight = new BillNight();
                    $billNight->set('bid', $bid);
                }

                $billNight->set('date_arrival', (new DateTime($ebnd['ebnf-date-arrival']))->format('Y-m-d'));
                $billNight->set('date_departure', (new DateTime($ebnd['ebnf-date-departure']))->format('Y-m-d'));
                $billNight->set('quantity', $ebnd['ebnf-quantity']);
                $billNight->set('price', $ebnd['ebnf-price'] * 100);
                $billNight->set('rate', $ebnd['ebnf-rate']);
                $billNight->set('gross', $ebnd['ebnf-gross']);

                $billNightManager->save($billNight);

                $this->flashMessenger()->addSuccessMessage('Night bill has been saved');

                return $this->redirect()->toRoute('backend/bill/edit/component', ['bid' => $bid]);
            }
        } else {
            if ($billNight) {
                $editBillNightForm->setData(array(
                    'ebnf-date-arrival' => $this->dateFormat($billNight->need('date_arrival')),
                    'ebnf-date-departure' => $this->dateFormat($billNight->need('date_departure')),
                    'ebnf-quantity' => $billNight->need('quantity'),
                    'ebnf-price' => (New NumberFormat())->filter($billNight->need('price') / 100),
                    'ebnf-rate' => $billNight->need('rate'),
                    'ebnf-gross' => $billNight->need('gross'),
                ));
            }
        }

        return array(
            'bill' => $bill,
            'billNight' => $billNight,
            'editBillNightForm' => $editBillNightForm,
        );
    }

    public function deleteNightAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billNightManager = $serviceManager->get('Bill\Manager\BillNightManager');

        $bid = $this->params()->fromRoute('bid');
        $bnid = $this->params()->fromRoute('bnid');

        $billNightManager->delete($bnid);

        $this->flashMessenger()->addSuccessMessage('Night bill has been deleted');

        return $this->redirect()->toRoute('backend/bill/edit/component', ['bid' => $bid]);
    }

    public function deleteAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $billManager = $serviceManager->get('Bill\Manager\BillManager');

        $bid = $this->params()->fromRoute('bid');

        $bill = $billManager->get($bid);

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $billManager->delete($bill);

            $this->flashMessenger()->addSuccessMessage('Bill has been deleted');

            return $this->redirect()->toRoute('backend/bill');
        }

        return array(
            'bill' => $bill,
        );
    }

}
