<?php

namespace Room\Manager;

use Base\Entity\AbstractEntity;
use Base\Manager\AbstractEntityManager;
use Room\Entity\RoomFactory;
use RuntimeException;
use Traversable;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;

class RoomManager extends AbstractEntityManager
{

    protected function getInsertValues(AbstractEntity $entity)
    {
        return array(
            'rid_prototype' => $entity->get('rid_prototype'),
            'rnr' => $entity->need('rnr'),
            'status' => $entity->need('status'),
            'capacity' => $entity->need('capacity'),
        );
    }

    protected function getByResultSet(Traversable $resultSet)
    {
        return RoomFactory::fromResultSet($resultSet);
    }

    protected function getByMetaResultSet(Traversable $metaResultSet, array $rooms)
    {
        return RoomFactory::fromMetaResultSet($rooms, $metaResultSet);
    }

    public function get($rid, $strict = true)
    {
        $rooms = $this->getBy(array('rid' => $rid));

        if (empty($rooms)) {
            if ($strict) {
                throw new RuntimeException('This room does not exist');
            }

            return null;
        } else {
            return current($rooms);
        }
    }

    public function getCapacityMax()
    {
        $select = $this->entityTable->getSql()->select();
        $select->columns(array('capacity' => new Expression('MAX(capacity)')));

        $resultSet = $this->entityTable->selectWith($select);

        foreach ($resultSet as $result) {
            return (int) $result->capacity;
        }
    }

    public function getByBookings(array $bookings)
    {
        $rids = array();

        foreach ($bookings as $booking) {
            $rid = $booking->get('rid');

            if ($rid) {
                $rids[] = $rid;
            }
        }

        if (! $rids) {
            return array();
        }

        $rooms = $this->getBy(array(new In('rid', $rids)));

        foreach ($bookings as $booking) {
            $rid = $booking->get('rid');

            if (isset($rooms[$rid])) {
                $booking->setExtra('room', $rooms[$rid]);
            }
        }

        return $rooms;
    }

    public function getByBundles(array $bundles)
    {
        $rids = array();

        foreach ($bundles as $bundle) {
            $rid = $bundle->get('rid');

            if ($rid) {
                $rids[] = $rid;
            }
        }

        if (! $rids) {
            return array();
        }

        $rooms = $this->getBy(array(new In('rid', $rids)));

        foreach ($bundles as $bundle) {
            $rid = $bundle->get('rid');

            if (isset($rooms[$rid])) {
                $bundle->setExtra('room', $rooms[$rid]);
            }
        }

        return $rooms;
    }

}