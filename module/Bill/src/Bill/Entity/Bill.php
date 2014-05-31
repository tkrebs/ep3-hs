<?php

namespace Bill\Entity;

use Base\Entity\AbstractEntity;

class Bill extends AbstractEntity
{

    protected $bid;
    protected $bnr;
    protected $status;
    protected $booking;
    protected $bundle;
    protected $bundle_name;
    protected $user;
    protected $created;

    protected $primary = 'bid';

    /**
     * The possible status options.
     *
     * @var array
     */
    public static $statusOptions = array(
        'pending' => 'Pending',
        'paid' => 'Paid',
        'uncollectable' => 'Uncollectable',
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

    public function getTotal()
    {
        $billItems = $this->getExtra('items', []);
        $billNights = $this->getExtra('nights', []);

        $total = 0;

        foreach ($billItems as $billItem) {
            $total += $billItem->get('price', 0);
        }

        foreach ($billNights as $billNight) {
            $total += $billNight->get('price', 0);
        }

        return $total;
    }

}