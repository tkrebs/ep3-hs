<?php

namespace Booking\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BookingExceptionTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingExceptionTable(BookingExceptionTable::NAME, $sm->get('Zend\Db\Adapter\Adapter'));
    }

}