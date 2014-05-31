<?php

namespace Backend\Controller;

use Bundle\Entity\Bundle;
use Bundle\Entity\BundleItem;
use Bundle\Entity\BundleNight;
use DateTime;
use Zend\Mvc\Controller\AbstractActionController;

class BundleController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $roomManager = $serviceManager->get('Room\Manager\RoomManager');

        $bundles = $bundleManager->getAll('priority DESC, bid DESC');

        $roomManager->getByBundles($bundles);

        return array(
            'bundles' => $bundles,
        );
    }

    public function editAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        if ($bid) {
            $bundle = $bundleManager->get($bid);
        } else {
            $bundle = null;
        }

        $editBundleForm = $formElementManager->get('Backend\Form\Bundle\EditBundleForm');

        if ($this->getRequest()->isPost()) {
            $editBundleForm->setData($this->params()->fromPost());

            if ($editBundleForm->isValid()) {
                $ebd = $editBundleForm->getData();

                if (! $bundle) {
                    $bundle = new Bundle();
                }

                /* Determine room */

                $bundleRoom = $ebd['ebf-rid'];

                if ($bundleRoom == '0') {
                    $bundleRoom = null;
                }

                /* Determine code */

                $bundleCode = $ebd['ebf-code'];

                if (! $bundleCode) {
                    $bundleCode = null;
                }

                /* Determine dates */

                $dateStart = $ebd['ebf-date-start'];

                if ($dateStart) {
                    $dateStart = (new DateTime($dateStart))->format('Y-m-d');
                } else {
                    $dateStart = null;
                }

                $dateEnd = $ebd['ebf-date-end'];

                if ($dateEnd) {
                    $dateEnd = (new DateTime($dateEnd))->format('Y-m-d');
                } else {
                    $dateEnd = null;
                }

                /* Set properties */

                $bundle->set('rid', $bundleRoom);
                $bundle->set('status', 'enabled');
                $bundle->set('code', $bundleCode);
                $bundle->set('priority', $ebd['ebf-priority']);
                $bundle->set('date_start', $dateStart);
                $bundle->set('date_end', $dateEnd);

                $bundle->setMeta('name', $ebd['ebf-name']);

                /* Save bundle */

                $bundleManager->save($bundle);

                $this->flashMessenger()->addSuccessMessage('Bundle has been saved');

                return $this->redirect()->toRoute('backend/bundle');
            }
        } else {
            if ($bundle) {
                $editBundleForm->setData(array(
                    'ebf-rid' => $bundle->get('rid'),
                    'ebf-code' => $bundle->get('code'),
                    'ebf-priority' => $bundle->get('priority'),
                    'ebf-date-start' => $this->dateFormat($bundle->get('date_start')),
                    'ebf-date-end' => $this->dateFormat($bundle->get('date_end')),
                    'ebf-name' => $bundle->getMeta('name'),
                ));
            }
        }

        return array(
            'editBundleForm' => $editBundleForm,
            'bundle' => $bundle,
        );
    }

    public function componentAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $bundleItemManager = $serviceManager->get('Bundle\Manager\BundleItemManager');
        $bundleNightManager = $serviceManager->get('Bundle\Manager\BundleNightManager');
        $productManager = $serviceManager->get('Product\Manager\ProductManager');

        $bid = $this->params()->fromRoute('bid');

        $bundle = $bundleManager->get($bid);
        $bundleItems = $bundleItemManager->getBy(array('bid' => $bid), 'priority DESC, biid ASC');
        $bundleNights = $bundleNightManager->getBy(array('bid' => $bid), 'bnid ASC');

        $productManager->getByBundleItems($bundleItems);

        return array(
            'bundle' => $bundle,
            'bundleItems' => $bundleItems,
            'bundleNights' => $bundleNights,
        );
    }

    public function editItemAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $bundleItemManager = $serviceManager->get('Bundle\Manager\BundleItemManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        $bundle = $bundleManager->get($bid);

        $biid = $this->params()->fromRoute('biid');

        if ($biid) {
            $bundleItem = $bundleItemManager->get($biid);
        } else {
            $bundleItem = null;
        }

        $editBundleItemForm = $formElementManager->get('Backend\Form\Bundle\Item\EditBundleItemForm');

        if ($this->getRequest()->isPost()) {
            $editBundleItemForm->setData($this->params()->fromPost());

            if ($editBundleItemForm->isValid()) {
                $ebid = $editBundleItemForm->getData();

                if (! $bundleItem) {
                    $bundleItem = new BundleItem();
                    $bundleItem->set('bid', $bid);
                }

                $bundleItem->set('pid', $ebid['ebif-pid']);
                $bundleItem->set('priority', $ebid['ebif-priority']);
                $bundleItem->set('due', $ebid['ebif-due']);

                switch ($ebid['ebif-due']) {
                    case 'per_night':
                        if ($ebid['ebif-amount-required']) {
                            $amountMin = 1;
                            $amountMax = 1;
                        } else {
                            $amountMin = 0;
                            $amountMax = 1;
                        }
                        break;
                    case 'per_item':
                    default:
                        $amountMin = $ebid['ebif-amount-min'];
                        $amountMax = $ebid['ebif-amount-max'];
                }

                $bundleItem->set('amount_min', $amountMin);
                $bundleItem->set('amount_max', $amountMax);
                $bundleItem->set('price', $ebid['ebif-price'] * 100);
                $bundleItem->set('price_fixed', 0);
                $bundleItem->set('rate', $ebid['ebif-rate']);
                $bundleItem->set('gross', $ebid['ebif-gross']);

                $bundleItemManager->save($bundleItem);

                $this->flashMessenger()->addSuccessMessage('Bundle item rule has been saved');

                return $this->redirect()->toRoute('backend/bundle/edit/component', ['bid' => $bid]);
            }
        } else {
            if ($bundleItem) {
                $amountMin = $bundleItem->need('amount_min');
                $amountMax = $bundleItem->need('amount_max');

                $editBundleItemForm->setData(array(
                    'ebif-pid' => $bundleItem->need('pid'),
                    'ebif-priority' => $bundleItem->need('priority'),
                    'ebif-due' => $bundleItem->need('due'),
                    'ebif-amount-required' => $amountMin == 1 && $amountMax == 1 ? true : false,
                    'ebif-amount-min' => $amountMin,
                    'ebif-amount-max' => $amountMax,
                    'ebif-price' => $bundleItem->need('price') / 100,
                    'ebif-rate' => $bundleItem->need('rate'),
                    'ebif-gross' => $bundleItem->need('gross'),
                ));
            }
        }

        return array(
            'bundle' => $bundle,
            'bundleItem' => $bundleItem,
            'editBundleItemForm' => $editBundleItemForm,
        );
    }

    public function deleteItemAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleItemManager = $serviceManager->get('Bundle\Manager\BundleItemManager');

        $bid = $this->params()->fromRoute('bid');
        $biid = $this->params()->fromRoute('biid');

        $bundleItemManager->delete($biid);

        $this->flashMessenger()->addSuccessMessage('Bundle product rule has been deleted');

        return $this->redirect()->toRoute('backend/bundle/edit/component', ['bid' => $bid]);
    }

    public function editNightAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $bundleNightManager = $serviceManager->get('Bundle\Manager\BundleNightManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $bid = $this->params()->fromRoute('bid');

        $bundle = $bundleManager->get($bid);

        $bnid = $this->params()->fromRoute('bnid');

        if ($bnid) {
            $bundleNight = $bundleNightManager->get($bnid);
        } else {
            $bundleNight = null;
        }

        $editBundleNightForm = $formElementManager->get('Backend\Form\Bundle\Night\EditBundleNightForm');

        if ($this->getRequest()->isPost()) {
            $editBundleNightForm->setData($this->params()->fromPost());

            if ($editBundleNightForm->isValid()) {
                $ebnd = $editBundleNightForm->getData();

                if (! $bundleNight) {
                    $bundleNight = new BundleNight();
                    $bundleNight->set('bid', $bid);
                }

                $bundleNight->set('nights_min', $ebnd['ebnf-nights-min']);
                $bundleNight->set('nights_max', $ebnd['ebnf-nights-max']);
                $bundleNight->set('price', $ebnd['ebnf-price'] * 100);
                $bundleNight->set('price_fixed', 0);
                $bundleNight->set('rate', $ebnd['ebnf-rate']);
                $bundleNight->set('gross', $ebnd['ebnf-gross']);

                $bundleNightManager->save($bundleNight);

                $this->flashMessenger()->addSuccessMessage('Bundle night rule has been saved');

                return $this->redirect()->toRoute('backend/bundle/edit/component', ['bid' => $bid]);
            }
        } else {
            if ($bundleNight) {
                $editBundleNightForm->setData(array(
                    'ebnf-nights-min' => $bundleNight->need('nights_min'),
                    'ebnf-nights-max' => $bundleNight->need('nights_max'),
                    'ebnf-price' => $bundleNight->need('price') / 100,
                    'ebnf-rate' => $bundleNight->need('rate'),
                    'ebnf-gross' => $bundleNight->need('gross'),
                ));
            }
        }

        return array(
            'bundle' => $bundle,
            'bundleNight' => $bundleNight,
            'editBundleNightForm' => $editBundleNightForm,
        );
    }

    public function deleteNightAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleNightManager = $serviceManager->get('Bundle\Manager\BundleNightManager');

        $bid = $this->params()->fromRoute('bid');
        $bnid = $this->params()->fromRoute('bnid');

        $bundleNightManager->delete($bnid);

        $this->flashMessenger()->addSuccessMessage('Bundle night rule has been deleted');

        return $this->redirect()->toRoute('backend/bundle/edit/component', ['bid' => $bid]);
    }

    public function deleteAction()
    {
        $this->authorize('admin');

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');

        $bid = $this->params()->fromRoute('bid');

        $bundle = $bundleManager->get($bid);

        $confirmed = $this->params()->fromQuery('confirmed');

        if ($confirmed == 'true') {
            $bundleManager->delete($bundle);

            $this->flashMessenger()->addSuccessMessage('Bundle has been deleted');

            return $this->redirect()->toRoute('backend/bundle');
        }

        return array(
            'bundle' => $bundle,
        );
    }

}