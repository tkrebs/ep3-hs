<?php

namespace Backend\Form\Bill\Item;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBillItemForm extends Form
{

    public function init()
    {
        $this->setName('ebif');

        $this->add(array(
            'name' => 'ebif-pid-name',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-pid-name',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Product',
            ),
        ));

        $this->add(array(
            'name' => 'ebif-amount',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-amount',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Amount',
                'postfix' => 'Unit/s',
            ),
        ));

        $this->add(array(
            'name' => 'ebif-price',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-price',
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
            'name' => 'ebif-gross',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebif-gross',
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
            'name' => 'ebif-rate',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-rate',
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
            'name' => 'ebif-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save product bill',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ebif-pid-name' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type a name here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'The name should be at least %min% characters long',
                        ),
                    ),
                ),
            ),
            'ebif-amount' => array(
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
            'ebif-price' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'NumberParse'),
                ),
                'validators' => array(
                    array(
                        'name' => 'IsFloat',
                        'options' => array(
                            'message' => 'Please type a number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'ebif-rate' => array(
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
