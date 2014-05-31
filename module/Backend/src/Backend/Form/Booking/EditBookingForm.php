<?php

namespace Backend\Form\Booking;

use Booking\Entity\Booking;
use Room\Manager\RoomManager;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBookingForm extends Form
{

    protected $roomManager;
    protected $rooms = array();

    public function __construct(RoomManager $roomManager)
    {
        parent::__construct();

        $this->roomManager = $roomManager;

        $this->rooms[0] = 'All rooms';

        foreach ($roomManager->getAll('rnr ASC') as $rid => $room) {
            $this->rooms[$rid] = sprintf('%s - %s',
                $room->get('rnr', $rid), $room->getMeta('name'));
        }
    }

    public function init()
    {
        $this->setName('ebf');

        $this->add(array(
            'name' => 'ebf-rid',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebf-rid',
                'style' => 'width: 164px;',
            ),
            'options' => array(
                'label' => 'Room',
                'value_options' => $this->rooms,
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
                'notes' => 'Name of the user this booking is related to',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-date-arrival',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-date-arrival',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'Arrival',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-date-departure',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-date-departure',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'Departure',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-quantity',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-quantity',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Persons',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-notes',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'ebf-notes',
                'style' => 'width: 320px; height: 48px;',
            ),
            'options' => array(
                'label' => 'Notes',
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
                'value_options' => Booking::$statusOptions,
            ),
        ));

        $this->add(array(
            'name' => 'ebf-edit-bill',
            'type' => 'Checkbox',
            'attributes' => array(
                'id' => 'ebf-edit-bill',
            ),
            'options' => array(
                'label' => 'Edit bill components afterwards',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save booking',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
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
            'ebf-date-arrival' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebf-date-departure' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebf-quantity' => array(
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
            'ebf-notes' => array(
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