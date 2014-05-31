<?php

namespace Booking\View\Helper\Bundle\Night;

use Bundle\Entity\BundleNight;
use Zend\View\Helper\AbstractHelper;

class BundleNightFormat extends AbstractHelper
{

    public function __invoke(BundleNight $bundleNight = null, $nights = null)
    {
        $view = $this->getView();
        $html = '';

        if (! $bundleNight) {
            $html .= sprintf('<div><em class="symbolic symbolic-warning">%s</em></div>',
                $view->t('We are very sorry, but this bundle is not applicable for this visit.'));

            return $html;
        }

        $html .= '<div class="sandbox">';
        $html .= '<table class="compact-table compact-medium-table full-width">';

        $pricePerNight = $bundleNight->need('price');
        $priceRate = $bundleNight->need('rate');
        $priceGross = $bundleNight->need('gross');

        $html .= sprintf('<tr><td class="right-text gray">%s:</td><td class="bf-bn-price-col bordered-col no-wrap" style="width: 120px;">%s</td></tr>',
            $view->t('Price per night'),
            $view->priceFormat($pricePerNight, $priceRate, $priceGross));

        if (is_numeric($nights)) {

            $priceTotal = $pricePerNight * $nights;

            $html .= sprintf('<tr><td class="right-text gray">%s %s %s %s:</td><td class="bf-bn-price-col bordered-col no-wrap" style="width: 120px;">%s</td></tr>',
                $view->t('Price'),
                $view->t('for'),
                $nights,
                ($nights == 1 ? $view->t('night') : $view->t('nights')),
                $view->priceFormat($priceTotal, $priceRate, $priceGross));
        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

}