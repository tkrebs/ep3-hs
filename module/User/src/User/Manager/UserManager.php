<?php

namespace User\Manager;

use Base\Entity\AbstractEntity;
use Base\Manager\AbstractEntityManager;
use InvalidArgumentException;
use RuntimeException;
use Traversable;
use User\Entity\User;
use User\Entity\UserFactory;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Where;

class UserManager extends AbstractEntityManager
{

    /**
     * Creates a new user.
     *
     * @param string $alias
     * @param string $status
     * @param string $email
     * @param string $pw
     * @param array $meta
     * @return User
     */
    public function create($alias, $status = 'placeholder', $email = null, $pw = null, array $meta = array())
    {
        if (! (is_string($alias) && strlen($alias) >= 3)) {
            throw new InvalidArgumentException('User name too short');
        }

        $bcrypt = new Bcrypt();
        $bcrypt->setCost(6);

        $user = new User(array(
            'alias' => $alias,
            'status' => $status,
            'email' => $email,
            'pw' => $bcrypt->create($pw),
        ), $meta);

        $this->save($user);

        $this->getEventManager()->trigger('create', $user);

        return $user;
    }

    protected function getInsertValues(AbstractEntity $entity)
    {
        $created = date('Y-m-d H:i:s');

        return array(
            'alias' => $entity->need('alias'),
            'status' => $entity->need('status'),
            'email' => $entity->get('email'),
            'pw' => $entity->get('pw'),
            'login_attempts' => $entity->get('login_attempts'),
            'login_detent' => $entity->get('login_detent'),
            'last_activity' => $entity->get('last_activity'),
            'last_ip' => $entity->get('last_ip'),
            'created' => $entity->get('created', $created),
        );
    }

    protected function getByResultSet(Traversable $resultSet)
    {
        return UserFactory::fromResultSet($resultSet);
    }

    protected function getByMetaResultSet(Traversable $metaResultSet, array $users)
    {
        return UserFactory::fromMetaResultSet($users, $metaResultSet);
    }

    /**
     * Gets the user by primary id.
     *
     * @param int $uid
     * @param boolean $strict
     * @return User
     * @throws RuntimeException
     */
    public function get($uid, $strict = true)
    {
        $users = $this->getBy(array('uid' => $uid));

        if (empty($users)) {
            if ($strict) {
                throw new RuntimeException('This user does not exist');
            }

            return null;
        } else {
            return current($users);
        }
    }

    /**
     * Gets regular users, that is, users that have not been created during booking session only.
     *
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @param boolean $loadMeta
     * @return array
     */
    public function getRegular($order = null, $limit = null, $offset = null, $loadMeta = true)
    {
        $where = new Where();
        $where->notEqualTo('status', 'placeholder');
        $where->notEqualTo('status', 'guest');

        return $this->getBy($where, $order, $limit, $offset, $loadMeta);
    }

    /**
     * Interprets the input to return matching users.
     *
     * @param int|string $input     Any input for interpretation;
     *                              numeric or at least three chars long
     * @param int $limit            Maximum number of users to return
     * @param boolean $loadMeta     Whether to also load meta data
     * @param array $where          Additional where clauses
     * @return array                An array of matching user objects;
     *                              empty if invalid input or no results
     * @throws RuntimeException
     */
    public function interpret($input, $limit = null, $loadMeta = false, array $where = array())
    {
        if (! (is_numeric($input) || is_string($input))) {
            throw new InvalidArgumentException('User interpretation requires either numeric or string input');
        }

        if (! is_numeric($input) && is_string($input) && strlen($input) < 3) {
            return array();
        }

        if (is_numeric($input)) {
            $user = $this->get($input, false);

            if ($user) {
                return array($user->need('uid') => $user);
            } else {
                return array();
            }
        } else {
            return $this->getBy(array_merge(array(new Like('alias', '%' . $input . '%')), $where), 'alias ASC', $limit, null, $loadMeta);
        }
    }

    public function getByBills(array $bills)
    {
        $uids = array();

        foreach ($bills as $bill) {
            $uid = $bill->get('user');

            if ($uid) {
                $uids[] = $uid;
            }
        }

        if (! $uids) {
            return array();
        }

        $users = $this->getBy(array(new In('uid', $uids)));

        foreach ($bills as $bill) {
            $uid = $bill->get('user');

            if (isset($users[$uid])) {
                $bill->setExtra('user', $users[$uid]);
            }
        }

        return $users;
    }

    public function getByBookings(array $bookings)
    {
        $uids = array();

        foreach ($bookings as $booking) {
            $uid = $booking->get('uid');

            if ($uid) {
                $uids[] = $uid;
            }
        }

        if (! $uids) {
            return array();
        }

        $users = $this->getBy(array(new In('uid', $uids)));

        foreach ($bookings as $booking) {
            $uid = $booking->get('uid');

            if (isset($users[$uid])) {
                $booking->setExtra('user', $users[$uid]);
            }
        }

        return $users;
    }

}