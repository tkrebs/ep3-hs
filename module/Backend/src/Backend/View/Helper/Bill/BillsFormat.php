<?php

namespace Backend\View\Helper\Bill;

use Zend\View\Helper\AbstractHelper;

class BillsFormat extends AbstractHelper
{

    public function __invoke(array $bills)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th style="width: 16px;">%s</th>',
            $view->t('ID'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Number'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Status'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Booking'));

        $html .= sprintf('<th>%s</th>',
            $view->t('User'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/bill/edit'), $view->t('New bill'));

        foreach ($bills as $bill) {
            $html .= $view->backendBillFormat($bill);
        }

        $html .= '</table>';

        if (! $bills) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No bills found'));
        }

        return $html;
    }

}