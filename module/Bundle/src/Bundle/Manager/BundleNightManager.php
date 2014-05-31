<?php

namespace Bundle\Manager;

use Base\Manager\AbstractManager;
use Bundle\Entity\Bundle;
use Bundle\Entity\BundleNight;
use Bundle\Entity\BundleNightFactory;
use Bundle\Table\BundleNightTable;
use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Sql\Where;

class BundleNightManager extends AbstractManager
{

    protected $bundleNightTable;

    /**
     * Creates a new manager object.
     *
     * @param BundleNightTable $bundleNightTable
     */
    public function __construct(BundleNightTable $bundleNightTable)
    {
        $this->bundleNightTable = $bundleNightTable;
    }

    /**
     * Saves (updates or creates) a bundle night.
     *
     * @param BundleNight $bundleNight
     * @return BundleNight
     * @throws RuntimeException
     */
    public function save(BundleNight $bundleNight)
    {
        $id = $bundleNight->getPrimary();

        if ($bundleNight->get($id)) {

            /* Update existing bundle night */

            /* Determine updated properties */

            $updates = array();

            foreach ($bundleNight->need('updatedProperties') as $property) {
                $updates[$property] = $bundleNight->get($property);
            }

            if ($updates) {
                $this->bundleNightTable->update($updates, array($id => $bundleNight->get($id)));
            }

            $bundleNight->reset();

            $this->getEventManager()->trigger('save.update', $bundleNight);

        } else {

            /* Insert bundle night */

            $insertValues = array(
                'bid' => $bundleNight->need('bid'),
                'nights_min' => $bundleNight->need('nights_min'),
                'nights_max' => $bundleNight->need('nights_max'),
                'price' => $bundleNight->need('price'),
                'price_fixed' => $bundleNight->need('price_fixed'),
                'rate' => $bundleNight->need('rate'),
                'gross' => $bundleNight->need('gross'),
            );

            if ($bundleNight->getExtra('n' . $id)) {
                $insertValues[$id] = $bundleNight->getExtra('n' . $id);
            }

            $this->bundleNightTable->insert($insertValues);

            $bnid = $this->bundleNightTable->getLastInsertValue();

            if (! (is_numeric($bnid) && $bnid > 0)) {
                throw new RuntimeException('Failed to save bundle night');
            }

            $bundleNight->add($id, $bnid);

            $this->getEventManager()->trigger('save.insert', $bundleNight);
        }

        $this->getEventManager()->trigger('save', $bundleNight);

        return $bundleNight;
    }

    public function get($bnid, $strict = true)
    {
        $bundleNights = $this->getBy(array('bnid' => $bnid));

        if (empty($bundleNights)) {
            if ($strict) {
                throw new RuntimeException('This bundle night does not exist');
            }

            return null;
        } else {
            return current($bundleNights);
        }
    }

    /**
     * Gets all bundle nights that match the passed conditions.
     *
     * @param mixed $where              Any valid where conditions, but usually an array with key/value pairs.
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getBy($where, $order = null, $limit = null, $offset = null)
    {
        $select = $this->bundleNightTable->getSql()->select();

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

        $resultSet = $this->bundleNightTable->selectWith($select);

        return BundleNightFactory::fromResultSet($resultSet);
    }

    /**
     * Gets the (one) cheapest bundle night rule for the passed amount of nights.
     *
     * @param Bundle $bundle
     * @param int $nights
     * @return BundleNight
     */
    public function getByNights(Bundle $bundle, $nights)
    {
        $where = new Where();

        $where->equalTo('bid', $bundle->need('bid'));
        $where->and;
        $where->lessThanOrEqualTo('nights_min', $nights);
        $where->and;
        $where->greaterThanOrEqualTo('nights_max', $nights);

        $nightRules = $this->getBy($where);
        $nightRuleCheapest = null;

        foreach ($nightRules as $nightRule) {
            if (is_null($nightRuleCheapest)) {
                $nightRuleCheapest = $nightRule;
            } else {
                if ($nightRule->need('price') < $nightRuleCheapest->need('price')) {
                    $nightRuleCheapest = $nightRule;
                }
            }
        }

        return $nightRuleCheapest;
    }

    /**
     * Gets all bundle nights.
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
     * Deletes a bundle night.
     *
     * @param BundleNight|int $bundleNight
     * @return int
     * @throws InvalidArgumentException
     */
    public function delete($bundleNight)
    {
        if ($bundleNight instanceof BundleNight) {
            $bnid = $bundleNight->need( $bundleNight->getPrimary() );
        } else {
            $bnid = $bundleNight;
        }

        if (! (is_numeric($bnid) && $bnid > 0)) {
            throw new InvalidArgumentException('Bundle night id must be numeric');
        }

        $bundleNight = $this->get($bnid);

        $id = $bundleNight->getPrimary();

        $deletion = $this->bundleNightTable->delete(array($id => $bnid));

        $this->getEventManager()->trigger('delete', $bundleNight);

        return $deletion;
    }

}