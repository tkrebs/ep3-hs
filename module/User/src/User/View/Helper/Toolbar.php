<?php

namespace User\View\Helper;

use User\Manager\UserSessionManager;
use Zend\View\Helper\AbstractHelper;

class Toolbar extends AbstractHelper
{

    protected $userSessionManager;
    protected $user;

    public function __construct(UserSessionManager $userSessionManager)
    {
        $this->userSessionManager = $userSessionManager;
        $this->user = $userSessionManager->getSessionUser();
    }

    public function __invoke()
    {
        $view = $this->getView();
        $html = '';

        if ($this->user) {
            $html .= sprintf('<span class="light-gray">%s <b>%s</b></span>',
                $view->t('Online as'), $this->user->need('alias'));

            $html .= $this->renderSeparator();

            if ($this->user->can('admin')) {

                $html .= sprintf('<a href="%s" class="unlined white symbolic symbolic-config">%s</a>',
                    $view->url('backend/dashboard'), $view->t('Administration'));

                $html .= $this->renderSeparator();

            }

            $html .= sprintf('<a href="%s" class="unlined white symbolic symbolic-user">%s</a>',
                    $view->url('user/dashboard'), $view->t('My profile'));

            $html .= $this->renderSeparator();

            $html .= sprintf('<a href="%s" class="unlined white symbolic symbolic-off">%s</a>',
                $view->url('user/logout'), $view->t('Logout'));
        } else {
            $html .= sprintf('<a href="%s" class="unlined white symbolic symbolic-door">%s</a>',
                $view->url('user/login'), $view->t('Login'));
        }

        return $html;
    }

    protected function renderSeparator()
    {
        return '<span class="light-gray" style="padding: 0px 8px;"></span>';
    }

}