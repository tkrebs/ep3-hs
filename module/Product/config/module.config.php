<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Product\Manager\ProductManager' => 'Product\Manager\ProductManagerFactory',

            'Product\Table\ProductTable' => 'Product\Table\ProductTableFactory',
            'Product\Table\ProductMetaTable' => 'Product\Table\ProductMetaTableFactory',
        ),
    ),
);