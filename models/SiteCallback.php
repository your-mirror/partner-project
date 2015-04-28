<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "site_callback".
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $type
 * @property string $value
 *
 * @property Sites $site
 */
class SiteCallback extends \yii\db\ActiveRecord
{
    const TYPE_FORM          = 0;
    const TYPE_SITE_CONTACT  = 1;
    const TYPE_OTHER_CONTACT = 2;

    private $_types = [
        self::TYPE_FORM          => 'Форма обратной связи',
        self::TYPE_SITE_CONTACT  => 'Контакты на сайте',
        self::TYPE_OTHER_CONTACT => 'Контакты на стороне'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_callback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'type'], 'required'],
            [['site_id', 'type'], 'integer'],
            [['value'], 'string'],
            ['type', 'default', 'value'=>self::TYPE_FORM],
            ['type', 'in', 'range' => array_keys($this->_types)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Site ID',
            'type' => 'Обратная связь',
            'value' => 'Содержимое',
        ];
    }

    public function getTypes()
    {
        return $this->_types;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Sites::className(), ['id' => 'site_id']);
    }
}
