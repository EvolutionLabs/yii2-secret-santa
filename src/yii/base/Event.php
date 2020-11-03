<?php declare(strict_types=1);

namespace evolutionlabs\ssanta\yii\base;

use yii\base\Event as BaseEvent;

/**
 * Class Event
 * @package evolutionlabs\ssanta\yii\base
 */
class Event extends BaseEvent
{
    public $params = [];
}
