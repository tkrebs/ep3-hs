<?php

namespace Backend\Form\Bundle;

use Room\Manager\RoomManager;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBundleForm extends Form
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
            'name' => 'ebf-name',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-name',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Name',
                'notes' => 'This is both displayed to the client and to you',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-rid',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebf-rid',
                'style' => 'width: 264px;',
            ),
            'options' => array(
                'label' => 'Room',
                'notes' => 'Enable this bundle for a specific room or for all rooms',
                'value_options' => $this->rooms,
            ),
        ));

        $this->add(array(
            'name' => 'ebf-code',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-code',
                'style' => 'width: 250px;',
            ),
            'options' => array(
                'label' => 'Code',
                'notes' => 'Optionally protect this bundle with a (coupon) code',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-date-start',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-date-start',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'Start date',
                'notes' => 'Optionally specify a start date for this bundle',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-date-end',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-date-end',
                'class' => 'symbolic-datepicker datepicker',
                'style' => 'width: 150px;',
            ),
            'options' => array(
                'label' => 'End date',
                'notes' => 'Optionally specify an end date for this bundle',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-priority',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebf-priority',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Priority',
                'notes' => 'Optionally specify a priority for this bundle\'s position<br>(higher is better)',
            ),
        ));

        $this->add(array(
            'name' => 'ebf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save bundle',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ebf-name' => array(
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
            'ebf-code' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebf-date-start' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebf-date-end' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
            'ebf-priority' => array(
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