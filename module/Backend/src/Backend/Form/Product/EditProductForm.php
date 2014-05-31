<?php

namespace Backend\Form\Product;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditProductForm extends Form
{

    public function init()
    {
        $this->setName('epf');

        $this->add(array(
            'name' => 'epf-name',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'epf-name',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Name',
                'notes' => 'The name/label of your product/service',
            ),
        ));

        $this->add(array(
            'name' => 'epf-description',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'epf-description',
                'style' => 'width: 420px; height: 120px;',
            ),
            'options' => array(
                'label' => 'Description',
                'notes' => 'Describe or advertise this product/service',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save product',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'epf-name' => array(
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
            'epf-description' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'The description should be at least %min% characters long',
                        ),
                    ),
                ),
            ),
        )));
    }

}