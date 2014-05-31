<?php

namespace Backend\View\Helper;

use Zend\View\Helper\AbstractHelper;

class InfoEdit extends AbstractHelper
{

    public function __invoke($route, array $routeParams = array())
    {
        $view = $this->getView();
        $html = '';

        $html .= '<div class="separator-small separator-line"></div>';

        $html .= '<div class="centered-text">';

        $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link" target="_blank">%s</a>',
            $view->url($route, $routeParams),
            $view->t('Details / Edit'));

        $html .= '</div>';

        return $html;
    }

}