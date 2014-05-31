<?php

namespace Bill\Entity;

use Base\Entity\AbstractEntity;

class BillNight extends AbstractEntity
{

    protected $bnid;
    protected $bid;
    protected $rid;
    protected $date_arrival;
    protected $date_departure;
    protected $date_repeat;
    protected $quantity;
    protected $price;
    protected $rate;
    protected $gross;

    protected $primary = 'bnid';

}