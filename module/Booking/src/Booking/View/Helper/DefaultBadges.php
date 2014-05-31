<?php

namespace Booking\View\Helper;

use Zend\View\Helper\AbstractHelper;

class DefaultBadges extends AbstractHelper
{

    public function __invoke($activeBadge = 1)
    {
        $badges = array(
            1 => 'Customize',
            2 => 'Register',
            3 => 'Confirm',
        );

        return array(
            'badges' => $badges,
            'misc' => array(
                'badgesActive' => $activeBadge,
            ),
        );
    }

}