<?php

namespace Bill\Entity;

use Base\Entity\AbstractEntityFactory;

class BillFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Bill\Entity\Bill';
    protected static $entityPrimary = 'bid';

}