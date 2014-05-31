<?php

namespace Backend\Form\Bundle\Night;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBundleNightForm extends Form
{

    public function init()
    {
        $this->setName('ebnf');

        $this->add(array(
            'name' => 'ebnf-nights-min',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-nights-min',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Minimum amount',
                'notes' => 'The minimum amount necessary for this rule to apply',
                'postfix' => 'Night/s',
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-nights-max',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-nights-max',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '10',
            ),
            'options' => array(
                'label' => 'Maximum amount',
                'notes' => 'The maximum amount necessary for this rule to apply',
                'postfix' => 'Night/s',
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-price',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-price',
                'class' => 'right-text',
                'style' => 'width: 75px;',
            ),
            'options' => array(
                'label' => 'Price',
                'notes' => 'The price per night for this rule',
                'postfix' => '&euro;',
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-gross',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebnf-gross',
                'style' => 'width: 89px;',
            ),
            'options' => array(
                'label' => ' ',
                'value_options' => array(
                    '1' => 'including',
                    '0' => 'plus',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-rate',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-rate',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '19',
            ),
            'options' => array(
                'label' => 'VAT',
                'postfix' => '%',
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save rule',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ebnf-nights-min' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'message' => 'Please type a number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'ebnf-nights-min' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'message' => 'Please type a number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'ebnf-price' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'message' => 'Please type a number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'ebnf-rate' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'message' => 'Please type a number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
        )));
    }

}