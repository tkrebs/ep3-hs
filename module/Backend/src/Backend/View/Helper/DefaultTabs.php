<?php

namespace Backend\View\Helper;

use Zend\View\Helper\AbstractHelper;

class DefaultTabs extends AbstractHelper
{

    public function __invoke()
    {
        $view = $this->getView();

        return array(
            'Logout' => array(
                'url' => $view->url('user/logout'),
                'outer-class' => 'tab-align-right',
            ),
            'Overview' => $view->url('backend/dashboard'),
            'Rooms' => $view->url('backend/room'),
            'Products' => $view->url('backend/product'),
            'Bundles' => $view->url('backend/bundle'),
            'Bookings' => $view->url('backend/booking'),
            'Bills' => $view->url('backend/bill'),
            'Users' => $view->url('backend/user'),
            'Export' => $view->url('backend/export'),
            'Config' => $view->url('backend/config'),
        );
    }

}