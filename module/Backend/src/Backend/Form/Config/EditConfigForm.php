<?php

namespace Backend\Form\Config;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditConfigForm extends Form
{

    protected $definitionId;
    protected $definitionElements;

    public function __construct($definitionId, array $definitionElements)
    {
        parent::__construct();

        $this->definitionId = $definitionId;
        $this->definitionElements = $definitionElements;

        $this->init();
    }

    public function init()
    {
        $prefix = $this->getPrefix();

        $this->setName($prefix);

        $inputFilters = array();

        foreach ($this->definitionElements as $key => $definition) {
            $id = sprintf('%s-%s',
                $prefix, str_replace('.', '-', $key));

            $type = $definition['type'];

            if (isset($definition['required'])) {
                $required = $definition['required'];
            } else {
                $required = true;
            }

            if ($type == 'Text') {
                $style = 'width: 320px;';

                $inputFilters = array_merge($inputFilters,
                    $this->getTextInputFilter($id, $required));
            } else {
                $style = null;
            }

            $label = $definition['label'];

            if (isset($definition['notes'])) {
                $notes = $definition['notes'];
            } else {
                $notes = null;
            }

            $this->add(array(
                'name' => $id,
                'type' => $type,
                'attributes' => array(
                    'id' => $id,
                    'style' => $style,
                ),
                'options' => array(
                    'label' => $label,
                    'notes' => $notes,
                ),
            ));
        }

        $this->add(array(
            'name' => sprintf('%s-submit', $prefix),
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save config',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter($inputFilters));
    }

    public function getPrefix()
    {
        return sprintf('ecf-%s', $this->definitionId);
    }

    protected function getTextInputFilter($id, $required)
    {
        return array(
            $id => array(
                'required' => $required,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'This input cannot be empty',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'This input should be at least %min% characters long',
                        ),
                    ),
                ),
            ),
        );
    }

}