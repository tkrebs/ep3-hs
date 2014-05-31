<?php

namespace Bundle\Manager;

use Base\Entity\AbstractEntity;
use Base\Manager\AbstractEntityManager;
use Bundle\Entity\BundleFactory;
use DateTime;
use Room\Entity\Room;
use RuntimeException;
use Traversable;
use Zend\Db\Sql\Where;

class BundleManager extends AbstractEntityManager
{

    protected function getInsertValues(AbstractEntity $entity)
    {
        return array(
            'rid' => $entity->get('rid'),
            'rid_group' => $entity->get('rid_group'),
            'status' => $entity->need('status'),
            'code' => $entity->get('code'),
            'priority' => $entity->get('priority'),
            'date_start' => $entity->get('date_start'),
            'date_end' => $entity->get('date_end'),
            'date_repeat' => $entity->get('date_repeat'),
        );
    }

    protected function getByResultSet(Traversable $resultSet)
    {
        return BundleFactory::fromResultSet($resultSet);
    }

    protected function getByMetaResultSet(Traversable $metaResultSet, array $rooms)
    {
        return BundleFactory::fromMetaResultSet($rooms, $metaResultSet);
    }

    public function get($bid, $strict = true)
    {
        $bundles = $this->getBy(array('bid' => $bid));

        if (empty($bundles)) {
            if ($strict) {
                throw new RuntimeException('This bundle does not exist');
            }

            return null;
        } else {
            return current($bundles);
        }
    }

    public function getByBooking(DateTime $dateArrival, DateTime $dateDeparture, Room $room, $code = null)
    {
        $where = new Where();

        $nested = $where->nest();
        $nested->equalTo('rid', $room->need('rid'));
        $nested->or;
        $nested->isNull('rid');
        $nested->unnest();

        $where->and->equalTo('status', 'enabled');

        if ($code) {
            $where->and->equalTo('code', $code);
        } else {
            $where->and->isNull('code');
        }

        $nested = $where->nest();
        $nested->lessThanOrEqualTo('date_start', $dateArrival->format('Y-m-d'));
        $nested->or;
        $nested->isNull('date_start');
        $nested->unnest();

        $nested->and;

        $nested = $where->nest();
        $nested->greaterThanOrEqualTo('date_end', $dateDeparture->format('Y-m-d'));
        $nested->or;
        $nested->isNull('date_end');
        $nested->unnest();

        return $this->getBy($where, 'priority DESC');
    }

}