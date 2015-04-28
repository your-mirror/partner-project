<?php
namespace app\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class UserIdBehavior extends AttributeBehavior
{
    public $userIdAttribute = 'user_id';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'setUserId'
        ];
    }

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->userIdAttribute]
            ];
        }
    }

    public function setUserId()
    {
        if ( empty( $this->owner->{$this->userIdAttribute} ) ) {
            $this->owner->{$this->userIdAttribute} = \Yii::$app->user->getId();
        }
    }
}