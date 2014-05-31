<?php

namespace Bill\Entity;

use Base\Entity\AbstractEntity;

class BillItem extends AbstractEntity
{

    protected $biid;
    protected $bid;
    protected $pid;
    protected $pid_name;
    protected $priority;
    protected $amount;
    protected $price;
    protected $rate;
    protected $gross;

    protected $primary = 'biid';

}