<?php

return array(
    'router' => array(
        'routes' => array(
            'calendar' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/calendar',
                    'defaults' => array(
                        'controller' => 'Calendar\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'snippet.js' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/snippet.js',
                            'defaults' => array(
                                'action' => 'snippetJs',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Calendar\Controller\Index' => 'Calendar\Controller\IndexController',
        ),
    ),

    'controller_plugins' => array(
        'invokables' => array(
            'DetermineCheck' => 'Calendar\Controller\Plugin\DetermineCheck',
            'DetermineCapacity' => 'Calendar\Controller\Plugin\DetermineCapacity',
            'DetermineDateArrival' => 'Calendar\Controller\Plugin\DetermineDateArrival',
            'DetermineDateDeparture' => 'Calendar\Controller\Plugin\DetermineDateDeparture',

            'ValidateDateArrival' => 'Calendar\Controller\Plugin\ValidateDateArrival',
            'ValidateDateDeparture' => 'Calendar\Controller\Plugin\ValidateDateDeparture',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'CalendarCapacityChoice' => 'Calendar\View\Helper\CapacityChoice',

            'CalendarRoomFormat' => 'Calendar\View\Helper\RoomFormat',
            'CalendarRoomsFormat' => 'Calendar\View\Helper\RoomsFormat',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);