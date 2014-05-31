<?php

namespace Booking\View\Helper\Bundle;

use Bundle\Entity\Bundle;
use DateTime;
use Zend\View\Helper\AbstractHelper;

class BundlesFormat extends AbstractHelper
{

    public function __invoke(array $bundles, DateTime $dateArrival, DateTime $dateDeparture, Bundle $bundleSelected = null)
    {
        $view = $this->getView();
        $html = '';

        $bookingPeriod = $dateArrival->diff($dateDeparture);

        $nights = $bookingPeriod->format('%a');

        foreach ($bundles as $bundle) {
            $html .= $view->bookingBundleFormat($bundle, $nights, $bundleSelected);
        }

        if (! $bundles) {
            $html .= sprintf('<div class="padded centered-text"><em class="symbolic symbolic-warning">%s</em></div>',
                $view->t('We are very sorry, but we have no offers for this time.'));
        }

        return $html;
    }

}