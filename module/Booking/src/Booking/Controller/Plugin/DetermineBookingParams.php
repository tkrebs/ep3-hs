<?php

namespace Booking\Controller\Plugin;

use Booking\Manager\BookingManager;
use Bundle\Manager\BundleItemManager;
use Bundle\Manager\BundleManager;
use Bundle\Manager\BundleNightManager;
use DateTime;
use Product\Manager\ProductManager;
use Room\Manager\RoomManager;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineBookingParams extends AbstractPlugin
{

    protected $bookingManager;
    protected $bundleManager;
    protected $bundleItemManager;
    protected $bundleNightManager;
    protected $productManager;
    protected $roomManager;

    public function __construct(BookingManager $bookingManager, BundleManager $bundleManager,
        BundleItemManager$bundleItemManager, BundleNightManager $bundleNightManager,
        ProductManager $productManager, RoomManager $roomManager)
    {
        $this->bookingManager = $bookingManager;
        $this->bundleManager = $bundleManager;
        $this->bundleItemManager = $bundleItemManager;
        $this->bundleNightManager = $bundleNightManager;
        $this->productManager = $productManager;
        $this->roomManager = $roomManager;
    }

    public function __invoke($requireBundle = false)
    {
        $controller = $this->getController();

        $dateArrival = $controller->validateDateArrival($controller->determineDateArrival(), true);
        $dateDeparture = $controller->validateDateDeparture($controller->determineDateDeparture(), $dateArrival, true);

        $bookingPeriod = $dateArrival->diff($dateDeparture);

        $dateNights = $bookingPeriod->format('%a');

        $roomParam = $controller->params()->fromQuery('room');

        if (! is_numeric($roomParam)) {
            $roomParam = 0;
        }

        $room = $this->roomManager->get($roomParam);

        $capacity = $controller->determineCapacity($room->need('capacity'));

        $bookings = $this->bookingManager->getBetween($dateArrival, $dateDeparture, $room);

        if ($bookings) {
            throw new RuntimeException('Unfortunately, this room has already been booked for this time');
        }

        $bundleParam = $controller->params()->fromQuery('bundle');

        if ($bundleParam || $requireBundle) {
            $bundle = $this->bundleManager->get($bundleParam);

            if ($bundle->get('rid') && $bundle->get('rid') != $room->get('rid')) {
                throw new RuntimeException('The selected bundle is not applicable for this visit');
            }

            if ($bundle->need('status') == 'disabled') {
                throw new RuntimeException('The selected bundle is currently not available');
            }

            if ($bundle->get('date_start')) {
                $bundleDateStart = new DateTime($bundle->get('date_start'));

                if ($dateArrival < $bundleDateStart) {
                    throw new RuntimeException('The selected bundle is not applicable for this visit');
                }
            }

            if ($bundle->get('date_end')) {
                $bundleDateEnd = new DateTime($bundle->get('date_end'));

                if ($dateDeparture > $bundleDateEnd) {
                    throw new RuntimeException('The selected bundle is not applicable for this visit');
                }
            }

            /* Bundle Night */

            $bundleNight = $this->bundleNightManager->getByNights($bundle, $dateNights);

            $bundle->setExtra('night', $bundleNight);
            $bundle->setExtra('nights', $dateNights);

            /* Bundle Items */

            $bundleItemsCode = $controller->params()->fromQuery('bundle-items');
            $bundleItemCodes = explode('-', $bundleItemsCode);
            $bundleItemAmounts = array();

            foreach ($bundleItemCodes as $bundleItemCode) {
                $bundleItemCodeParts = explode('x', $bundleItemCode);

                if (count($bundleItemCodeParts) == 2) {
                    $amount = $bundleItemCodeParts[0];
                    $biid = $bundleItemCodeParts[1];

                    if (is_numeric($amount) && is_numeric($biid)) {
                        $bundleItemAmounts[$biid] = $amount;
                    }
                }
            }

            $bundleItems = $this->bundleItemManager->getBy(array('bid' => $bundle->need('bid')), 'priority DESC');

            foreach ($bundleItems as $biid => $bundleItem) {
                if (! isset($bundleItemAmounts[$biid])) {
                    throw new RuntimeException('Invalid choice of additional booking products received');
                }

                if ($bundleItemAmounts[$biid] > $bundleItem->need('amount_max') || $bundleItemAmounts[$biid] < $bundleItem->need('amount_min')) {
                    throw new RuntimeException('Invalid choice of additional booking products received');
                }

                $bundleItem->setExtra('amount', $bundleItemAmounts[$biid]);
            }

            $this->productManager->getByBundleItems($bundleItems);

            $bundle->setExtra('items', $bundleItems);
        } else {
            $bundle = null;
            $bundleItems = null;
            $bundleItemsCode = null;
        }

        return array(
            'dateArrival' => $dateArrival,
            'dateDeparture' => $dateDeparture,
            'dateNights' => $dateNights,
            'capacity' => $capacity,
            'room' => $room,
            'bundle' => $bundle,
            'bundleItems' => $bundleItems,
            'bundleItemsCode' => $bundleItemsCode,
        );
    }

}