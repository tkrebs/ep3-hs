<?php

namespace Backend\Controller\Plugin\Config;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class EditDefinitions extends AbstractPlugin
{

    public function __invoke()
    {
        return array(
            'client' => array(
                'label' => 'About your company',
                'elements' => array(
                    'client.name.full' => array(
                        'type' => 'Text',
                        'label' => 'Name',
                        'notes' => 'Name of your company',
                    ),
                    'client.name.short' => array(
                        'type' => 'Text',
                        'label' => 'Short name',
                        'notes' => 'Short name or abbreviation of your company',
                    ),
                    'client.contact.email' => array(
                        'type' => 'Text',
                        'label' => 'Email address',
                        'notes' => 'Email address of the administrating person',
                    ),
                    'client.contact.phone' => array(
                        'type' => 'Text',
                        'label' => 'Phone',
                        'notes' => 'Phone number to display to your guests',
                    ),
                    'client.website' => array(
                        'type' => 'Text',
                        'label' => 'Website',
                        'notes' => 'Website of your company',
                    ),
                    'client.website.contact' => array(
                        'type' => 'Text',
                        'label' => 'Website contact',
                        'notes' => 'Contact page of your company website',
                    ),
                    'client.website.imprint' => array(
                        'type' => 'Text',
                        'label' => 'Website imprint',
                        'notes' => 'Imprint page of your company website',
                    ),
                    'client.logo.website' => array(
                        'type' => 'Checkbox',
                        'label' => 'Logo links to website',
                        'notes' => 'If enabled, the logo links to your website',
                        'i18n' => false,
                    ),
                ),
            ),

            'service' => array(
                'label' => 'About your system',
                'elements' => array(
                    'service.name.full' => array(
                        'type' => 'Text',
                        'label' => 'Name',
                        'notes' => 'Name of your system',
                    ),
                    'service.name.short' => array(
                        'type' => 'Text',
                        'label' => 'Short name',
                        'notes' => 'Short name or abbreviation of your system',
                    ),
                    'service.maintenance' => array(
                        'type' => 'Checkbox',
                        'label' => 'Enable maintenance mode',
                        'notes' => 'If enabled, the system is temporarily disabled',
                        'i18n' => false,
                    ),
                    'service.maintenance.message' => array(
                        'type' => 'Text',
                        'label' => ' ',
                        'notes' => 'Optional message to display in maintenance mode',
                        'i18n' => false,
                        'required' => false,
                    ),
                    'service.login.footer' => array(
                        'type' => 'Checkbox',
                        'label' => 'Display login in the footer',
                        'notes' => 'If enabled, the login will be displayed in the footer',
                        'i18n' => false,
                    ),
                    'service.notify.booking' => array(
                        'type' => 'Checkbox',
                        'label' => 'Notify per email about new bookings',
                        'notes' => 'If enabled, emails will be sent to the address above',
                        'i18n' => false,
                    ),
                ),
            ),

            'subject' => array(
                'label' => 'About your subject',
                'elements' => array(
                    'subject.type' => array(
                        'type' => 'Text',
                        'label' => 'Name',
                        'notes' => 'Name of the subject you run (including lowercased determiner)',
                    ),
                    'service.meta.description' => array(
                        'type' => 'Text',
                        'label' => 'Description',
                        'notes' => 'Optional description of your subject',
                        'required' => false,
                    ),
                    'service.meta.keywords' => array(
                        'type' => 'Text',
                        'label' => 'Keywords',
                        'notes' => 'Optional keywords related to your subject',
                        'required' => false,
                    ),
                ),
            ),

            'payment' => array(
                'label' => 'Payment methods',
                'elements' => array(
                    'payment.paypal' => array(
                        'type' => 'Checkbox',
                        'label' => 'Payment via PayPal',

                        // TODO: Make PayPal payment a pluggable module
                        // 'notes' => 'If enabled, clients can pay via PayPal or Credit Card',
                        'notes' => 'The PayPal payment module is still in development!<br>This option will <b>have no effect</b> yet!',

                        'i18n' => false,
                    ),
                    'payment.paypal.endpoint' => array(
                        'type' => 'Text',
                        'label' => 'PayPal URL',
                        'notes' => 'PayPal payment endpoint for communication',
                        'i18n' => false,
                        'required' => false,
                    ),
                    'payment.paypal.client.id' => array(
                        'type' => 'Text',
                        'label' => 'PayPal User',
                        'notes' => 'PayPal API user name',
                        'i18n' => false,
                        'required' => false,
                    ),
                    'payment.paypal.client.secret' => array(
                        'type' => 'Text',
                        'label' => 'PayPal Password',
                        'notes' => 'PayPal API password',
                        'i18n' => false,
                        'required' => false,
                    ),
                ),
            ),
        );
    }

}