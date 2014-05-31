<?php

namespace Backend\View\Helper\Bundle\Night;

use Bundle\Entity\Bundle;
use Zend\View\Helper\AbstractHelper;

class NightsFormat extends AbstractHelper
{

    public function __invoke(array $bundleNights, Bundle $bundle)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th>%s</th>',
            $view->t('Nights minimum'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Nights maximum'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Price per night'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/bundle/edit/component/edit-night', ['bid' => $bundle->need('bid')]),
            $view->t('New night rule'));

        foreach ($bundleNights as $bundleNight) {
            $html .= $view->backendBundleNightFormat($bundleNight, $bundle);
        }

        $html .= '</table>';

        if (! $bundleNights) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No bundle night rules found'));
        }

        return $html;
    }

}