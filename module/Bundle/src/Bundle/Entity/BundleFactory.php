<?php

namespace Bundle\Entity;

use Base\Entity\AbstractEntityFactory;

class BundleFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Bundle\Entity\Bundle';
    protected static $entityPrimary = 'bid';

}