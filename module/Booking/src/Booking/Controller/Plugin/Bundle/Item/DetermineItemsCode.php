<?php

namespace Booking\Controller\Plugin\Bundle\Item;

use Bundle\Entity\Bundle;
use Bundle\Manager\BundleItemManager;
use RuntimeException;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineItemsCode extends AbstractPlugin
{

    protected $bundleItemManager;

    public function __construct(BundleItemManager $bundleItemManager)
    {
        $this->bundleItemManager = $bundleItemManager;
    }

    public function __invoke(Bundle $bundle, $post = true)
    {
        $bundleItemCodes = array();

        $bundleItems = $this->bundleItemManager->getBy(array('bid' => $bundle->need('bid')), 'priority DESC');

        foreach ($bundleItems as $biid => $bundleItem) {
            $id = sprintf('bf-bi-%s',
                $biid);

            if ($post) {
                $amount = $this->getController()->params()->fromPost($id);
            } else {
                $amount = $this->getController()->params()->fromQuery($id);
            }

            if (! is_numeric($amount)) {
                throw new RuntimeException('Invalid choice of additional booking products');
            }

            if ($amount < $bundleItem->need('amount_min')) {
                throw new RuntimeException('Invalid choice of additional booking products');
            }

            if ($amount > $bundleItem->need('amount_max')) {
                throw new RuntimeException('Invalid choice of additional booking products');
            }

            $bundleItem->setExtra('amount', $amount);

            $bundleItemCodes[] = sprintf('%sx%s',
                $amount, $biid);
        }

        $bundle->setExtra('items', $bundleItems);

        return implode('-', $bundleItemCodes);
    }

}