<?php

return array(
    'router' => array(
        'routes' => array(
            'booking' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/booking',
                    'defaults' => array(
                        'controller' => 'Booking\Controller\Index',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'customize' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/customize',
                            'defaults' => array(
                                'action' => 'customize',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'pricing' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/pricing',
                                    'defaults' => array(
                                        'controller' => 'Booking\Controller\Support',
                                        'action' => 'pricing',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'action' => 'register',
                            ),
                        ),
                    ),
                    'confirm' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/confirm',
                            'defaults' => array(
                                'action' => 'confirm',
                            ),
                        ),
                    ),
                    'complete' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/complete',
                            'defaults' => array(
                                'action' => 'complete',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Booking\Controller\Index' => 'Booking\Controller\IndexController',
            'Booking\Controller\Support' => 'Booking\Controller\SupportController',
        ),
    ),

    'controller_plugins' => array(
        'invokables' => array(
            'RedirectToBookingRoute' => 'Booking\Controller\Plugin\RedirectToBookingRoute',
        ),

        'factories' => array(
            'DetermineBookingParams' => 'Booking\Controller\Plugin\DetermineBookingParamsFactory',
            'DetermineBookingSession' => 'Booking\Controller\Plugin\DetermineBookingSessionFactory',

            'RedirectToPayPal' => 'Booking\Controller\Plugin\PayPal\RedirectToPayPalFactory',

            'DetermineBookingBundleItemsCode' => 'Booking\Controller\Plugin\Bundle\Item\DetermineItemsCodeFactory',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Booking\Manager\BookingManager' => 'Booking\Manager\BookingManagerFactory',

            'Booking\Service\BookingService' => 'Booking\Service\BookingServiceFactory',
            'Booking\Service\PayPalService' => 'Booking\Service\PayPalServiceFactory',

            'Booking\Table\BookingTable' => 'Booking\Table\BookingTableFactory',
            'Booking\Table\BookingMetaTable' => 'Booking\Table\BookingMetaTableFactory',
            'Booking\Table\BookingExceptionTable' => 'Booking\Table\BookingExceptionTableFactory',

            /* Listeners */

            'Booking\Service\Listener\ConfirmationListener' => 'Booking\Service\Listener\ConfirmationListenerFactory',
            'Booking\Service\Listener\NotificationListener' => 'Booking\Service\Listener\NotificationListenerFactory',
        ),
    ),

    'form_elements' => array(
        'factories' => array(
            'Booking\Form\RegistrationForm' => 'Booking\Form\RegistrationFormFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'BookingBundleItemFormat' => 'Booking\View\Helper\Bundle\Item\BundleItemFormat',
            'BookingBundleItemsFormat' => 'Booking\View\Helper\Bundle\Item\BundleItemsFormat',

            'BookingBundleNightFormat' => 'Booking\View\Helper\Bundle\Night\BundleNightFormat',

            'BookingBundleReview' => 'Booking\View\Helper\Bundle\BundleReview',
            'BookingBundlesFormat' => 'Booking\View\Helper\Bundle\BundlesFormat',

            'BookingDatePreview' => 'Booking\View\Helper\BookingDatePreview',
            'BookingRules' => 'Booking\View\Helper\BookingRules',
            'BookingUrl' => 'Booking\View\Helper\BookingUrl',
            'BookingDefaultBadges' => 'Booking\View\Helper\DefaultBadges',
        ),

        'factories' => array(
            'BookingBundleFormat' => 'Booking\View\Helper\Bundle\BundleFormatFactory',

            'BookingPreview' => 'Booking\View\Helper\BookingPreviewFactory',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);