<?php

namespace Product\Entity;

use Base\Entity\AbstractEntityFactory;

class ProductFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Product\Entity\Product';
    protected static $entityPrimary = 'pid';

}