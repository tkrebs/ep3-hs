<?php

namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Badges extends AbstractHelper
{

    public function __invoke()
    {
        $view = $this->getView();
        $html = '';

        $badges = $view->placeholder('badges')->getValue();
        $misc = $view->placeholder('misc')->getValue();

        if (is_array($badges)) {
            $badgesCount = count($badges);

            if ($badgesCount > 0) {
                $html .= '<div style="margin-bottom: 16px;">';
                $html .= '<table class="default-table full-width">';
                $html .= '<tr>';

                $badgesWidth = floor(100 / $badgesCount);

                foreach ($badges as $badgeNumber => $badgeLabel) {
                    $badgeOpacity = 0.5;

                    if (is_array($misc) && isset($misc['badgesActive'])) {
                        $badgesActive = $misc['badgesActive'];

                        if (is_array($badgesActive)) {
                            if (in_array($badgeNumber, $badgesActive)) {
                                $badgeOpacity = 1.00;
                            }
                        } else {
                            if ($badgeNumber == $badgesActive) {
                                $badgeOpacity = 1.00;
                            }
                        }
                    }

                    $html .= sprintf('<td class="centered-text" style="width: %s%%; opacity: %s;">',
                        $badgesWidth, $badgeOpacity);

                    if (is_array($badgeLabel)) {
                        $badgeUrl = current($badgeLabel);
                        $badgeLabel = key($badgeLabel);
                    } else {
                        $badgeUrl = null;
                    }

                    if ($badgeUrl) {
                        $html .= '<a href="' . $badgeUrl . '" class="unlined" style="display: block; opacity: 1.0;">';
                        $badgeStyle = 'cursor: pointer;';
                    } else {
                        $badgeStyle = null;
                    }

                    $html .= '<div class="badge-white" style="' . $badgeStyle . '">' . $badgeNumber . '</div>';
                    $html .= '<div class="badge-label-white large-text" style="margin-top: 4px; ' . $badgeStyle . '">' . $view->translate($badgeLabel) . '</div>';

                    if ($badgeUrl) {
                        $html .= '</a>';
                    }

                    $html .= '</td>';
                }

                $html .= '</tr>';
                $html .= '</table>';
                $html .= '</div>';
            }
        }

        if ($html) {
            if (is_array($misc) && isset($misc['badgesPanel'])) {
                $panel = $misc['badgesPanel'];
            } else {
                $panel = $view->placeholder('panel');
            }

            $html = sprintf('<div class="%s phantom-panel badges-panel">%s</div>',
                $panel, $html);
        }

        return $html;
    }

}