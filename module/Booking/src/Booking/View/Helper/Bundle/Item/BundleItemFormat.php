<?php

namespace Booking\View\Helper\Bundle\Item;

use Bundle\Entity\BundleItem;
use Zend\View\Helper\AbstractHelper;

class BundleItemFormat extends AbstractHelper
{

    public function __invoke(BundleItem $bundleItem, $bundleItemAmount = null)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        /* Selection */

        $html .= '<td style="width: 76px;">';

        switch ($bundleItem->need('due')) {
            case 'per_night':
                $html .= $this->renderPerNightSelection($bundleItem, $bundleItemAmount);
                break;
            case 'per_item':
            default:
                $html .= $this->renderPerItemSelection($bundleItem, $bundleItemAmount);
        }

        $html .= '</td>';

        /* Name */

        if ($bundleItem->getExtra('product')) {
            $name = $bundleItem->getExtra('product')->getMeta('name');
            $description = $bundleItem->getExtra('product')->getMeta('description');
        } else {
            $name = $bundleItem->need('pid');
            $description = null;
        }

        if ($description) {
            $html .= sprintf('<td><div class="large-text" style="margin-top: 4px; margin-bottom: 4px;">%s</div><div>%s</div></td>',
                $name, $description);
        } else {
            $html .= sprintf('<td><div class="large-text" style="margin-top: 4px;">%s</div></td>',
                $name);
        }

        /* Price */

        if ($bundleItem->need('price') == 0) {
            $price = $view->t('included');
        } else {
            $price = sprintf('<span class="no-wrap">%s</span><br><span class="no-wrap small-text">(%s)</span>',
                $view->priceFormat($bundleItem->need('price'), $bundleItem->need('rate'), $bundleItem->need('gross')),
                $view->translate($bundleItem->getDue()));
        }

        $html .= sprintf('<td class="bf-bi-price-col bordered-col"><div style="margin-top: 7px;">%s</div></td>',
            $price);

        $html .= '</tr>';

        return $html;
    }

    protected function renderPerNightSelection(BundleItem $bundleItem, $bundleItemAmount = null)
    {
        $view = $this->getView();
        $html = '';

        $amountMin = $bundleItem->need('amount_min');
        $amountMax = $bundleItem->need('amount_max');

        if ($amountMin == $amountMax) {
            $style = 'opacity: 0.65;';
        } else {
            $style = null;
        }

        $html .= sprintf('<select name="bf-bi-%s" class="bf-bi" style="width: 76px; cursor: pointer; %s">',
            $bundleItem->need('biid'), $style);

        for ($i = $amountMin; $i <= $amountMax; $i++) {
            if ($i == 0) {
                $label = $view->t('No');
            } else {
                $label = $view->t('Yes');
            }

            if ($bundleItemAmount && $bundleItemAmount == $i) {
                $selected = 'selected="selected"';
            } else {
                $selected = null;
            }

            $html .= sprintf('<option value="%s" %s>%s</option>',
                $i, $selected, $label);
        }

        $html .= '</select>';

        return $html;
    }

    protected function renderPerItemSelection(BundleItem $bundleItem, $bundleItemAmount = null)
    {
        $view = $this->getView();
        $html = '';

        $amountMin = $bundleItem->need('amount_min');
        $amountMax = $bundleItem->need('amount_max');

        if ($amountMin > $amountMax) {
            $amountMin = $amountMax;
        }

        if ($amountMin == $amountMax) {
            $style = 'opacity: 0.65;';
        } else {
            $style = null;
        }

        $html .= sprintf('<select name="bf-bi-%s" class="bf-bi" style="width: 76px; cursor: pointer; %s">',
            $bundleItem->need('biid'), $style);

        for ($i = $amountMin; $i <= $amountMax; $i++) {
            if ($i == 0) {
                $label = $view->t('None');
            } else {
                $label = $i;
            }

            if ($bundleItemAmount && $bundleItemAmount == $i) {
                $selected = 'selected="selected"';
            } else {
                $selected = null;
            }

            $html .= sprintf('<option value="%s" %s>%s</option>',
                $i, $selected, $label);
        }

        $html .= '</select>';

        return $html;
    }

}