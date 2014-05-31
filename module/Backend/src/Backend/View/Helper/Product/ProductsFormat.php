<?php

namespace Backend\View\Helper\Product;

use Zend\View\Helper\AbstractHelper;

class ProductsFormat extends AbstractHelper
{

    public function __invoke(array $products)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table full-width">';

        $html .= sprintf('<th style="width: 16px;">%s</th>',
            $view->t('ID'));

        $html .= sprintf('<th>%s</th>',
            $view->t('Name'));

        $html .= sprintf('<th style="width: 196px;"><a href="%s" class="symbolic symbolic-plus symbolic-link">%s</a></th>',
            $view->url('backend/product/edit'), $view->t('New product'));

        foreach ($products as $product) {
            $html .= $view->backendProductFormat($product);
        }

        $html .= '</table>';

        if (! $products) {
            $html .= sprintf('<div class="sandbox centered-text gray">%s</div>',
                $view->t('No products found'));
        }

        return $html;
    }

}