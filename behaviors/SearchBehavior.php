<?php
/**
 * @link https://github.com/gromver/yii2-platform-basic.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-platform-basic/blob/master/LICENSE
 * @package yii2-platform-basic
 * @version 1.0.0
 */

namespace gromver\platform\basic\behaviors;

use gromver\modulequery\ModuleEvent;
use gromver\platform\basic\interfaces\ModelSearchableInterface;
use gromver\platform\basic\interfaces\ViewableInterface;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class SearchBehavior
 * @package yii2-platform-basic
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class SearchBehavior extends Behavior {
    const EVENT_INDEX_PAGE = 'SearchBehavior_IndexPage';
    const EVENT_DELETE_PAGE = 'SearchBehavior_DeletePage';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'searchIndexPage',
            ActiveRecord::EVENT_AFTER_UPDATE => 'searchIndexPage',
            ActiveRecord::EVENT_AFTER_DELETE => 'searchDeletePage',
        ];
    }

    /**
     * @param \yii\base\Component $owner
     * @throws InvalidConfigException
     */
    public function attach($owner)
    {
        parent::attach($owner);

        if (!$this->owner instanceof ViewableInterface) {
            throw new InvalidConfigException(__CLASS__ . '::owner must be an instance of \gromver\platform\basic\interfaces\ViewableInterface');
        }

        if (!$this->owner instanceof ModelSearchableInterface) {
            throw new InvalidConfigException(__CLASS__ . '::owner must be an instance of \gromver\platform\basic\interfaces\ModelSearchableInterface');
        }
    }

    /**
     * @param $event \yii\base\Event
     */
    public function searchIndexPage($event)
    {
        ModuleEvent::trigger(self::EVENT_INDEX_PAGE, [$this->owner]);
    }

    /**
     * @param $event \yii\base\Event
     */
    public function searchDeletePage($event)
    {
        ModuleEvent::trigger(self::EVENT_DELETE_PAGE, [$this->owner]);
    }
} 