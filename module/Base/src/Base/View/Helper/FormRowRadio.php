<?php

namespace Base\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormRowRadio extends AbstractHelper
{

    public function __invoke($form, $id)
    {
        $view = $this->getView();

        if ($id instanceof ElementInterface) {
            $formElement = $id;
        } else {
            $formElement = $form->get($id);
        }

        $postfix = $formElement->getOption('postfix');

        if ($postfix) {
            $postfix = sprintf('<span class="default-form-postfix" style="margin-left: 8px;">%s</span>', $postfix);
        }

        $html = sprintf('<tr><td class="default-form-label-row" style="padding-top: 3px;">%s</td><td class="default-form-radio-group">%s %s %s %s</td></tr>',
            $view->formLabel($formElement),
            $view->formElement($formElement),
            $postfix,
            $view->formElementNotes($formElement),
            $view->formElementErrors($formElement));

        return $html;
    }

}