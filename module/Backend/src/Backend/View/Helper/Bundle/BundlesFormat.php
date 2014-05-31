<?php

namespace Backend\View\Helper\Bundle;

use Zend\View\Helper\AbstractHelper;

class BundlesFormat extends AbstractHelper
{

    public function __invoke(array $bundles)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th style="width: 16px;">%s</th>',
            $view->t('ID'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Name'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Room'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Start date'));

        $html .= sprintf('<th>%s</th>',
            $view->t('End date'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Code'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/bundle/edit'), $view->t('New bundle'));

        foreach ($bundles as $bundle) {
            $html .= $view->backendBundleFormat($bundle);
        }

        $html .= '</table>';

        if (! $bundles) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No bundles found'));
        }

        return $html;
    }

}