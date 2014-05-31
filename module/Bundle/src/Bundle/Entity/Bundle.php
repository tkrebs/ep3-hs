<?php

namespace Bundle\Entity;

use Base\Entity\AbstractEntity;

class Bundle extends AbstractEntity
{

    protected $bid;
    protected $rid;
    protected $rid_group;
    protected $status;
    protected $code;
    protected $priority;
    protected $date_start;
    protected $date_end;
    protected $date_repeat;

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