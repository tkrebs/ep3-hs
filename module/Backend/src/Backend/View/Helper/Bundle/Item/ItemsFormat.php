<?php

namespace Backend\View\Helper\Bundle\Item;

use Bundle\Entity\Bundle;
use Zend\View\Helper\AbstractHelper;

class ItemsFormat extends AbstractHelper
{

    public function __invoke(array $bundleItems, Bundle $bundle)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th>%s</th>',
            $view->t('Product'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Units minimum'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Units maximum'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Price per unit'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Priority'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/bundle/edit/component/edit-item', ['bid' => $bundle->need('bid')]),
            $view->t('New product rule'));

        foreach ($bundleItems as $bundleItem) {
            $html .= $view->backendBundleItemFormat($bundleItem, $bundle);
        }

        $html .= '</table>';

        if (! $bundleItems) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No bundle product rules found'));
        }

        return $html;
    }

}