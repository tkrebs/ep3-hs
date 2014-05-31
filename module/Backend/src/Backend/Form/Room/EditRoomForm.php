<?php

namespace Backend\Form\Room;

use Room\Entity\Room;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditRoomForm extends Form
{

    public function init()
    {
        $this->setName('epf');

        $this->add(array(
            'name' => 'erf-rnr',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'erf-rnr',
                'class' => 'right-text',
                'style' => 'width: 75px;',
            ),
            'options' => array(
                'label' => 'Number',
                'notes' => 'Room number or identifier',
            ),
        ));

        $this->add(array(
            'name' => 'erf-capacity',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'erf-capacity',
                'class' => 'right-text',
                'style' => 'width: 75px;',
            ),
            'options' => array(
                'label' => 'Persons',
                'notes' => 'Number of persons',
            ),
        ));

        $this->add(array(
            'name' => 'erf-status',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'erf-status',
                'style' => 'width: 164px;',
            ),
            'options' => array(
                'label' => 'Status',
                'notes' => 'Status of this room',
                'value_options' => Room::$statusOptions,
            ),
        ));

        $this->add(array(
            'name' => 'erf-name',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'erf-name',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Name',
                'notes' => 'Optional name of this room',
            ),
        ));

        $this->add(array(
            'name' => 'erf-info',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'erf-info',
                'style' => 'width: 420px; height: 32px;',
            ),
            'options' => array(
                'label' => 'Info',
                'notes' => 'Optional info line for this room (short)',
            ),
        ));

        $this->add(array(
            'name' => 'erf-description',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'erf-description',
                'class' => 'wysiwyg-editor',
                'style' => 'width: 420px; height: 256px;',
            ),
            'options' => array(
                'label' => 'Description',
                'notes' => 'Optional description for this room (long)',
            ),
        ));

        $this->add(array(
            'name' => 'erf-picture',
            'type' => 'File',
            'attributes' => array(
                'id' => 'erf-picture',
                'accept' => 'image/*',
            ),
            'options' => array(
                'label' => 'Picture',
                'notes' => 'Optionally upload a picture',
            ),
        ));

        $this->add(array(
            'name' => 'erf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save room',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'erf-rnr' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Please type a number here',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'erf-capacity' => array(
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
            'erf-name' => array(
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
            'erf-info' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'message' => 'The info line should be at least %min% characters long',
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => 128,
                            'message' => 'The info line should not be longer than %max% characters',
                        ),
                    ),
                ),
            ),
            'erf-description' => array(
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
            'erf-picture' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'File/IsImage',
                        'options' => array(
                            'message' => 'The selected file must be an image file',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'File/Size',
                        'options' => array(
                            'min' => '2kB',
                            'max' => '4MB',
                            'message' => 'The selected image file size must be between 2 kB and 4 MB',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'File/ImageSize',
                        'options' => array(
                            'minWidth' => 100,
                            'minHeight' => 100,
                            'maxWidth' => 4096,
                            'maxHeight' => 4096,
                            'message' => 'The selected image size must be between 100x100 and 4096x4096 pixels',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
        )));
    }

}