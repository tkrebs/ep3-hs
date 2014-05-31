<?php

namespace Bundle\Manager;

use Base\Manager\AbstractManager;
use Bundle\Entity\BundleItem;
use Bundle\Entity\BundleItemFactory;
use Bundle\Table\BundleItemTable;
use InvalidArgumentException;
use RuntimeException;

class BundleItemManager extends AbstractManager
{

    protected $bundleItemTable;

    /**
     * Creates a new manager object.
     *
     * @param BundleItemTable $bundleItemTable
     */
    public function __construct(BundleItemTable $bundleItemTable)
    {
        $this->bundleItemTable = $bundleItemTable;
    }

    /**
     * Saves (updates or creates) a bundle item.
     *
     * @param BundleItem $bundleItem
     * @return BundleItem
     * @throws RuntimeException
     */
    public function save(BundleItem $bundleItem)
    {
        $id = $bundleItem->getPrimary();

        if ($bundleItem->get($id)) {

            /* Update existing bundle item */

            /* Determine updated properties */

            $updates = array();

            foreach ($bundleItem->need('updatedProperties') as $property) {
                $updates[$property] = $bundleItem->get($property);
            }

            if ($updates) {
                $this->bundleItemTable->update($updates, array($id => $bundleItem->get($id)));
            }

            $bundleItem->reset();

            $this->getEventManager()->trigger('save.update', $bundleItem);

        } else {

            /* Insert bundle item */

            $insertValues = array(
                'bid' => $bundleItem->need('bid'),
                'pid' => $bundleItem->need('pid'),
                'priority' => $bundleItem->need('priority'),
                'due' => $bundleItem->need('due'),
                'amount_min' => $bundleItem->need('amount_min'),
                'amount_max' => $bundleItem->need('amount_max'),
                'price' => $bundleItem->need('price'),
                'price_fixed' => $bundleItem->need('price_fixed'),
                'rate' => $bundleItem->need('rate'),
                'gross' => $bundleItem->need('gross'),
            );

            if ($bundleItem->getExtra('n' . $id)) {
                $insertValues[$id] = $bundleItem->getExtra('n' . $id);
            }

            $this->bundleItemTable->insert($insertValues);

            $biid = $this->bundleItemTable->getLastInsertValue();

            if (! (is_numeric($biid) && $biid > 0)) {
                throw new RuntimeException('Failed to save bundle item');
            }

            $bundleItem->add($id, $biid);

            $this->getEventManager()->trigger('save.insert', $bundleItem);
        }

        $this->getEventManager()->trigger('save', $bundleItem);

        return $bundleItem;
    }

    public function get($biid, $strict = true)
    {
        $bundleItems = $this->getBy(array('biid' => $biid));

        if (empty($bundleItems)) {
            if ($strict) {
                throw new RuntimeException('This bundle item does not exist');
            }

            return null;
        } else {
            return current($bundleItems);
        }
    }

    /**
     * Gets all bundle items that match the passed conditions.
     *
     * @param mixed $where              Any valid where conditions, but usually an array with key/value pairs.
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getBy($where, $order = null, $limit = null, $offset = null)
    {
        $select = $this->bundleItemTable->getSql()->select();

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

        $resultSet = $this->bundleItemTable->selectWith($select);

        return BundleItemFactory::fromResultSet($resultSet);
    }

    /**
     * Gets all bundle items.
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
     * Deletes a bundle item.
     *
     * @param BundleItem|int $bundleItem
     * @return int
     * @throws InvalidArgumentException
     */
    public function delete($bundleItem)
    {
        if ($bundleItem instanceof BundleItem) {
            $biid = $bundleItem->need( $bundleItem->getPrimary() );
        } else {
            $biid = $bundleItem;
        }

        if (! (is_numeric($biid) && $biid > 0)) {
            throw new InvalidArgumentException('Bundle item id must be numeric');
        }

        $bundleItem = $this->get($biid);

        $id = $bundleItem->getPrimary();

        $deletion = $this->bundleItemTable->delete(array($id => $biid));

        $this->getEventManager()->trigger('delete', $bundleItem);

        return $deletion;
    }

}