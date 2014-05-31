<?php

namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PriceFormat extends AbstractHelper
{

    public function __invoke($price = null, $rate = null, $gross = null)
    {
        $view = $this->getView();
        $html = '';

        if (is_numeric($price)) {
            $html .= '<b>' . $view->currencyFormat($price / 100) . '</b>';
        }

        if ($rate) {
            if ($gross) {
                $grossFormulation = $view->t('incl.');
            } else {
                $grossFormulation = $view->t('plus');
            }

            $html .= sprintf(' <span class="small-text">%s %s%% %s</span>',
                $grossFormulation, $rate, $view->t('VAT'));
        }

        return $html;
    }

}