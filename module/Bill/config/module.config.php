<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Bill\Manager\BillManager' => 'Bill\Manager\BillManagerFactory',
            'Bill\Manager\BillItemManager' => 'Bill\Manager\BillItemManagerFactory',
            'Bill\Manager\BillNightManager' => 'Bill\Manager\BillNightManagerFactory',

            'Bill\Table\BillTable' => 'Bill\Table\BillTableFactory',
            'Bill\Table\BillMetaTable' => 'Bill\Table\BillMetaTableFactory',
            'Bill\Table\BillItemTable' => 'Bill\Table\BillItemTableFactory',
            'Bill\Table\BillNightTable' => 'Bill\Table\BillNightTableFactory',
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'BillPreview' => 'Bill\View\Helper\BillPreviewFactory',
        ),
    ),
);