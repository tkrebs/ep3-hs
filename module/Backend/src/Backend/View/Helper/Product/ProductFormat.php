<?php

namespace Backend\View\Helper\Product;

use Product\Entity\Product;
use Zend\View\Helper\AbstractHelper;

class ProductFormat extends AbstractHelper
{

    public function __invoke(Product $product)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $product->need('pid'));

        $html .= sprintf('<td>%s</td>',
            $product->getMeta('name'));

        $html .= sprintf('<td class="symbolic-link-list"><a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a><a href="%s" class="symbolic symbolic-cross symbolic-link">%s</a></td>',
            $view->url('backend/product/edit', ['pid' => $product->need('pid')]), $view->t('Edit'),
            $view->url('backend/product/delete', ['pid' => $product->need('pid')]), $view->t('Delete'));

        $html .= '</tr>';

        return $html;
    }

}