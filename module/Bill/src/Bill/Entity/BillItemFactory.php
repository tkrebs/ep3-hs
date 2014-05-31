<?php

namespace Bill\Entity;

use Base\Entity\AbstractEntityFactory;

class BillItemFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Bill\Entity\BillItem';
    protected static $entityPrimary = 'biid';

}