<?php

namespace Backend\View\Helper\Bill;

use Bill\Entity\Bill;
use DateTime;
use IntlDateFormatter;
use Zend\View\Helper\AbstractHelper;

class BillFormat extends AbstractHelper
{

    public function __invoke(Bill $bill)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $bill->need('bid'));

        $html .= sprintf('<td>%s</td>',
            $bill->get('bnr', '-'));

        $html .= sprintf('<td>%s</td>',
            $view->t($bill->getStatus()));

        if ($bill->getExtra('booking')) {
            $html .= sprintf('<td><a href="%s" class="symbolic symbolic-booking symbolic-link" target="_blank">%s - %s</a></td>',
                $view->url('backend/booking/edit', ['bid' => $bill->needExtra('booking')->need('bid')]),
                $view->dateFormat(new DateTime($bill->needExtra('booking')->need('date_arrival')), IntlDateFormatter::SHORT),
                $view->dateFormat(new DateTime($bill->needExtra('booking')->need('date_departure')), IntlDateFormatter::SHORT));
        } else {
            $html .= sprintf('<td>-</td>');
        }

        $html .= sprintf('<td><a href="%s" class="symbolic symbolic-user symbolic-link" target="_blank">%s</a></td>',
            $view->url('backend/user/edit', ['uid' => $bill->needExtra('user')->need('uid')]),
            $bill->needExtra('user')->need('alias'));

        /* Actions */

        $html .= '<td class="symbolic-link-list">';

        $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a>',
            $view->url('backend/bill/edit', ['bid' => $bill->need('bid')]), $view->t('Edit'));

        $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a>',
            $view->url('backend/bill/edit/component', ['bid' => $bill->need('bid')]), $view->t('Edit components'));

        $html .= sprintf('<a href="%s" class="symbolic symbolic-cross symbolic-link" data-tooltip="%s"></a>',
            $view->url('backend/bill/delete', ['bid' => $bill->need('bid')]), $view->t('Delete'));

        $html .= '</td>';

        $html .= '</tr>';

        return $html;
    }

}