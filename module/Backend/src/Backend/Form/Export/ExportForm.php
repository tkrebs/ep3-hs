<?php

namespace Backend\Form\Export;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ExportForm extends Form
{

    public function init()
    {
        $this->setName('ef');

        $this->add(array(
            'name' => 'ef-date-start',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ef-date-start',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'Start date',
            ),
        ));

        $this->add(array(
            'name' => 'ef-date-end',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ef-date-end',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'End date',
            ),
        ));

        $this->add(array(
            'name' => 'ef-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Export',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ef-date-start' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ef-date-end' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
        )));
    }

}