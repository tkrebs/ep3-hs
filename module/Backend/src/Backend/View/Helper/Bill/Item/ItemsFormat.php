<?php

namespace Backend\View\Helper\Bill\Item;

use Bill\Entity\Bill;
use Zend\View\Helper\AbstractHelper;

class ItemsFormat extends AbstractHelper
{

    public function __invoke(array $billItems, Bill $bill)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th>%s</th>',
            $view->t('Product'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Amount'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Price'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/bill/edit/component/edit-item', ['bid' => $bill->need('bid')]),
            $view->t('New product bill'));

        foreach ($billItems as $billItem) {
            $html .= $view->backendBillItemFormat($billItem, $bill);
        }

        $html .= '</table>';

        if (! $billItems) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No product bills found'));
        }

        return $html;
    }

}