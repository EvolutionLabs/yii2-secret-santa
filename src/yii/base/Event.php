<?php declare(strict_types=1);

namespace evo\ssanta\yii\base;

use yii\base\Event as BaseEvent;

/**
 * Class Event
 * @package evo\ssanta\yii\base
 */
class Event extends BaseEvent
{
    public $params = [];
}
