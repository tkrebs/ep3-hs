<?php

namespace User\View\Helper;

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
            'Overview' => $view->url('user/dashboard'),
            'My bookings' => $view->url('user/bookings'),
            'My account' => $view->url('user/settings'),
        );
    }

}