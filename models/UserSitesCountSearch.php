<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class UserSitesCountSearch extends Model
{
    public $username;
    public $sitesCount;
    public $sitesNewCount;
    public $sitesContactCount;
    public $sitesAnswerCount;
    public $sitesDenyCount;
    public $sitesAgreeCount;

    public function rules()
    {
        return [
            'fieldsSafe' => [
                [
                    'username',
                    'sitesCount',
                    'sitesNewCount',
                    'sitesContactCount',
                    'sitesAnswerCount',
                    'sitesDenyCount',
                    'sitesAgreeCount'
                ],
            'safe']
        ];
    }

    public function search($params)
    {
        $query = (new Query())
            ->select([
                '*',
                'user.id as userId',
                'COUNT(sites.domain) as sitesCount',
                'SUM(if(sites.status = '.    Sites::STATUS_NEW           .', 1, 0)) AS sitesNewCount',
                'SUM(if(sites.status = '.    Sites::STATUS_WAIT_CONTACT  .', 1, 0)) AS sitesContactCount',
                'SUM(if(sites.status = '.    Sites::STATUS_WAIT_ANSWER   .', 1, 0)) AS sitesAnswerCount',
                'SUM(if(sites.status = '.    Sites::STATUS_DENY          .', 1, 0)) AS sitesDenyCount',
                'SUM(if(sites.status = '.    Sites::STATUS_AGREE         .', 1, 0)) AS sitesAgreeCount'
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
            $query->andFilterWhere(['like', 'username', $this->username]);
        }

        $query->having(['>=', 'sitesCount', $this->sitesCount]);
        $query->andHaving(['>=', 'sitesNewCount', $this->sitesNewCount]);
        $query->andHaving(['>=', 'sitesContactCount', $this->sitesContactCount]);
        $query->andHaving(['>=', 'sitesAnswerCount', $this->sitesAnswerCount]);
        $query->andHaving(['>=', 'sitesDenyCount', $this->sitesDenyCount]);
        $query->andHaving(['>=', 'sitesAgreeCount', $this->sitesAgreeCount]);

        return $dataProvider;
    }
}