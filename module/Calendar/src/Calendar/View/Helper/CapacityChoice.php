<?php

namespace Calendar\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CapacityChoice extends AbstractHelper
{

    public function __invoke($capacityMax, $capacityChoice = 1)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table>';

        for ($i = 1; $i <= $capacityMax; $i++) {

            /* Spacing Row */

            if ($i > 1) {
                $html .= '<tr><td colspan="3" style="height: 12px;"></td></tr>';
            }

            $html .= '<tr>';

            /* Radio Col */

            $html .= '<td class="calendar-capacity-radio-col">';

            if ($i == $capacityChoice) {
                $checked = 'checked="checked"';
            } else {
                $checked = null;
            }

            $html .= sprintf('<input type="radio" name="capacity" value="%s" id="calendar-capacity-%s" class="calendar-capacity-radio" %s>',
                $i, $i, $checked);

            $html .= '</td>';

            /* Symbol Col */

            $html .= '<td class="calendar-capacity-symbol-col centered-text">';

            $html .= sprintf('<label for="calendar-capacity-%s"><img src="%s"></label>',
                $i, $view->basePath('imgs/icons/persons/' . min($i, 5) . '.png'));

            $html .= '</td>';

            /* Text Col */

            $html .= '<td class="calendar-capacity-text-col">';

            if ($i == 1) {
                $label = $view->t('Person');
            } else {
                $label = $view->t('Persons');
            }

            $html .= sprintf('<label for="calendar-capacity-%s">%s %s</label>',
                $i, $i, $label);

            $html .= '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

}