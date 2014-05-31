<?php

namespace Backend\View\Helper\Bundle;

use Bundle\Entity\Bundle;
use DateTime;
use IntlDateFormatter;
use Zend\View\Helper\AbstractHelper;

class BundleFormat extends AbstractHelper
{

    public function __invoke(Bundle $bundle)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<tr>';

        $html .= sprintf('<td>%s</td>',
            $bundle->need('bid'));

        $html .= sprintf('<td>%s</td>',
            $bundle->getMeta('name', '-'));

        /* Room */

        if ($bundle->getExtra('room')) {
            $roomLabel = $bundle->getExtra('room')->getName($view);
        } else {
            $roomLabel = $view->t('All rooms');
        }

        $html .= sprintf('<td>%s</td>', $roomLabel);

        /* Start date */

        if ($bundle->get('date_start')) {
            $dateStart = $view->dateFormat(new DateTime($bundle->get('date_start')), IntlDateFormatter::MEDIUM);
        } else {
            $dateStart = $view->t('None');
        }

        $html .= sprintf('<td>%s</td>', $dateStart);

        /* End date */

        if ($bundle->get('date_end')) {
            $dateEnd = $view->dateFormat(new DateTime($bundle->get('date_end')), IntlDateFormatter::MEDIUM);
        } else {
            $dateEnd = $view->t('None');
        }

        $html .= sprintf('<td>%s</td>', $dateEnd);

        /* Code */

        if ($bundle->get('code')) {
            $code = $bundle->get('code');

            if (strlen($code) > 8) {
                $code = substr($code, 0, 8) . '&hellip;';
            }
        } else {
            $code = $view->t('None');
        }

        $html .= sprintf('<td>%s</td>', $code);

        /* Actions */

        $html .= '<td class="symbolic-link-list">';

        $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a>',
            $view->url('backend/bundle/edit', ['bid' => $bundle->need('bid')]), $view->t('Edit'));

        $html .= sprintf('<a href="%s" class="symbolic symbolic-edit symbolic-link">%s</a>',
            $view->url('backend/bundle/edit/component', ['bid' => $bundle->need('bid')]), $view->t('Edit components'));

        $html .= sprintf('<a href="%s" class="symbolic symbolic-cross symbolic-link" data-tooltip="%s"></a>',
            $view->url('backend/bundle/delete', ['bid' => $bundle->need('bid')]), $view->t('Delete'));

        $html .= '</td>';

        $html .= '</tr>';

        return $html;
    }

}