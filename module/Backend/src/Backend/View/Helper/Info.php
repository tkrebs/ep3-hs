<?php

namespace Backend\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Info extends AbstractHelper
{

    public function __invoke($subject)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<p class="symbolic symbolic-info">';

        switch ($subject) {
            case 'product':
                $html .= $view->t('A product can be an actual product or a service you offer to your clients in addition to bare night bookings.');
                break;
            case 'bundle':
                $html .= $view->t('A bundle is a package of pricing rules for nights, products and services. It basically defines what clients can choose and how much it costs.');
                break;
            case 'bundle-item':
                $html .= $view->t('A bundle product rule defines the pricing for a product or service for a specific amount of units. Multiple rules can be combined for the final pricing.');
                break;
            case 'bundle-night':
                $html .= $view->t('A bundle night rule defines the pricing for a night. Multiple rules can be combined for the final pricing.');
                break;
            case 'booking':
                $html .= $view->t('A booking is a reservation of a room for a specific amount of time. Details like products and pricing can be set by one or more related bills.');
                break;
            case 'bill':
                $html .= $view->t('A bill is a package of night bills and product bills. It is usually related to bookings. It basically defines what clients have choosen and how much it costs.');
                break;
            case 'user':
                $html .= $view->t('User is a collective term for people interacting with your system. This includes you, your staff, your one-time guests and your registered regular guests.');
                break;
            case 'export':
                $html .= $view->t('Export your booking data to your favourite spreadsheet application (e.g. Microsoft Excel).');
                break;
            case 'config':
                $html .= $view->t('The configuration page allows you to customize the behaviour and data of your system.');
                break;
            case 'i18n':
                $html .= $view->t('Switch the language like usual (using the flags in the upper right corner) for language-dependent configuration.');
                break;
        }

        $html .= '</p>';

        return $html;
    }

}