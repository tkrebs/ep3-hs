<?php

namespace Backend\Form\Bundle\Item;

use Bundle\Entity\BundleItem;
use Product\Manager\ProductManager;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditBundleItemForm extends Form
{

    protected $products = array();

    public function __construct(ProductManager $productManager)
    {
        parent::__construct();

        $products = $productManager->getAll('pid ASC');

        foreach ($products as $pid => $product) {
            $this->products[$pid] = $product->getMeta('name');
        }
    }

    public function init()
    {
        $this->setName('ebif');

        $this->add(array(
            'name' => 'ebif-pid',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebif-pid',
                'style' => 'width: 264px;',
            ),
            'options' => array(
                'label' => 'Product',
                'notes' => 'The product you want to include in this bundle',
                'value_options' => array_merge(array(
                    '0' => 'Choose product',
                ), $this->products),
            ),
        ));

        $this->add(array(
            'name' => 'ebif-due',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'ebif-due',
                'style' => 'width: 264px;',
            ),
            'options' => array(
                'label' => 'Due',
                'notes' => 'How to calculate the total price',
                'value_options' => BundleItem::$dueOptions,
            ),
        ));

        $this->add(array(
            'name' => 'ebif-amount-required',
            'type' => 'Checkbox',
            'attributes' => array(
                'id' => 'ebif-amount-required',
            ),
            'options' => array(
                'label' => 'Required',
                'notes' => 'If enabled, this product is obligatory',
            ),
        ));

        $this->add(array(
            'name' => 'ebif-amount-min',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-amount-min',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Minimum amount',
                'notes' => 'The minimum amount necessary for this rule to apply',
                'postfix' => 'Unit/s',
            ),
        ));

        $this->add(array(
            'name' => 'ebif-amount-max',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-amount-max',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '10',
            ),
            'options' => array(
                'label' => 'Maximum amount',
                'notes' => 'The maximum amount necessary for this rule to apply',
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
                'notes' => 'The price per unit/night for this rule',
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
            'name' => 'ebif-priority',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'ebif-priority',
                'class' => 'right-text',
                'style' => 'width: 75px;',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Priority',
                'notes' => 'Optionally specify a priority for this item\'s position<br>(higher is better)',
            ),
        ));

        $this->add(array(
            'name' => 'ebif-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save rule',
                'class' => 'default-button',
                'style' => 'width: 175px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'ebif-pid' => array(
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function($value) {
                                if ($value == '0') {
                                    return false;
                                } else {
                                    return true;
                                }
                            },
                            'message' => 'Please choose a product',
                        ),
                    ),
                ),
            ),
            'ebif-amount-min' => array(
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
            'ebif-amount-max' => array(
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
            'ebif-priority' => array(
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