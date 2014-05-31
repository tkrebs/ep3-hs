<?php

namespace Booking\Entity;

use Base\Entity\AbstractEntity;

class Booking extends AbstractEntity
{

    protected $bid;
    protected $rid;
    protected $uid;
    protected $status;
    protected $date_arrival;
    protected $date_departure;
    protected $date_repeat;
    protected $quantity;
    protected $created;

    protected $primary = 'bid';

    /**
     * The possible status options.
     *
     * @var array
     */
    public static $statusOptions = array(
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
    );

    /**
     * Returns the status string.
     *
     * @return string
     */
    public function getStatus()
    {
        $status = $this->need('status');

        if (isset(self::$statusOptions[$status])) {
            return self::$statusOptions[$status];
        } else {
            return 'Unknown';
        }
    }

}