<?php

return array(
    'router' => array(
        'routes' => array(
            'backend' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/backend',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'dashboard' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/dashboard',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Index',
                                'action' => 'dashboard',
                            ),
                        ),
                    ),
                    'room' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/room',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Room',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:rid]',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'constraints' => array(
                                    'rid' => '[0-9]+',
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:rid',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'constraints' => array(
                                    'rid' => '[0-9]+',
                                ),
                            ),
                            'promote-picture' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/promote-picture/:rid/:pid',
                                    'defaults' => array(
                                        'action' => 'promotePicture',
                                    ),
                                ),
                                'constraints' => array(
                                    'rid' => '[0-9]+',
                                    'pid' => '[0-9]+',
                                ),
                            ),
                            'delete-picture' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete-picture/:rid/:pid',
                                    'defaults' => array(
                                        'action' => 'deletePicture',
                                    ),
                                ),
                                'constraints' => array(
                                    'rid' => '[0-9]+',
                                    'pid' => '[0-9]+',
                                ),
                            ),
                        ),
                    ),
                    'product' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/product',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Product',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:pid]',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'constraints' => array(
                                    'pid' => '[0-9]+',
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:pid',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'constraints' => array(
                                    'pid' => '[0-9]+',
                                ),
                            ),
                            'interpret' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/interpret',
                                    'defaults' => array(
                                        'action' => 'interpret',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'bundle' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/bundle',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Bundle',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:bid]',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'constraints' => array(
                                    'bid' => '[0-9]+',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'component' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/component',
                                            'defaults' => array(
                                                'action' => 'component',
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' => array(
                                            'edit-item' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit-item[/:biid]',
                                                    'defaults' => array(
                                                        'action' => 'editItem',
                                                    ),
                                                    'constraints' => array(
                                                        'biid' => '[0-9]+',
                                                    ),
                                                ),
                                            ),
                                            'delete-item' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/delete-item/:biid',
                                                    'defaults' => array(
                                                        'action' => 'deleteItem',
                                                    ),
                                                ),
                                                'constraints' => array(
                                                    'biid' => '[0-9]+',
                                                ),
                                            ),
                                            'edit-night' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit-night[/:bnid]',
                                                    'defaults' => array(
                                                        'action' => 'editNight',
                                                    ),
                                                    'constraints' => array(
                                                        'bnid' => '[0-9]+',
                                                    ),
                                                ),
                                            ),
                                            'delete-night' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/delete-night/:bnid',
                                                    'defaults' => array(
                                                        'action' => 'deleteNight',
                                                    ),
                                                ),
                                                'constraints' => array(
                                                    'bnid' => '[0-9]+',
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:bid',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'constraints' => array(
                                    'bid' => '[0-9]+',
                                ),
                            ),
                        ),
                    ),
                    'booking' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/booking',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Booking',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:bid]',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'constraints' => array(
                                    'bid' => '[0-9]+',
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:bid',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'constraints' => array(
                                    'bid' => '[0-9]+',
                                ),
                            ),
                        ),
                    ),
                    'bill' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/bill',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Bill',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:bid]',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'constraints' => array(
                                    'bid' => '[0-9]+',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'component' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/component',
                                            'defaults' => array(
                                                'action' => 'component',
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' => array(
                                            'edit-item' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit-item[/:biid]',
                                                    'defaults' => array(
                                                        'action' => 'editItem',
                                                    ),
                                                    'constraints' => array(
                                                        'biid' => '[0-9]+',
                                                    ),
                                                ),
                                            ),
                                            'delete-item' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/delete-item/:biid',
                                                    'defaults' => array(
                                                        'action' => 'deleteItem',
                                                    ),
                                                ),
                                                'constraints' => array(
                                                    'biid' => '[0-9]+',
                                                ),
                                            ),
                                            'edit-night' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/edit-night[/:bnid]',
                                                    'defaults' => array(
                                                        'action' => 'editNight',
                                                    ),
                                                    'constraints' => array(
                                                        'bnid' => '[0-9]+',
                                                    ),
                                                ),
                                            ),
                                            'delete-night' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/delete-night/:bnid',
                                                    'defaults' => array(
                                                        'action' => 'deleteNight',
                                                    ),
                                                ),
                                                'constraints' => array(
                                                    'bnid' => '[0-9]+',
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:bid',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'constraints' => array(
                                    'bid' => '[0-9]+',
                                ),
                            ),
                        ),
                    ),
                    'user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/user',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\User',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:uid]',
                                    'defaults' => array(
                                        'action' => 'edit',
                                    ),
                                ),
                                'constraints' => array(
                                    'uid' => '[0-9]+',
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delete/:uid',
                                    'defaults' => array(
                                        'action' => 'delete',
                                    ),
                                ),
                                'constraints' => array(
                                    'uid' => '[0-9]+',
                                ),
                            ),
                            'interpret' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/interpret',
                                    'defaults' => array(
                                        'action' => 'interpret',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'export' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/export',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Export',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'config' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/config',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Config',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'info' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/info',
                                    'defaults' => array(
                                        'action' => 'info',
                                    ),
                                ),
                            ),
                            'help' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/help',
                                    'defaults' => array(
                                        'action' => 'help',
                                    ),
                                ),
                            ),
                            'rules' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/rules',
                                    'defaults' => array(
                                        'action' => 'rules',
                                    ),
                                ),
                            ),
                            'confirmation' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/confirmation',
                                    'defaults' => array(
                                        'action' => 'confirmation',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Backend\Controller\Index' => 'Backend\Controller\IndexController',
            'Backend\Controller\Room' => 'Backend\Controller\RoomController',
            'Backend\Controller\Product' => 'Backend\Controller\ProductController',
            'Backend\Controller\Bundle' => 'Backend\Controller\BundleController',
            'Backend\Controller\Booking' => 'Backend\Controller\BookingController',
            'Backend\Controller\Bill' => 'Backend\Controller\BillController',
            'Backend\Controller\User' => 'Backend\Controller\UserController',
            'Backend\Controller\Export' => 'Backend\Controller\ExportController',
            'Backend\Controller\Config' => 'Backend\Controller\ConfigController',
        ),
    ),

    'controller_plugins' => array(
        'invokables' => array(
            'BackendConfigEditDefinitions' => 'Backend\Controller\Plugin\Config\EditDefinitions',
        ),
    ),

    'form_elements' => array(
        'factories' => array(
            'Backend\Form\Booking\EditBookingForm' => 'Backend\Form\Booking\EditBookingFormFactory',

            'Backend\Form\Bundle\Item\EditBundleItemForm' => 'Backend\Form\Bundle\Item\EditBundleItemFormFactory',
            'Backend\Form\Bundle\EditBundleForm' => 'Backend\Form\Bundle\EditBundleFormFactory',

            'Backend\Form\User\EditUserForm' => 'Backend\Form\User\EditUserFormFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'BackendBillItemFormat' => 'Backend\View\Helper\Bill\Item\ItemFormat',
            'BackendBillItemsFormat' => 'Backend\View\Helper\Bill\Item\ItemsFormat',

            'BackendBillNightFormat' => 'Backend\View\Helper\Bill\Night\NightFormat',
            'BackendBillNightsFormat' => 'Backend\View\Helper\Bill\Night\NightsFormat',

            'BackendBillFormat' => 'Backend\View\Helper\Bill\BillFormat',
            'BackendBillsFormat' => 'Backend\View\Helper\Bill\BillsFormat',

            'BackendBookingFormat' => 'Backend\View\Helper\Booking\BookingFormat',
            'BackendBookingsFormat' => 'Backend\View\Helper\Booking\BookingsFormat',

            'BackendBookingCalendar' => 'Backend\View\Helper\Booking\Calendar',

            'BackendBundleItemFormat' => 'Backend\View\Helper\Bundle\Item\ItemFormat',
            'BackendBundleItemsFormat' => 'Backend\View\Helper\Bundle\Item\ItemsFormat',

            'BackendBundleNightFormat' => 'Backend\View\Helper\Bundle\Night\NightFormat',
            'BackendBundleNightsFormat' => 'Backend\View\Helper\Bundle\Night\NightsFormat',

            'BackendBundleFormat' => 'Backend\View\Helper\Bundle\BundleFormat',
            'BackendBundlesFormat' => 'Backend\View\Helper\Bundle\BundlesFormat',

            'BackendProductFormat' => 'Backend\View\Helper\Product\ProductFormat',
            'BackendProductsFormat' => 'Backend\View\Helper\Product\ProductsFormat',

            'BackendRoomFormat' => 'Backend\View\Helper\Room\RoomFormat',
            'BackendRoomsFormat' => 'Backend\View\Helper\Room\RoomsFormat',

            'BackendUserFormat' => 'Backend\View\Helper\User\UserFormat',
            'BackendUsersFormat' => 'Backend\View\Helper\User\UsersFormat',

            'BackendDefaultTabs' => 'Backend\View\Helper\DefaultTabs',
            'BackendInfo' => 'Backend\View\Helper\Info',
            'BackendInfoEdit' => 'Backend\View\Helper\InfoEdit',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),

        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);