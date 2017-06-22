<?php

namespace Booking\Service;

use Base\Service\AbstractService;
use Bill\Entity\Bill;
use Bill\Entity\BillItem;
use Bill\Entity\BillNight;
use Bill\Manager\BillItemManager;
use Bill\Manager\BillManager;
use Bill\Manager\BillNightManager;
use Booking\Entity\Booking;
use Booking\Manager\BookingManager;
use Bundle\Entity\Bundle;
use Bundle\Manager\BundleItemManager;
use Bundle\Manager\BundleNightManager;
use DateTime;
use Exception;
use Room\Entity\Room;
use User\Entity\User;
use User\Manager\UserManager;
use Zend\Db\Adapter\Adapter;

class BookingService extends AbstractService
{

    protected $billManager;
    protected $billItemManager;
    protected $billNightManager;
    protected $bookingManager;
    protected $bundleItemManager;
    protected $bundleNightManager;
    protected $userManager;
    protected $dbAdapter;

    public function __construct(BillManager $billManager, BillItemManager $billItemManager, BillNightManager $billNightManager,
        BookingManager $bookingManager, BundleItemManager $bundleItemManager, BundleNightManager $bundleNightManager,
        UserManager $userManager, Adapter $dbAdapter)
    {
        $this->billManager = $billManager;
        $this->billItemManager = $billItemManager;
        $this->billNightManager = $billNightManager;
        $this->bookingManager = $bookingManager;
        $this->bundleItemManager = $bundleItemManager;
        $this->bundleNightManager = $bundleNightManager;
        $this->userManager = $userManager;
        $this->dbAdapter = $dbAdapter;
    }

    public function createUser(array $registrationData)
    {
        return new User(array(
            'alias' => $registrationData['rf-firstname'] . ' ' . $registrationData['rf-lastname'],
            'status' => 'guest',
            'email' => $registrationData['rf-email'],
            'last_ip' => $_SERVER['REMOTE_ADDR'],
        ), array(
            'gender' => $registrationData['rf-gender'],
            'firstname' => $registrationData['rf-firstname'],
            'lastname' => $registrationData['rf-lastname'],
            'street' => $registrationData['rf-street'],
            'zip' => $registrationData['rf-zip'],
            'city' => $registrationData['rf-city'],
            'country' => $registrationData['rf-country'],
            'phone' => $registrationData['rf-phone'],
        ));
    }

    public function createBooking(DateTime $dateArrival, DateTime $dateDeparture, $quantity, Room $room, User $user,
        Bundle $bundle, array $bundleItems, array $bookingMeta = array(), $billStatus = 'pending', array $billMeta = array())
    {
        $connection = $this->dbAdapter->getDriver()->getConnection();

        if (! $connection->inTransaction()) {
            $connection->beginTransaction();
            $transaction = true;
        } else {
            $transaction = false;
        }

        try {

            /* Save User */

            $this->userManager->save($user);

            /* Save Booking */

            $booking = new Booking(array(
                'rid' => $room->need('rid'),
                'uid' => $user->need('uid'),
                'status' => 'enabled',
                'date_arrival' => $dateArrival->format('Y-m-d'),
                'date_departure' => $dateDeparture->format('Y-m-d'),
                'quantity' => $quantity,
            ), $bookingMeta);

            $this->bookingManager->save($booking);

            $booking->setExtra('room', $room);
            $booking->setExtra('user', $user);

            /* Save Bill */

            $bill = new Bill(array(
                'status' => $billStatus,
                'booking' => $booking->need('bid'),
                'bundle' => $bundle->need('bid'),
                'bundle_name' => $bundle->getMeta('name', '-'),
                'user' => $user->need('uid'),
            ), $billMeta);

            $this->billManager->save($bill);

            $booking->setExtra('bill', $bill);

            /* Save Bill Items */

            $billItems = array();

            foreach ($bundleItems as $bundleItem) {
                $bundleItemAmount = $bundleItem->needExtra('amount');

                if ($bundleItemAmount > 0) {
                    if ($bundleItem->need('due') == 'per_night') {
                        if ($bundleItemAmount == 1) {
                            $bundleItemAmount = $bundle->needExtra('nights');
                        }
                    }

                    $billItem = new BillItem(array(
                        'bid' => $bill->need('bid'),
                        'pid' => $bundleItem->need('pid'),
                        'pid_name' => $bundleItem->needExtra('product')->getMeta('name'),
                        'priority' => $bundleItem->need('priority'),
                        'amount' => $bundleItemAmount,
                        'price' => $bundleItem->need('price') * $bundleItemAmount,
                        'rate' => $bundleItem->need('rate'),
                        'gross' => $bundleItem->need('gross'),
                    ));

                    $this->billItemManager->save($billItem);

                    $billItems[$billItem->need('biid')] = $billItem;
                }
            }

            $bill->setExtra('items', $billItems);

            /* Save Bill Night */

            $bundleNights = $bundle->needExtra('nights');
            $bundleNightRule = $this->bundleNightManager->getByNights($bundle, $bundleNights);

            $billNight = new BillNight(array(
                'bid' => $bill->need('bid'),
                'rid' => $room->need('rid'),
                'date_arrival' => $dateArrival->format('Y-m-d'),
                'date_departure' => $dateDeparture->format('Y-m-d'),
                'quantity' => $quantity,
                'price' => $bundleNightRule->need('price') * $bundleNights,
                'rate' => $bundleNightRule->need('rate'),
                'gross' => $bundleNightRule->need('gross'),
            ));

            $this->billNightManager->save($billNight);

            $bill->setExtra('night', $billNight);

            /* Done! */

            if ($transaction) {
                $connection->commit();
                $transaction = false;
            }

            $this->getEventManager()->trigger('book', $booking);

            return $booking;

        } catch (Exception $e) {
            if ($transaction) {
                $connection->rollback();
            }

            throw $e;
        }
    }

}
