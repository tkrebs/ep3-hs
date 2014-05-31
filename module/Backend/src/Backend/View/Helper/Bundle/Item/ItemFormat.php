<?php

namespace Backend\View\Helper\Bundle\Item;

use Bundle\Entity\Bundle;
use Bundle\Entity\BundleItem;
use Zend\View\Helper\AbstractHelper;

class ItemFormat extends AbstractHelper
{

    public function __invoke(BundleItem $bundleItem, Bundle $bundle)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        if ($bundleItem->getExtra('product')) {
            $product = $bundleItem->getExtra('product')->getMeta('name');
        } else {
            $product = $bundleItem->get('pid');
        }

        $html .= sprintf('<td>%s</td>',
            $product);

        switch ($bundleItem->need('due')) {
            case 'per_night':
                $html .= sprintf('<td colspan="2">%s</td>',
                    $view->t('Per Night'));

                break;
            case 'per_item':
            default:
                $html .= sprintf('<td>%s</td>',
                    $bundleItem->need('amount_min'));

                $html .= sprintf('<td>%s</td>',
                    $bundleItem->need('amount_max'));

                break;
        }

        $html .= sprintf('<td>%s</td>',
            $view->priceFormat($bundleItem->need('price'), $bundleItem->need('rate'), $bundleItem->need('gross')));

        $html .= sprintf('<td>%s</td>',
            $bundleItem->need('priority'));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/bundle/edit/component/edit-item', ['bid' => $bundle->need('bid'), 'biid' => $bundleItem->need('biid')]), $view->t('Edit'),
            $view->url('backend/bundle/edit/component/delete-item', ['bid' => $bundle->need('bid'), 'biid' => $bundleItem->need('biid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}