<?php

namespace Bill\View\Helper;

use Bill\Entity\Bill;
use Bill\Manager\BillItemManager;
use Bill\Manager\BillManager;
use Bill\Manager\BillNightManager;
use Booking\Entity\Booking;
use DateTime;
use Zend\View\Helper\AbstractHelper;

class BillPreview extends AbstractHelper
{

    protected $billManager;
    protected $billItemManager;
    protected $billNightManager;

    public function __construct(BillManager $billManager, BillItemManager $billItemManager, BillNightManager $billNightManager)
    {
        $this->billManager = $billManager;
        $this->billItemManager = $billItemManager;
        $this->billNightManager = $billNightManager;
    }

    public function __invoke(Bill $bill, $editable = false)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="compact-table">';
        $html .= '<tr>';

        $html .= sprintf('<td class="gray" style="width: 120px;">%s:</td>',
            $view->t('Pricing'));

        $html .= '<td>';

        /* Pricing (Start) */

        $html .= '<table class="compact-table" style="margin-bottom: 16px;">';

        $pricingTotal = 0;

        /* Pricing (Nights) */

        $billNights = $this->billNightManager->getBy(array('bid' => $bill->need('bid')));

        foreach ($billNights as $billNight) {
            $dateArrival = new DateTime($billNight->need('date_arrival'));
            $dateDeparture = new DateTime($billNight->need('date_departure'));

            $nights = $dateArrival->diff($dateDeparture)->format('%a');

            $html .= sprintf('<tr><td class="right-text">%s</td><td>%s</td><td><span class="small-text">%s</span></td><td><b>%s&times; %s</b></td></tr>',
                $view->priceFormat($billNight->need('price')),
                $view->priceFormat(null, $billNight->need('rate'), $billNight->need('gross')),
                $view->t('for'),
                $nights,
                $view->t('Night'));

            $pricingTotal += $billNight->need('price');
        }

        /* Pricing (Items) */

        $billItems = $this->billItemManager->getBy(array('bid' => $bill->need('bid')), 'priority DESC, biid ASC');

        foreach ($billItems as $billItem) {
            $billItemPrice = $billItem->need('price');

            if ($billItemPrice > 0) {
                $html .= sprintf('<tr><td class="right-text">%s</td><td>%s</td><td><span class="small-text">%s</span></td><td><b>%s&times; %s</b></td></tr>',
                    $view->priceFormat($billItemPrice),
                    $view->priceFormat(null, $billItem->need('rate'), $billItem->need('gross')),
                    $view->t('for'),
                    $billItem->need('amount'),
                    $billItem->need('pid_name'));
            } else {
                $html .= sprintf('<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td><b>%s&times; %s</b></td></tr>',
                    $billItem->need('amount'),
                    $billItem->need('pid_name'));
            }

            $pricingTotal += $billItemPrice;
        }

        /* Pricing (Total) */

        $html .= sprintf('<tr><td class="right-text" %s>%s</td><td %1$s>&nbsp;</td><td %1$s>&nbsp;</td><td %1$s><b>%s</b></td>',
            'style="padding-top: 4px; border-top: solid 1px #999;"',
            $view->priceFormat($pricingTotal),
            $view->t('Total'));

        $html .= '</table>';

        /* Pricing (End) */

        $html .= '</td>';
        $html .= '</tr>';

        /* Further bill information */

        if ($bill->get('bnr')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Number'), $bill->get('bnr'));
        }

        switch ($bill->getMeta('payment.method')) {
            case 'paypal':
                $paymentMethod = 'PayPal';
                break;
            case 'invoice':
                $paymentMethod = 'Invoice';
                break;
            default:
                $paymentMethod = 'Unknown';
        }

        $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
            $view->t('Payment method'), $view->t($paymentMethod));

        $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
            $view->t('Status'), $view->t($bill->getStatus()));

        if ($bill->get('bundle_name')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Bundle'), $bill->get('bundle_name'));
        }

        $html .= '</table>';

        /* Edit */

        if ($editable) {
            $html .= '<div class="separator-small separator-line"></div>';

            $html .= '<div class="symbolic-link-list centered-text">';

            $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link" target="_blank">%s</a>',
                $view->url('backend/bill/edit', ['bid' => $bill->need('bid')]),
                $view->t('Edit'));

            $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link" target="_blank">%s</a>',
                $view->url('backend/bill/edit/component', ['bid' => $bill->need('bid')]),
                $view->t('Edit components'));

            $html .= '</div>';
        }

        return $html;
    }

    public function fromBooking(Booking $booking, $editable = false)
    {
        $html = '';

        $bills = $this->billManager->getBy(array('booking' => $booking->need('bid')));

        foreach ($bills as $bill) {
            $html .= $this($bill, $editable);

            if (count($bills) > 1) {
                $html .= '<div class="separator separator-line"></div>';
            }
        }

        return $html;
    }

}