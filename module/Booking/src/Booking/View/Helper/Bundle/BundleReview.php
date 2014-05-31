<?php

namespace Booking\View\Helper\Bundle;

use Bundle\Entity\Bundle;
use Zend\View\Helper\AbstractHelper;

class BundleReview extends AbstractHelper
{

    public function __invoke(Bundle $bundle, array $bundleItems)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="compact-table">';

        $html .= sprintf('<tr><td class="gray" style="width: 120px;">%s:</td><td>%s</td></tr>',
            $view->t('Bundle'), $bundle->getMeta('name'));

        if ($bundle->getExtra('bill')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Status'), $view->t($bundle->needExtra('bill')->getStatus()));
        }

        $html .= '<tr>';

        $html .= sprintf('<td class="gray">%s:</td>',
            $view->t('Pricing'));

        $html .= '<td>';

        $html .= '<table class="compact-table">';

        /* Night */

        $nights = $bundle->needExtra('nights');

        $bundleNight = $bundle->needExtra('night');

        $pricePerNight = $bundleNight->need('price');
        $priceTotal = $pricePerNight * $nights;
        $priceRate = $bundleNight->need('rate');
        $priceGross = $bundleNight->need('gross');

        $html .= sprintf('<tr><td class="right-text">%s</td><td>%s</td><td><span class="small-text">%s</span></td><td><b>%s&times; %s</b></td></tr>',
            $view->priceFormat($priceTotal),
            $view->priceFormat(null, $priceRate, $priceGross),
            $view->t('for'),
            $nights,
            $view->t('Night'));

        /* Items */

        foreach ($bundleItems as $bundleItem) {
            $bundleItemAmount = $bundleItem->needExtra('amount');

            if ($bundleItem->need('due') == 'per_night') {
                if ($bundleItemAmount == 1) {
                    $bundleItemAmount = $nights;
                }
            }

            $bundleItemPrice = $bundleItem->need('price');
            $bundleItemPriceTotal = $bundleItemPrice * $bundleItemAmount;
            $bundleItemPriceRate = $bundleItem->need('rate');
            $bundleItemPriceGross = $bundleItem->need('gross');

            if ($bundleItemAmount > 0) {
                if ($bundleItemPriceTotal > 0) {
                    $html .= sprintf('<tr><td class="right-text">%s</td><td>%s</td><td><span class="small-text">%s</span></td><td><b>%s&times; %s</b></td></tr>',
                        $view->priceFormat($bundleItemPriceTotal),
                        $view->priceFormat(null, $bundleItemPriceRate, $bundleItemPriceGross),
                        $view->t('for'),
                        $bundleItemAmount,
                        $bundleItem->needExtra('product')->getMeta('name'));
                } else {
                    $html .= sprintf('<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td><b>%s&times; %s</b></td></tr>',
                        $bundleItemAmount,
                        $bundleItem->needExtra('product')->getMeta('name'));
                }


                $priceTotal += $bundleItemPriceTotal;
            }
        }

        /* Total */

        $html .= sprintf('<tr><td class="right-text" %s>%s</td><td %1$s>&nbsp;</td><td %1$s>&nbsp;</td><td %1$s><b>%s</b></td>',
            'style="padding-top: 4px; border-top: solid 1px #999;"',
            $view->priceFormat($priceTotal),
            $view->t('Total'));

        $html .= '</table>';

        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

}