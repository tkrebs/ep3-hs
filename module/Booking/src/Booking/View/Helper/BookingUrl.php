<?php

namespace Booking\View\Helper;

use Zend\View\Helper\AbstractHelper;

class BookingUrl extends AbstractHelper
{

    public function __invoke($route, array $queryParams = array())
    {
        $view = $this->getView();

        if ($view->dateArrival && $view->dateDeparture && $view->capacity) {

            $query = array(
                'date-arrival' => $view->dateArrival->format('Y-m-d'),
                'date-departure' => $view->dateDeparture->format('Y-m-d'),
                'capacity' => $view->capacity,
            );

            if ($view->room) {
                $query['room'] = $view->room->need('rid');
            }

            if ($view->bundle) {
                $query['bundle'] = $view->bundle->need('bid');
            }

            if ($view->bundleItemsCode) {
                $query['bundle-items'] = $view->bundleItemsCode;
            }

            return $view->url($route, [], ['query' => array_merge($query, $queryParams)]);

        } else {

            return $view->url($route);

        }
    }

}