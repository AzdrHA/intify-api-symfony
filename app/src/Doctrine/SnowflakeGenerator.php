<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Exception;
use Godruoyi\Snowflake\Snowflake;

class SnowflakeGenerator extends AbstractIdGenerator
{
    /**
     * @var int
     *
     * @description: static as simple cache to prevent collisions
     */
    static private int $lastTimeStamp = -1;

    /**
     * @var int
     *
     * @description: static as simple cache to prevent collisions
     */
    static private int $sequence = 0;

    public function generateId(EntityManagerInterface $em, $entity)
    {
        $snowflake = new Snowflake();
        $snowflake->setSequenceResolver(function ($currentTime) {
            if (self::$lastTimeStamp === $currentTime) {
                self::$sequence++;
                self::$lastTimeStamp = $currentTime;

                return self::$sequence;
            }

            self::$sequence = 0;
            self::$lastTimeStamp = $currentTime;
            return self::$sequence;
        });

        try {
            $snowflake->setStartTimeStamp(strtotime('2020-05-24') * 1000);
        } catch (Exception $exception) {
            // this will not happen, since this code is static
        }

        return ($snowflake->id());
    }

}