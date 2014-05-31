<?php

namespace Booking\Manager;

use Base\Entity\AbstractEntity;
use Base\Manager\AbstractEntityManager;
use Booking\Entity\BookingFactory;
use DateTime;
use Room\Entity\Room;
use RuntimeException;
use Traversable;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Where;

class BookingManager extends AbstractEntityManager
{

    protected function getInsertValues(AbstractEntity $entity)
    {
        $created = date('Y-m-d H:i:s');

        return array(
            'rid' => $entity->get('rid'),
            'uid' => $entity->get('uid'),
            'status' => $entity->need('status'),
            'date_arrival' => $entity->get('date_arrival'),
            'date_departure' => $entity->get('date_departure'),
            'date_repeat' => $entity->get('date_repeat'),
            'quantity' => $entity->need('quantity'),
            'created' => $entity->get('created', $created),
        );
    }

    protected function getByResultSet(Traversable $resultSet)
    {
        return BookingFactory::fromResultSet($resultSet);
    }

    protected function getByMetaResultSet(Traversable $metaResultSet, array $rooms)
    {
        return BookingFactory::fromMetaResultSet($rooms, $metaResultSet);
    }

    public function get($bid, $strict = true)
    {
        $bookings = $this->getBy(array('bid' => $bid));

        if (empty($bookings)) {
            if ($strict) {
                throw new RuntimeException('This booking does not exist');
            }

            return null;
        } else {
            return current($bookings);
        }
    }

    public function getBetween(DateTime $dateStart, DateTime $dateEnd, Room $room = null, $status = 'enabled', $inclusive = false)
    {
        $where = new Where();

        if ($room) {
            $nested = $where->nest();
            $nested->equalTo('rid', $room->need('rid'));
            $nested->or;
            $nested->isNull('rid');
            $nested->unnest();
            $where->and;
        }

        if ($status) {
            $where->equalTo('status', $status);
            $where->and;
        }

        if ($inclusive) {
            $where->greaterThanOrEqualTo('date_departure', $dateStart->format('Y-m-d'));
            $where->and;
            $where->lessThanOrEqualTo('date_arrival', $dateEnd->format('Y-m-d'));
        } else {
            $where->greaterThan('date_departure', $dateStart->format('Y-m-d'));
            $where->and;
            $where->lessThan('date_arrival', $dateEnd->format('Y-m-d'));
        }

        return $this->getBy($where, 'date_arrival ASC');
    }

    public function getByBills(array $bills)
    {
        $bids = array();

        foreach ($bills as $bill) {
            $bid = $bill->get('booking');

            if ($bid) {
                $bids[] = $bid;
            }
        }

        if (! $bids) {
            return array();
        }

        $bookings = $this->getBy(array(new In('bid', $bids)));

        foreach ($bills as $bill) {
            $bid = $bill->get('booking');

            if (isset($bookings[$bid])) {
                $bill->setExtra('booking', $bookings[$bid]);
            }
        }

        return $bookings;
    }

}