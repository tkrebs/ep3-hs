<?php

namespace Room\Entity;

use Base\Entity\AbstractEntityFactory;

class RoomFactory extends AbstractEntityFactory
{

    protected static $entityClass = 'Room\Entity\Room';
    protected static $entityPrimary = 'rid';

}