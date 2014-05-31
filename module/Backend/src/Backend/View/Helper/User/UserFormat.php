<?php

namespace Backend\View\Helper\User;

use User\Entity\User;
use Zend\View\Helper\AbstractHelper;

class UserFormat extends AbstractHelper
{

    public function __invoke(User $user)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $user->need('uid'));

        $html .= sprintf('<td>%s</td>',
            $user->need('alias'));

        $html .= sprintf('<td>%s</td>',
            $view->t($user->getStatus()));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/user/edit', ['uid' => $user->need('uid')]), $view->t('Edit'),
            $view->url('backend/user/delete', ['uid' => $user->need('uid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}