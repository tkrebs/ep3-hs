<?php

namespace Booking\View\Helper;

use Zend\View\Helper\AbstractHelper;

class BookingRules extends AbstractHelper
{

    public function __invoke()
    {
        $view = $this->getView();
        $html = '';

        $rulesDocumentFile = $view->option('subject.rules.document.file');
        $rulesDocumentName = $view->option('subject.rules.document.name', 'Rules & Conditions');
        $rulesText = $view->option('subject.rules.text');

        if ($rulesDocumentFile) {
            $html .= '<div class="sandbox sandbox-list">';

            $html .= sprintf('<p class="symbolic symbolic-warning">%s</p>',
                $view->t('Please view the following:'));

            /* Document */

            $html .= '<p style="margin-left: 32px;">';

            $html .= sprintf('<a href="%s" class="default-button" target="_blank"><span class="symbolic symbolic-attachment">%s</span></a>',
                $view->basePath($rulesDocumentFile), $rulesDocumentName);

            $html .= sprintf('<span class="small-text gray" style="margin-left: 8px;">(%s)</span>',
                $view->t('this will open in a new window'));

            $html .= '</p>';

            /* Checkbox */

            $html .= '<div style="margin-left: 32px;">';

            $html .= '<input type="checkbox" name="bf-accept-rules-document" id="bf-accept-rules-document">';

            $html .= sprintf('<label for="bf-accept-rules-document" style="margin-left: 4px;">%s</label>',
                sprintf($view->t('Yes, I have %1$sread and accepted%2$s the "%3$s"'),
                    '<b>', '</b>', $rulesDocumentName));

            $html .= '</div>';

            $html .= '</div>';
        }

        if ($rulesText) {
            $html .= '<div class="sandbox sandbox-list">';

            $html .= sprintf('<p class="symbolic symbolic-warning">%s</p>',
                $view->t('Please view the following:'));

            /* Text */

            $html .= '<div style="margin-left: 32px;">';
            $html .= $rulesText;
            $html .= '</div>';

            /* Checkbox */

            $html .= '<div style="margin-left: 32px; margin-top: 16px;">';

            $html .= '<input type="checkbox" name="bf-accept-rules-text" id="bf-accept-rules-text">';

            $html .= sprintf('<label for="bf-accept-rules-text" style="margin-left: 4px;">%s</label>',
                sprintf($view->t('Yes, I have %sread and accepted%s these rules and notes'),
                    '<b>', '</b>'));

            $html .= '</div>';

            $html .= '</div>';
        }

        return $html;
    }

}