<?php

namespace Backend\View\Helper\User;

use Zend\View\Helper\AbstractHelper;

class UsersFormat extends AbstractHelper
{

    public function __invoke(array $users)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th style="width: 16px;">%s</th>',
            $view->t('ID'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Name'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Status'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/user/edit'), $view->t('New user'));

        foreach ($users as $user) {
            $html .= $view->backendUserFormat($user);
        }

        $html .= '</table>';

        if (! $users) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No users found'));
        }

        return $html;
    }

}