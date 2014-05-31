<?php

namespace Booking\View\Helper\Bundle\Item;

use Bundle\Entity\BundleItem;
use Zend\View\Helper\AbstractHelper;

class BundleItemsFormat extends AbstractHelper
{

    public function __invoke(array $bundleItems, array $bundleItemsSelected = array())
    {
        $view = $this->getView();
        $html = '';

        if (! $bundleItems) {
            return null;
        }

        $html .= '<div class="sandbox">';
        $html .= '<table class="compact-table compact-medium-table full-width">';

        foreach ($bundleItems as $biid => $bundleItem) {
            $bundleItemAmount = null;

            if (isset($bundleItemsSelected[$biid])) {
                $bundleItemSelected = $bundleItemsSelected[$biid];

                if ($bundleItemSelected instanceof BundleItem) {
                    $bundleItemAmount = $bundleItemSelected->getExtra('amount');
                }
            }

            $html .= $view->bookingBundleItemFormat($bundleItem, $bundleItemAmount);
        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

}