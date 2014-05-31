<?php

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'User\Controller\Session',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'User\Controller\Session',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'password' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/password',
                            'defaults' => array(
                                'controller' => 'User\Controller\Account',
                                'action' => 'password',
                            ),
                        ),
                    ),
                    'password-reset' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/password-reset',
                            'defaults' => array(
                                'controller' => 'User\Controller\Account',
                                'action' => 'passwordReset',
                            ),
                        ),
                    ),
                    'registration' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/registration',
                            'defaults' => array(
                                'controller' => 'User\Controller\Account',
                                'action' => 'registration',
                            ),
                        ),
                    ),
                    'dashboard' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/dashboard',
                            'defaults' => array(
                                'controller' => 'User\Controller\Account',
                                'action' => 'dashboard',
                            ),
                        ),
                    ),
                    'bookings' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/bookings',
                            'defaults' => array(
                                'controller' => 'User\Controller\Account',
                                'action' => 'bookings',
                            ),
                        ),
                    ),
                    'settings' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/settings',
                            'defaults' => array(
                                'controller' => 'User\Controller\Account',
                                'action' => 'settings',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'User\Controller\Account' => 'User\Controller\AccountController',
            'User\Controller\Session' => 'User\Controller\SessionController',
        ),
    ),

    'controller_plugins' => array(
        'factories' => array(
            'Authorize' => 'User\Controller\Plugin\AuthorizeFactory',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'User\Manager\UserManager' => 'User\Manager\UserManagerFactory',
            'User\Manager\UserSessionManager' => 'User\Manager\UserSessionManagerFactory',

            'User\Table\UserTable' => 'User\Table\UserTableFactory',
            'User\Table\UserMetaTable' => 'User\Table\UserMetaTableFactory',

            'User\Service\MailService' => 'User\Service\MailServiceFactory',

            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
            'Zend\Session\SessionManager' => 'Zend\Session\Service\SessionManagerFactory',
        ),

        'invokables' => array(
            'User\Service\CountryService' => 'User\Service\CountryService',
        ),
    ),

    'form_elements' => array(
        'factories' => array(
            'User\Form\EditEmailForm' => 'User\Form\EditEmailFormFactory',
            'User\Form\RegistrationForm' => 'User\Form\RegistrationFormFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'UserDefaultTabs' => 'User\View\Helper\DefaultTabs',
            'UserPreview' => 'User\View\Helper\UserPreview',
        ),

        'factories' => array(
            'UserToolbar' => 'User\View\Helper\ToolbarFactory',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);