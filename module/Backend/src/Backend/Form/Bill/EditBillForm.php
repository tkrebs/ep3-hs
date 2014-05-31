<?php

namespace Backend\Form\Bill;

use Bill\Entity\Bill;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBillForm extends Form
{

    public function init()
    {
        $this->setName('ebf');

        $this->add(array(
            'name' => 'ebf-bnr',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-bnr',
                'class' => 'right-text',
                'style' => 'width: 75px;',
            ),
            'options' => array(
                'label' => 'Number',
                'notes' => 'Optional bill number',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-status',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebf-status',
                'style' => 'width: 164px;',
            ),
            'options' => array(
                'label' => 'Status',
                'value_options' => Bill::$statusOptions,
            ),
        ));

        $this->add(array(
            'name' => 'ebf-booking',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-booking',
                'class' => 'right-text',
                'style' => 'width: 75px;',
            ),
            'options' => array(
                'label' => 'Booking',
                'notes' => 'ID of the booking this bill is related to',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-user',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-user',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'User',
                'notes' => 'Name of the user this bill is related to',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save bill',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ebf-bnr' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebf-booking' => array(
                'required' => false,
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
            'ebf-user' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/\([0-9]+\)$/',
                            'message' => 'Please choose a valid user from the autocompletion list',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
        )));
    }

}