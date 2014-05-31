<?php

namespace Product\Entity;

use Base\Entity\AbstractEntity;

class Product extends AbstractEntity
{

    protected $pid;
    protected $status;

    protected $primary = 'pid';

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