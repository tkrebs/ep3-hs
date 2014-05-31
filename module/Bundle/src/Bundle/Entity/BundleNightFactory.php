<?php

namespace Bundle\Entity;

use Base\Entity\AbstractEntityFactory;

class BundleNightFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Bundle\Entity\BundleNight';
    protected static $entityPrimary = 'bnid';

}