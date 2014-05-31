<?php

namespace Bill\Entity;

use Base\Entity\AbstractEntityFactory;

class BillNightFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Bill\Entity\BillNight';
    protected static $entityPrimary = 'bnid';

}