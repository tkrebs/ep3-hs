<?php

namespace User\View\Helper;

use User\Entity\User;
use Zend\View\Helper\AbstractHelper;

class UserPreview extends AbstractHelper
{

    public function __invoke(User $user, array $additions = array())
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="compact-table">';

        /* Display name */

        if ($user->getMeta('gender') && $user->getMeta('firstname') && $user->getMeta('lastname')) {
            $name = sprintf('%s %s %s',
                $view->t($user->getGender()),
                $user->getMeta('firstname'),
                $user->getMeta('lastname'));
        } else {
            $name = $user->need('alias');
        }

        $html .= sprintf('<tr><td class="gray" style="width: 120px;">%s:</td><td>%s</td></tr>',
            $view->t('Name'), $name);

        /* Display address */

        if ($user->getMeta('street') || $user->getMeta('zip') || $user->getMeta('city')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td><p>%s<br>%s %s</p></td></tr>',
                $view->t('Address'),
                $user->getMeta('street'),
                $user->getMeta('zip'),
                $user->getMeta('city'));
        }

        /* Display email address */

        $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
            $view->t('Email address'),
            $user->need('email'));

        /* Display phone number */

        if ($user->getMeta('phone')) {
            $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                $view->t('Phone number'),
                $user->getMeta('phone'));
        }

        /* Display additional information */

        foreach ($additions as $additionLabel => $additionValue) {
            if (is_string($additionLabel) && is_string($additionValue)) {
                $html .= sprintf('<tr><td class="gray">%s:</td><td>%s</td></tr>',
                    $view->t($additionLabel),
                    $additionValue);
            }
        }

        $html .= '</table>';

        return $html;
    }

}