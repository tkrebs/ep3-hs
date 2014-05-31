<?php

namespace Bill\Manager;

use Base\Entity\AbstractEntity;
use Base\Manager\AbstractEntityManager;
use Bill\Entity\BillFactory;
use RuntimeException;
use Traversable;
use Zend\Db\Sql\Predicate\In;

class BillManager extends AbstractEntityManager
{

    protected function getInsertValues(AbstractEntity $entity)
    {
        $created = date('Y-m-d H:i:s');

        return array(
            'bnr' => $entity->get('bnr'),
            'status' => $entity->need('status'),
            'booking' => $entity->get('booking'),
            'bundle' => $entity->get('bundle'),
            'bundle_name' => $entity->get('bundle_name'),
            'user' => $entity->need('user'),
            'created' => $entity->get('created', $created),
        );
    }

    protected function getByResultSet(Traversable $resultSet)
    {
        return BillFactory::fromResultSet($resultSet);
    }

    protected function getByMetaResultSet(Traversable $metaResultSet, array $rooms)
    {
        return BillFactory::fromMetaResultSet($rooms, $metaResultSet);
    }

    public function get($bid, $strict = true)
    {
        $bills = $this->getBy(array('bid' => $bid));

        if (empty($bills)) {
            if ($strict) {
                throw new RuntimeException('This bill does not exist');
            }

            return null;
        } else {
            return current($bills);
        }
    }

    public function getByBookings(array $bookings)
    {
        $bids = array();

        foreach ($bookings as $booking) {
            $bid = $booking->get('bid');

            if ($bid) {
                $bids[] = $bid;
            }
        }

        if (! $bids) {
            return array();
        }

        $bills = $this->getBy(array(new In('booking', $bids)));

        if (! $bills) {
            return array();
        }

        foreach ($bills as $bill) {
            $bid = $bill->get('booking');

            if (isset($bookings[$bid])) {
                $bookings[$bid]->setExtra('bill', $bill);
            }
        }

        return $bills;
    }

}