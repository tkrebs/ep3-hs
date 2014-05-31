<?php

namespace Backend\Form\Config;

use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditRulesForm extends Form
{

    public function init()
    {
        $this->setName('erf');

        $this->add(array(
            'name' => 'erf-document-file',
            'type' => 'File',
            'attributes' => array(
                'id' => 'erf-document-file',
                'accept' => '.pdf',
            ),
            'options' => array(
                'label' => 'Document',
                'notes' => 'Document, like general business terms, as PDF',
            ),
        ));

        $this->add(array(
            'name' => 'erf-document-name',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'erf-document-name',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Document name',
                'notes' => 'Name of the document uploaded above (e.g. Terms)',
            ),
        ));

        $this->add(array(
            'name' => 'erf-text',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'erf-text',
                'style' => 'width: 420px; height: 120px;',
            ),
            'options' => array(
                'label' => 'Text',
                'notes' => 'You can also directly type your rules and conditions here',
            ),
        ));

        $this->add(array(
            'name' => 'erf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save rules',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'erf-document-file' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'File/MimeType',
                        'options' => array(
                            'mimeType' => 'application/pdf',
                            'message' => 'The selected file must be a PDF document file',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'File/Size',
                        'options' => array(
                            'min' => '2kB',
                            'max' => '4MB',
                            'message' => 'The selected document\'s file size must be between 2 kB and 4 MB',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'erf-document-name' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'The name should be at least %min% characters long',
                        ),
                    ),
                ),
            ),
            'erf-text' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'The text should be at least %min% characters long',
                        ),
                    ),
                ),
            ),
        )));
    }

}