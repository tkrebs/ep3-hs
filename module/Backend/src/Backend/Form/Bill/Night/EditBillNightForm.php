<?php

namespace Backend\Form\Bill\Night;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBillNightForm extends Form
{

    public function init()
    {
        $this->setName('ebnf');

        $this->add(array(
            'name' => 'ebnf-date-arrival',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-date-arrival',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'Arrival',
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-date-departure',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-date-departure',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'Departure',
            ),
        ));

        $this->add(array(
            'name' => 'ebnf-quantity',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebnf-quantity',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Persons',
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
                'notes' => 'Total price',
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
                'value' => 'Save night bill',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ebnf-date-arrival' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebnf-date-departure' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebnf-quantity' => array(
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