<?php

namespace Bundle\Entity;

use Base\Entity\AbstractEntity;

class BundleNight extends AbstractEntity
{

    protected $bnid;
    protected $bid;
    protected $nights_min;
    protected $nights_max;
    protected $price;
    protected $price_fixed;
    protected $rate;
    protected $gross;

    protected $primary = 'bnid';

}