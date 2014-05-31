<?php

namespace Booking\Controller;

use RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class SupportController extends AbstractActionController
{

    public function pricingAction()
    {
        extract( $this->determineBookingParams() );

        $serviceManager = $this->getServiceLocator();
        $bundleManager = $serviceManager->get('Bundle\Manager\BundleManager');
        $bundleNightManager = $serviceManager->get('Bundle\Manager\BundleNightManager');

        $bundleParam = $this->params()->fromQuery('bf-bundle');

        $bundle = $bundleManager->get($bundleParam);

        /* Bunde Night */

        $bundleNightRule = $bundleNightManager->getByNights($bundle, $dateNights);

        if (! $bundleNightRule) {
            throw new RuntimeException('The selected bundle is not applicable for this visit');
        }

        $priceTotal = $bundleNightRule->need('price') * $dateNights;

        /* Bundle Items */

        $this->determineBookingBundleItemsCode($bundle, false);

        foreach ($bundle->needExtra('items') as $bundleItem) {
            $amount = $bundleItem->needExtra('amount');

            if ($bundleItem->need('due') == 'per_night') {
                if ($amount == 1) {
                    $amount = $dateNights;
                }
            }

            $priceTotal += $bundleItem->need('price') * $amount;
        }

        return $this->ajaxViewModel(array(
            'priceTotal' => $priceTotal,
        ));
    }

}