<?php

namespace Backend\View\Helper\Bill\Item;

use Bill\Entity\Bill;
use Bill\Entity\BillItem;
use Zend\View\Helper\AbstractHelper;

class ItemFormat extends AbstractHelper
{

    public function __invoke(BillItem $billItem, Bill $bill)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $billItem->need('pid_name'));

        $html .= sprintf('<td>%s</td>',
            $billItem->need('amount'));

        $html .= sprintf('<td>%s</td>',
            $view->priceFormat($billItem->need('price'), $billItem->need('rate'), $billItem->need('gross')));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/bill/edit/component/edit-item', ['bid' => $bill->need('bid'), 'biid' => $billItem->need('biid')]), $view->t('Edit'),
            $view->url('backend/bill/edit/component/delete-item', ['bid' => $bill->need('bid'), 'biid' => $billItem->need('biid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}