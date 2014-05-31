<?php

namespace Bill\Manager;

use Base\Manager\AbstractManager;
use Bill\Entity\BillItem;
use Bill\Entity\BillItemFactory;
use Bill\Table\BillItemTable;
use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Sql\Predicate\In;

class BillItemManager extends AbstractManager
{

    protected $billItemTable;

    /**
     * Creates a new manager object.
     *
     * @param BillItemTable $billItemTable
     */
    public function __construct(BillItemTable $billItemTable)
    {
        $this->billItemTable = $billItemTable;
    }

    /**
     * Saves (updates or creates) a bill item.
     *
     * @param BillItem $billItem
     * @return BillItem
     * @throws RuntimeException
     */
    public function save(BillItem $billItem)
    {
        $id = $billItem->getPrimary();

        if ($billItem->get($id)) {

            /* Update existing bill item */

            /* Determine updated properties */

            $updates = array();

            foreach ($billItem->need('updatedProperties') as $property) {
                $updates[$property] = $billItem->get($property);
            }

            if ($updates) {
                $this->billItemTable->update($updates, array($id => $billItem->get($id)));
            }

            $billItem->reset();

            $this->getEventManager()->trigger('save.update', $billItem);

        } else {

            /* Insert bill item */

            $insertValues = array(
                'bid' => $billItem->need('bid'),
                'pid' => $billItem->get('pid'),
                'pid_name' => $billItem->need('pid_name'),
                'priority' => $billItem->need('priority'),
                'amount' => $billItem->need('amount'),
                'price' => $billItem->need('price'),
                'rate' => $billItem->need('rate'),
                'gross' => $billItem->need('gross'),
            );

            if ($billItem->getExtra('n' . $id)) {
                $insertValues[$id] = $billItem->getExtra('n' . $id);
            }

            $this->billItemTable->insert($insertValues);

            $biid = $this->billItemTable->getLastInsertValue();

            if (! (is_numeric($biid) && $biid > 0)) {
                throw new RuntimeException('Failed to save bill item');
            }

            $billItem->add($id, $biid);

            $this->getEventManager()->trigger('save.insert', $billItem);
        }

        $this->getEventManager()->trigger('save', $billItem);

        return $billItem;
    }

    public function get($biid, $strict = true)
    {
        $billItems = $this->getBy(array('biid' => $biid));

        if (empty($billItems)) {
            if ($strict) {
                throw new RuntimeException('This bill item does not exist');
            }

            return null;
        } else {
            return current($billItems);
        }
    }

    /**
     * Gets all bill items that match the passed conditions.
     *
     * @param mixed $where              Any valid where conditions, but usually an array with key/value pairs.
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getBy($where, $order = null, $limit = null, $offset = null)
    {
        $select = $this->billItemTable->getSql()->select();

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

        $resultSet = $this->billItemTable->selectWith($select);

        return BillItemFactory::fromResultSet($resultSet);
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

        $billItems = $this->getBy(array(new In('bid', $bids)));

        if (! $billItems) {
            return array();
        }

        foreach ($billItems as $biid => $billItem) {
            $bid = $billItem->get('bid');

            if (isset($bills[$bid])) {
                $billItemsTmp = $bills[$bid]->getExtra('items', array());
                $billItemsTmp[$biid] = $billItem;

                $bills[$bid]->setExtra('items', $billItemsTmp);
            }
        }

        return $billItems;
    }

    /**
     * Gets all bill items.
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
     * Deletes a bill item.
     *
     * @param BillItem|int $billItem
     * @return int
     * @throws InvalidArgumentException
     */
    public function delete($billItem)
    {
        if ($billItem instanceof BillItem) {
            $biid = $billItem->need( $billItem->getPrimary() );
        } else {
            $biid = $billItem;
        }

        if (! (is_numeric($biid) && $biid > 0)) {
            throw new InvalidArgumentException('Bill item id must be numeric');
        }

        $billItem = $this->get($biid);

        $id = $billItem->getPrimary();

        $deletion = $this->billItemTable->delete(array($id => $biid));

        $this->getEventManager()->trigger('delete', $billItem);

        return $deletion;
    }

}