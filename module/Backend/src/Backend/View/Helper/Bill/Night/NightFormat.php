<?php

namespace Backend\View\Helper\Bill\Night;

use Bill\Entity\Bill;
use Bill\Entity\BillNight;
use DateTime;
use IntlDateFormatter;
use Zend\View\Helper\AbstractHelper;

class NightFormat extends AbstractHelper
{

    public function __invoke(BillNight $billNight, Bill $bill)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $view->dateFormat(new DateTime($billNight->need('date_arrival')), IntlDateFormatter::MEDIUM));

        $html .= sprintf('<td>%s</td>',
            $view->dateFormat(new DateTime($billNight->need('date_departure')), IntlDateFormatter::MEDIUM));

        $html .= sprintf('<td>%s</td>',
            $billNight->need('quantity'));

        $html .= sprintf('<td>%s</td>',
            $view->priceFormat($billNight->need('price'), $billNight->need('rate'), $billNight->need('gross')));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/bill/edit/component/edit-night', ['bid' => $bill->need('bid'), 'bnid' => $billNight->need('bnid')]), $view->t('Edit'),
            $view->url('backend/bill/edit/component/delete-night', ['bid' => $bill->need('bid'), 'bnid' => $billNight->need('bnid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}