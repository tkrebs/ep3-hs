<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Bundle\Manager\BundleManager' => 'Bundle\Manager\BundleManagerFactory',
            'Bundle\Manager\BundleItemManager' => 'Bundle\Manager\BundleItemManagerFactory',
            'Bundle\Manager\BundleNightManager' => 'Bundle\Manager\BundleNightManagerFactory',

            'Bundle\Table\BundleTable' => 'Bundle\Table\BundleTableFactory',
            'Bundle\Table\BundleMetaTable' => 'Bundle\Table\BundleMetaTableFactory',
            'Bundle\Table\BundleItemTable' => 'Bundle\Table\BundleItemTableFactory',
            'Bundle\Table\BundleNightTable' => 'Bundle\Table\BundleNightTableFactory',
            'Bundle\Table\BundleExceptionTable' => 'Bundle\Table\BundleExceptionTableFactory',
        ),
    ),
);