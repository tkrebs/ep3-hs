<?php

namespace Product\Manager;

use Base\Entity\AbstractEntity;
use Base\Manager\AbstractEntityManager;
use InvalidArgumentException;
use Product\Entity\ProductFactory;
use RuntimeException;
use Traversable;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Where;

class ProductManager extends AbstractEntityManager
{

    protected function getInsertValues(AbstractEntity $entity)
    {
        return array(
            'status' => $entity->need('status'),
        );
    }

    protected function getByResultSet(Traversable $resultSet)
    {
        return ProductFactory::fromResultSet($resultSet);
    }

    protected function getByMetaResultSet(Traversable $metaResultSet, array $rooms)
    {
        return ProductFactory::fromMetaResultSet($rooms, $metaResultSet);
    }

    public function get($pid, $strict = true)
    {
        $products = $this->getBy(array('pid' => $pid));

        if (empty($products)) {
            if ($strict) {
                throw new RuntimeException('This product does not exist');
            }

            return null;
        } else {
            return current($products);
        }
    }

    public function getByBillItems(array $billItems)
    {
        return $this->getByBundleItems($billItems);
    }

    public function getByBundleItems(array $bundleItems)
    {
        $pids = array();

        foreach ($bundleItems as $bundleItem) {
            $pid = $bundleItem->get('pid');

            if ($pid) {
                $pids[] = $pid;
            }
        }

        if (! $pids) {
            return array();
        }

        $products = $this->getBy(array(new In('pid', $pids)));

        foreach ($bundleItems as $bundleItem) {
            $pid = $bundleItem->get('pid');

            if (isset($products[$pid])) {
                $bundleItem->setExtra('product', $products[$pid]);
            }
        }

        return $products;
    }

    public function interpret($input, $limit = null)
    {
        if (! (is_numeric($input) || is_string($input))) {
            throw new InvalidArgumentException('Product interpretation requires either numeric or string input');
        }

        if (! is_numeric($input) && is_string($input) && strlen($input) < 3) {
            return array();
        }

        if (is_numeric($input)) {
            $product = $this->get($input, false);

            if ($product) {
                return array($product->need('pid') => $product);
            } else {
                return array();
            }
        } else {
            $where = new Where();
            $where->equalTo('key', 'name');
            $where->like('value', '%' . $input . '%');

            $select = $this->entityMetaTable->getSql()->select();
            $select->where($where);

            if ($limit) {
                $select->limit($limit);
            }

            $pids = array();

            $resultSet = $this->entityMetaTable->selectWith($select);

            foreach ($resultSet as $resultRecord) {
                $pid = $resultRecord->pid;

                if (! in_array($pid, $pids)) {
                    $pids[] = $pid;
                }
            }

            if (! $pids) {
                return array();
            }

            return $this->getBy(array(new In('pid', $pids)));
        }
    }

}