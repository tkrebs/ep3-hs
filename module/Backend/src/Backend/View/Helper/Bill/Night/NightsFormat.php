<?php

namespace Backend\View\Helper\Bill\Night;

use Bill\Entity\Bill;
use Zend\View\Helper\AbstractHelper;

class NightsFormat extends AbstractHelper
{

    public function __invoke(array $billNights, Bill $bill)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th>%s</th>',
            $view->t('Arrival'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Departure'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Persons'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Price'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/bill/edit/component/edit-night', ['bid' => $bill->need('bid')]),
            $view->t('New night bill'));

        foreach ($billNights as $billNight) {
            $html .= $view->backendBillNightFormat($billNight, $bill);
        }

        $html .= '</table>';

        if (! $billNights) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No night bills found'));
        }

        return $html;
    }

}