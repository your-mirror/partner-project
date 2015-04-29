<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\User as User;

/**
 * This is the model class for table "sites".
 *
 * @property integer $id
 * @property string $domain
 * @property string $contacts
 * @property string $comments
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 *
 * @property SiteCallback[] $siteCallbacks
 */
class Sites extends \yii\db\ActiveRecord
{
    const STATUS_NEW            = 0;
    const STATUS_WAIT_CONTACT   = 1;
    const STATUS_WAIT_ANSWER    = 2;
    const STATUS_DENY           = 3;
    const STATUS_AGREE          = 4;

    private $_statuses = [
        self::STATUS_NEW            => 'Новый',
        self::STATUS_WAIT_CONTACT  => 'В ожидании контактов',
        self::STATUS_WAIT_ANSWER    => 'В ожидании ответа',
        self::STATUS_DENY           => 'Отказался',
        self::STATUS_AGREE          => 'Согласился',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'author_id'], 'required'],
            [['contacts', 'comments'], 'string'],
            [['status', 'created_at', 'updated_at', 'author_id'], 'integer'],
            [['domain'], 'string', 'max' => 255],
            ['domain', 'unique'],
            ['status', 'in', 'range' => array_keys($this->_statuses)],
        ];
    }

    public function behaviors()
    {
        return [
            'user_id' => [
                'class' => 'app\behaviors\UserIdBehavior',
                'userIdAttribute' => 'author_id'
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Сайт',
            'contacts' => 'Контакты',
            'comments' => 'Комментарий',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'author_id' => 'Автор',
            'siteCallback' => 'Обратная связь'
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->domain = str_ireplace('www.', '', parse_url($this->domain, PHP_URL_HOST));
            return true;
        }
        return false;
    }

    public function getStatuses()
    {
        return $this->_statuses;
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function getSiteCallbacks()
    {
        return $this->hasMany(SiteCallback::className(), ['site_id' => 'id']);
    }

    public function getSiteCallback()
    {
        return $this->hasOne(SiteCallback::className(), ['site_id' => 'id']);
    }
}
