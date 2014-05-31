<?php

namespace Booking\Form;

use Base\Manager\OptionManager;
use User\Entity\User;
use User\Service\CountryService;
use Zend\Crypt\BlockCipher;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class RegistrationForm extends Form
{

    protected $optionManager;
    protected $countryService;
    protected $locale;

    public function __construct(OptionManager $optionManager, CountryService $countryService, $locale)
    {
        parent::__construct();

        $this->optionManager = $optionManager;
        $this->countryService = $countryService;
        $this->locale = $locale;
    }

    public function init()
    {
        $this->setName('rf');

        $this->add(array(
            'name' => 'rf-gender',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'rf-gender',
            ),
            'options' => array(
                'label' => 'Salutation',
                'value_options' => User::$genderOptions,
            ),
        ));

        $this->add(array(
            'name' => 'rf-firstname',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-firstname',
                'style' => 'width: 116px;',
            ),
            'options' => array(
                'label' => 'First & Last name',
            ),
        ));

        $this->add(array(
            'name' => 'rf-lastname',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-lastname',
                'style' => 'width: 116px;',
            ),
            'options' => array(
                'label' => 'Last name',
            ),
        ));

        $this->add(array(
            'name' => 'rf-street',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-street',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Street & Number',
            ),
        ));

        $this->add(array(
            'name' => 'rf-zip',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-zip',
                'style' => 'width: 116px;',
            ),
            'options' => array(
                'label' => 'Postal code & City',
            ),
        ));

        $this->add(array(
            'name' => 'rf-city',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-city',
                'style' => 'width: 116px;',
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));

        $this->add(array(
            'name' => 'rf-country',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'rf-country',
                'value' => strtoupper(substr($this->locale, 3, 2)),
                'style' => 'width: 264px;',
            ),
            'options' => array(
                'label' => 'Country',
                'value_options' => $this->countryService->getNames(),
            ),
        ));

        $this->add(array(
            'name' => 'rf-email',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-email',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Email address',
            ),
        ));

        $this->add(array(
            'name' => 'rf-phone',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'rf-phone',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Phone number',
            ),
        ));

        $this->add(array(
            'name' => 'rf-notes',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'rf-notes',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Notes',
                'notes' => 'This is optional',
            ),
        ));

        // TODO: Make PayPal payment a pluggable module
        // if ($this->optionManager->get('payment.paypal', 'false') == 'true') {
        if (false) {
            $paymentMethods = array(
                'paypal' => 'PayPal / Credit Card',
                'invoice' => 'Invoice',
            );
        } else {
            $paymentMethods = array(
                'invoice' => 'Invoice',
            );
        }

        $this->add(array(
            'name' => 'rf-payment',
            'type' => 'Radio',
            'attributes' => array(
                'id' => 'rf-payment',
            ),
            'options' => array(
                'label' => 'Pay via',
                'value_options' => $paymentMethods,
            ),
        ));

        /* Add AES encrypted timestamp for security */

        $blockCipher = BlockCipher::factory('mcrypt', array('algo' => 'aes'));
        $blockCipher->setKey('A balrog, a demon of the ancient world. Its foe is beyond any of you, RUN!');

        $this->add(array(
            'name' => 'rf-csrf',
            'type' => 'Hidden',
            'attributes' => array(
                'value' => $blockCipher->encrypt(time()),
            ),
        ));

        $this->add(array(
            'name' => 'rf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Continue to confirmation',
                'class' => 'default-button',
                'style' => 'width: 250px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'rf-firstname' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your firstname here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'Your firstname is somewhat short ...',
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^([ \&\'\(\)\+\,\-\.0-9\x{00c0}-\x{01ff}a-zA-Z])+$/u',
                            'message' => 'Your firstname contains invalid characters - sorry',
                        ),
                    ),
                ),
            ),
            'rf-lastname' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your lastname here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'Your lastname is somewhat short ...',
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^([ \'\+\-\x{00c0}-\x{01ff}a-zA-Z])+$/u',
                            'message' => 'Your lastname contains invalid characters - sorry',
                        ),
                    ),
                ),
            ),
            'rf-street' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'Callback', 'options' => array('callback' => function($name) { return ucfirst($name); })),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your street name here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'This street name is somewhat short ...',
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^([ \°\&\.\'\-\x{00c0}-\x{01ff}a-zA-Z0-9])+$/u',
                            'message' => 'This street name contains invalid characters - sorry',
                        ),
                    ),
                ),
            ),
            'rf-zip' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your postal code here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'rf-city' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'Callback', 'options' => array('callback' => function($name) { return ucfirst($name); })),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your city here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'This city name is somewhat short ...',
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^([ \&\'\(\)\-\x{00c0}-\x{01ff}a-zA-Z0-9])+$/u',
                            'message' => 'This city name contains invalid characters - sorry',
                        ),
                    ),
                ),
            ),
            'rf-email' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your email address here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'useMxCheck' => true,
                            'message' => 'Please type your correct email address here',
                            'messages' => array(
                                'emailAddressInvalidMxRecord' => 'We could not verify your email provider',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function($value) {
                                $blacklist = getcwd() . '/data/res/blacklist-emails.txt';

                                if (is_readable($blacklist)) {
                                    $blacklistContent = file_get_contents($blacklist);
                                    $blacklistDomains = explode("\r\n", $blacklistContent);

                                    foreach ($blacklistDomains as $blacklistDomain) {
                                        $blacklistPattern = str_replace('.', '\.', $blacklistDomain);

                                        if (preg_match('/' . $blacklistPattern . '$/', $value)) {
                                            return false;
                                        }
                                    }
                                }

                                return true;
                            },
                            'message' => 'Trash mail addresses are currently blocked - sorry',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'rf-phone' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type your phone number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'This phone number is somewhat short ...',
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^([ \+\/\(\)\-0-9])+$/u',
                            'message' => 'This phone number contains invalid characters - sorry',
                        ),
                    ),
                ),
            ),
            'rf-csrf' => array(
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please register about our website only',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function($value) use ($blockCipher) {
                                $time = $blockCipher->decrypt($value);

                                if (! is_numeric($time)) {
                                    return false;
                                }

                                /* Allow form submission after five seconds */

                                if (time() - $time < 5) {
                                    return false;
                                } else {
                                    return true;
                                }
                            },
                            'message' => 'You were too quick for our system! Please wait some seconds and try again. Thank you!',
                        ),
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function($value) use ($blockCipher) {
                                $time = $blockCipher->decrypt($value);

                                if (! is_numeric($time)) {
                                    return false;
                                }

                                /* Allow form submission within one day */

                                if (time() - $time > 60 * 60 * 24) {
                                    return false;
                                } else {
                                    return true;
                                }
                            },
                            'message' => 'You have reached the time limit of one day for your booking. We are very sorry.',
                        ),
                    ),
                ),
            ),
        )));
    }

}