<?php

namespace Backend\View\Helper\Bundle\Night;

use Bundle\Entity\Bundle;
use Bundle\Entity\BundleNight;
use Zend\View\Helper\AbstractHelper;

class NightFormat extends AbstractHelper
{

    public function __invoke(BundleNight $bundleNight, Bundle $bundle)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $bundleNight->need('nights_min'));

        $html .= sprintf('<td>%s</td>',
            $bundleNight->need('nights_max'));

        $html .= sprintf('<td>%s</td>',
            $view->priceFormat($bundleNight->need('price'), $bundleNight->need('rate'), $bundleNight->need('gross')));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/bundle/edit/component/edit-night', ['bid' => $bundle->need('bid'), 'bnid' => $bundleNight->need('bnid')]), $view->t('Edit'),
            $view->url('backend/bundle/edit/component/delete-night', ['bid' => $bundle->need('bid'), 'bnid' => $bundleNight->need('bnid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}