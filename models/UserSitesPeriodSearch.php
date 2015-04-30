<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class UserSitesPeriodSearch extends Model
{
    public $username;
    public $sitesCount;
    public $beginDate;
    public $endDate;

    public function rules()
    {
        return [
            'fieldsSafe' => [['username', 'sitesCount', 'beginDate', 'endDate'], 'safe'],
            ['beginDate', 'default', 'value' => null],
            ['endDate', 'default', 'value' => null]
        ];
    }

    public function search($params)
    {
        $query = (new Query())
            ->select([
                '*',
                'COUNT(sites.domain) as sitesCount'
            ])
            ->from('user')
            ->leftJoin('sites', 'user.id=sites.author_id')
            ->groupBy('user.id');

        $dataProvider = new ActiveDataProvider( [
            'query' => $query,
            'totalCount'=>User::find()->count()
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'username',
                'sitesCount'
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->sitesCount !== null) {
            $query->having(['>=', 'sitesCount', $this->sitesCount]);
        }

        if ($this->sitesCount !== null) {
            $query->andFilterWhere(['like', 'username', $this->username]);
        }

        if($this->beginDate !== null) {
            $date = strtotime($this->beginDate);
            $query->andWhere(['>=', 'sites.created_at', $date]);
        }

        if($this->endDate !== null) {
            $date = strtotime($this->endDate);
            $query->andWhere(['<=', 'sites.created_at', $date + 3600 * 24]);
        }

        return $dataProvider;
    }
}