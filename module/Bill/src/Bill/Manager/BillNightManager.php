<?php

namespace Bill\Manager;

use Base\Manager\AbstractManager;
use Bill\Entity\BillNight;
use Bill\Entity\BillNightFactory;
use Bill\Table\BillNightTable;
use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Sql\Predicate\In;

class BillNightManager extends AbstractManager
{

    protected $billNightTable;

    /**
     * Creates a new manager object.
     *
     * @param BillNightTable $billNightTable
     */
    public function __construct(BillNightTable $billNightTable)
    {
        $this->billNightTable = $billNightTable;
    }

    /**
     * Saves (updates or creates) a bill night.
     *
     * @param BillNight $billNight
     * @return BillNight
     * @throws RuntimeException
     */
    public function save(BillNight $billNight)
    {
        $id = $billNight->getPrimary();

        if ($billNight->get($id)) {

            /* Update existing bill night */

            /* Determine updated properties */

            $updates = array();

            foreach ($billNight->need('updatedProperties') as $property) {
                $updates[$property] = $billNight->get($property);
            }

            if ($updates) {
                $this->billNightTable->update($updates, array($id => $billNight->get($id)));
            }

            $billNight->reset();

            $this->getEventManager()->trigger('save.update', $billNight);

        } else {

            /* Insert bill night */

            $insertValues = array(
                'bid' => $billNight->need('bid'),
                'rid' => $billNight->get('rid'),
                'date_arrival' => $billNight->need('date_arrival'),
                'date_departure' => $billNight->need('date_departure'),
                'date_repeat' => $billNight->get('date_repeat'),
                'quantity' => $billNight->need('quantity'),
                'price' => $billNight->need('price'),
                'rate' => $billNight->need('rate'),
                'gross' => $billNight->need('gross'),
            );

            if ($billNight->getExtra('n' . $id)) {
                $insertValues[$id] = $billNight->getExtra('n' . $id);
            }

            $this->billNightTable->insert($insertValues);

            $biid = $this->billNightTable->getLastInsertValue();

            if (! (is_numeric($biid) && $biid > 0)) {
                throw new RuntimeException('Failed to save bill night');
            }

            $billNight->add($id, $biid);

            $this->getEventManager()->trigger('save.insert', $billNight);
        }

        $this->getEventManager()->trigger('save', $billNight);

        return $billNight;
    }

    public function get($bnid, $strict = true)
    {
        $billNights = $this->getBy(array('bnid' => $bnid));

        if (empty($billNights)) {
            if ($strict) {
                throw new RuntimeException('This bill night does not exist');
            }

            return null;
        } else {
            return current($billNights);
        }
    }

    /**
     * Gets all bill nights that match the passed conditions.
     *
     * @param mixed $where              Any valid where conditions, but usually an array with key/value pairs.
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getBy($where, $order = null, $limit = null, $offset = null)
    {
        $select = $this->billNightTable->getSql()->select();

        if ($where) {
            $select->where($where);
        }

        if ($order) {
            $select->order($order);
        }

        if ($limit) {
            $select->limit($limit);

            if ($offset) {
                $select->offset($offset);
            }
        }

        $resultSet = $this->billNightTable->selectWith($select);

        return BillNightFactory::fromResultSet($resultSet);
    }

    public function getByBills(array $bills)
    {
        $bids = array();

        foreach ($bills as $bill) {
            $bid = $bill->get('bid');

            if ($bid) {
                $bids[] = $bid;
            }
        }

        if (! $bids) {
            return array();
        }

        $billNights = $this->getBy(array(new In('bid', $bids)));

        if (! $billNights) {
            return array();
        }

        foreach ($billNights as $bnid => $billNight) {
            $bid = $billNight->get('bid');

            if (isset($bills[$bid])) {
                $billNightsTmp = $bills[$bid]->getExtra('nights', array());
                $billNightsTmp[$bnid] = $billNight;

                $bills[$bid]->setExtra('nights', $billNightsTmp);
            }
        }

        return $billNights;
    }

    /**
     * Gets all bill nights.
     *
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAll($order = null, $limit = null, $offset = null)
    {
        return $this->getBy(null, $order, $limit, $offset);
    }

    /**
     * Deletes a bill night.
     *
     * @param BillNight|int $billNight
     * @return int
     * @throws InvalidArgumentException
     */
    public function delete($billNight)
    {
        if ($billNight instanceof BillNight) {
            $bnid = $billNight->need( $billNight->getPrimary() );
        } else {
            $bnid = $billNight;
        }

        if (! (is_numeric($bnid) && $bnid > 0)) {
            throw new InvalidArgumentException('Bill night id must be numeric');
        }

        $billNight = $this->get($bnid);

        $id = $billNight->getPrimary();

        $deletion = $this->billNightTable->delete(array($id => $bnid));

        $this->getEventManager()->trigger('delete', $billNight);

        return $deletion;
    }

}