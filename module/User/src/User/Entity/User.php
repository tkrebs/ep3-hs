<?php

namespace User\Entity;

use Base\Entity\AbstractEntity;

class User extends AbstractEntity
{

    protected $uid;
    protected $alias;
    protected $status;
    protected $email;
    protected $pw;
    protected $login_attempts;
    protected $login_detent;
    protected $last_activity;
    protected $last_ip;
    protected $created;

    protected $primary = 'uid';

    /**
     * The possible status options.
     *
     * @var array
     */
    public static $statusOptions = array(
        'placeholder' => 'Placeholder',
        'guest' => 'Guest',
        'deleted' => 'Deleted user',
        'blocked' => 'Blocked user',
        'disabled' => 'Disabled user',
        'enabled' => 'Enabled user',
        'assist' => 'Assist',
        'admin' => 'Admin',
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

    /**
     * The possible gender options.
     *
     * @var array
     */
    public static $genderOptions = array(
        'male' => 'Mr.',
        'female' => 'Mrs',
    );

    /**
     * Returns the gender string.
     *
     * @return string
     */
    public function getGender($default = 'Unknown')
    {
        $gender = $this->getMeta('gender');

        if (is_null($gender)) {
            return $default;
        }

        if (isset(self::$genderOptions[$gender])) {
            return self::$genderOptions[$gender];
        } else {
            return $default;
        }
    }

    /**
     * The possible privileges.
     *
     * @var array
     */
    public static $privileges = array();

    /**
     * Access control for this user.
     *
     * @param string $privileges
     * @return boolean
     */
    public function can($privileges)
    {
        if ($this->need('status') == 'admin') {
            return true;
        }

        if ($this->need('status') == 'assist') {
            if (is_string($privileges)) {
                $orPrivileges = explode(',', $privileges);
                $orPrivilegesMatched = 0;

                foreach ($orPrivileges as $orPrivilege) {
                    $andPrivileges = explode('+', $orPrivilege);
                    $andPrivilegesMatched = 0;

                    foreach ($andPrivileges as $andPrivilege) {
                        $privilege = trim($andPrivilege);

                        if ($this->getMeta('allow.' . $privilege) == 'true') {
                            $andPrivilegesMatched++;
                        }
                    }

                    if ($andPrivilegesMatched == count($andPrivileges)) {
                        $orPrivilegesMatched++;
                    }
                }

                if ($orPrivilegesMatched >= 1) {
                    return true;
                }
            }
        }

        return false;
    }

}