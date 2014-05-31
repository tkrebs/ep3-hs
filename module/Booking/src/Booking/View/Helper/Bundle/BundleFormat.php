<?php

namespace Booking\View\Helper\Bundle;

use Bundle\Entity\Bundle;
use Bundle\Manager\BundleItemManager;
use Bundle\Manager\BundleNightManager;
use Product\Manager\ProductManager;
use Zend\View\Helper\AbstractHelper;

class BundleFormat extends AbstractHelper
{

    protected $bundleItemManager;
    protected $bundleNightManager;
    protected $productManager;

    public function __construct(BundleItemManager $bundleItemManager, BundleNightManager $bundleNightManager, ProductManager $productManager)
    {
        $this->bundleItemManager = $bundleItemManager;
        $this->bundleNightManager = $bundleNightManager;
        $this->productManager = $productManager;
    }

    public function __invoke(Bundle $bundle, $nights, Bundle $bundleSelected = null)
    {
        $view = $this->getView();
        $html = '';

        $bundleNightRule = $this->bundleNightManager->getByNights($bundle, $nights);

        if (! $bundleNightRule) {
            return null;
        }

        $html .= '<div class="bundle">';

        $html .= sprintf('<div class="small-text gray" style="float: right; margin-top: 4px; cursor: default;">%s</div>',
            sprintf($view->t('Starting at %s / night'),
                $view->priceFormat($bundleNightRule->need('price'))));

        $html .= sprintf('<label for="bf-bundle-%s" class="symbolic symbolic-tickets large-text" style="cursor: pointer;">%s</label>',
            $bundle->need('bid'), $bundle->getMeta('name'));

        if ($bundleSelected && $bundleSelected->need('bid') == $bundle->need('bid')) {
            $checked = 'checked="checked"';
        } else {
            $checked = null;
        }

        $html .= sprintf('<input type="radio" name="bf-bundle" id="bf-bundle-%s" class="bf-bundle" value="%s" %s>',
            $bundle->need('bid'), $bundle->need('bid'), $checked);

        $html .= sprintf('<span class="bundle-enable small-text gray" style="display: none; margin-left: 8px; cursor: default;">&laquo; %s</span>',
            $view->t('Click to select'));

        $html .= sprintf('<span class="bundle-enabled small-text gray" style="display: none; margin-left: 8px; cursor: default;">&laquo; <b>%s</b></span>',
            $view->t('Selected'));

        $html .= '<div class="bundle-customization" style="margin: 8px 0px 32px 32px;">';

        /* Night Rules */

        $html .= $view->bookingBundleNightFormat($bundleNightRule, $nights);

        /* Item Rules */

        $bundleItemRules = $this->bundleItemManager->getBy(array('bid' => $bundle->need('bid')), 'priority DESC');

        if ($bundleItemRules) {
            $html .= sprintf('<div class="centered-text gray" style="margin: 8px 0px;">+</div>');
        }

        $this->productManager->getByBundleItems($bundleItemRules);

        if ($bundleSelected) {
            $bundleItemsSelected = $bundleSelected->getExtra('items', array());
        } else {
            $bundleItemsSelected = array();
        }

        $html .= $view->bookingBundleItemsFormat($bundleItemRules, $bundleItemsSelected);

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

}