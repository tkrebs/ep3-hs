<?php

namespace Booking\Controller\Plugin\PayPal;

use Base\Manager\ConfigManager;
use Base\Manager\OptionManager;
use Booking\Service\PayPalService;
use Bundle\Entity\Bundle;
use DateTime;
use Room\Entity\Room;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

class RedirectToPayPal extends AbstractPlugin
{

    protected $configManager;
    protected $optionManager;
    protected $payPalService;

    public function __construct(ConfigManager $configManager, OptionManager $optionManager, PayPalService $payPalService)
    {
        $this->configManager = $configManager;
        $this->optionManager = $optionManager;
        $this->payPalService = $payPalService;
    }

    public function __invoke(DateTime $dateArrival, DateTime $dateDeparture, $capacity, Room $room, Bundle $bundle, $bundleItemsCode, Container $container)
    {
        throw new RuntimeException('PayPal payment not yet implemented');
    }

    protected function determineTransactionTotal(Bundle $bundle)
    {
        $total = 0;

        /* Night */

        $total += $bundle->needExtra('nights') * $bundle->needExtra('night')->need('price');

        /* Items */

        foreach ($bundle->needExtra('items') as $bundleItem) {
            $amount = $bundleItem->needExtra('amount');

            if ($bundleItem->need('due') == 'per_night') {
                if ($amount == 1) {
                    $amount = $bundle->needExtra('nights');
                }
            }

            $total += $bundleItem->need('price') * $amount;
        }

        /* Format */

        return $this->determinePrice($total);
    }

    protected function determineTransactionItems(Bundle $bundle)
    {
        $transItems = array();

        /* Night */

        $transItems[] = array(
            'name' => $this->t('Nights'),
            'quantity' => $this->determineNumber($bundle->needExtra('nights')),
            'price' => $this->determinePrice($bundle->needExtra('night')->need('price')),
            'currency' => $this->configManager->get('i18n.currency'),
        );

        /* Items */

        foreach ($bundle->needExtra('items') as $bundleItem) {
            $amount = $bundleItem->needExtra('amount');
            $price = $bundleItem->need('price');

            if ($amount > 0 && $price > 0) {
                if ($bundleItem->need('due') == 'per_night') {
                    if ($amount == 1) {
                        $amount = $bundle->needExtra('nights');
                    }
                }

                $transItems[] = array(
                    'name' => $bundleItem->needExtra('product')->getMeta('name'),
                    'quantity' => $this->determineNumber($amount),
                    'price' => $this->determinePrice($price),
                    'currency' => $this->configManager->get('i18n.currency'),
                );
            }
        }

        return $transItems;
    }

    protected function determineTransactionDescription(DateTime $dateArrival, DateTime $dateDeparture)
    {
        return sprintf($this->t('%s booking from %s to %s'),
            $this->optionManager->get('client.name.full'),
            $this->getController()->dateFormat($dateArrival),
            $this->getController()->dateFormat($dateDeparture));
    }

    protected function determineUrl($route, DateTime $dateArrival, DateTime $dateDeparture, $capacity, Room $room, Bundle $bundle, $bundleItemsCode)
    {
        $website = rtrim($this->optionManager->get('service.website'), '/');

        $path = $this->getController()->url()->fromRoute($route, [], ['query' => [
            'date-arrival' => $dateArrival->format('Y-m-d'),
            'date-departure' => $dateDeparture->format('Y-m-d'),
            'capacity' => $capacity,
            'room' => $room->need('rid'),
            'bundle' => $bundle->need('bid'),
            'bundle-items' => $bundleItemsCode,
        ]]);

        return sprintf('%s%s', $website, $path);
    }

    protected function determineNumber($number)
    {
        return (string) $number;
    }

    protected function determinePrice($price)
    {
        return $this->determineNumber(round($price / 100, 2));
    }

    protected function t($text)
    {
        return $this->getController()->t($text);
    }

}