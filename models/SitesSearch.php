<?php
namespace app\models;

use Yii;
use app\models\Sites;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SitesSearch extends Model
{
    public $domain;
    public $created_at;
    public $status;

    public function rules()
    {
        return [
            'fieldsSafe' => [['status', 'domain', 'created_at'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null]
        ];
    }

    public function search($params)
    {
        $query = Sites::find();

        if(Yii::$app->user->identity->role != 'admin')
            $query = $query->where(['author_id' => Yii::$app->user->identity->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=>[
                    'created_at'=>SORT_DESC
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->status !== null) {
            $query->andFilterWhere(['status'=>$this->status]);
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'domain', $this->domain]);

        return $dataProvider;
    }
}