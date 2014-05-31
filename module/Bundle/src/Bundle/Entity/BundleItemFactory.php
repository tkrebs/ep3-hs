<?php

namespace Bundle\Entity;

use Base\Entity\AbstractEntityFactory;

class BundleItemFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Bundle\Entity\BundleItem';
    protected static $entityPrimary = 'biid';

}