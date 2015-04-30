<?php
namespace app\modules\managers\models;

use dektrium\user\Finder;
use dektrium\user\models\UserSearch;
use yii\data\ActiveDataProvider;

class ManagerSearch extends UserSearch
{
    public function search($params)
    {
        $query = $this->finder->getUserQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}