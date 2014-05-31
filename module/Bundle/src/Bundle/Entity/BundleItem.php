<?php

namespace Bundle\Entity;

use Base\Entity\AbstractEntity;

class BundleItem extends AbstractEntity
{

    protected $biid;
    protected $bid;
    protected $pid;
    protected $priority;
    protected $due;
    protected $amount_min;
    protected $amount_max;
    protected $price;
    protected $price_fixed;
    protected $rate;
    protected $gross;

    protected $primary = 'biid';

    /**
     * The possible due options.
     *
     * @var array
     */
    public static $dueOptions = array(
        'per_item' => 'per Unit',
        'per_night' => 'per Night',
    );

    /**
     * Returns the due string.
     *
     * @return string
     */
    public function getDue()
    {
        $due = $this->need('due');

        if (isset(self::$dueOptions[$due])) {
            return self::$dueOptions[$due];
        } else {
            return 'Unknown';
        }
    }

}