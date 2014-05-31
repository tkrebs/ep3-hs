<?php

return array(
    'router' => array(
        'routes' => array(
            'room' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/room/:rid',
                    'defaults' => array(
                        'controller' => 'Room\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Room\Controller\Index' => 'Room\Controller\IndexController',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Room\Manager\RoomManager' => 'Room\Manager\RoomManagerFactory',

            'Room\Table\RoomTable' => 'Room\Table\RoomTableFactory',
            'Room\Table\RoomMetaTable' => 'Room\Table\RoomMetaTableFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'RoomPreview' => 'Room\View\Helper\RoomPreview',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);