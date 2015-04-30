<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Sites;
use app\models\SiteCallback;
use dosamigos\datepicker\DateRangePicker;
use app\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sites-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'layout'  => "{items}\n{pager}",
        'options' => ['class' => 'text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'username',
                'filter' => ArrayHelper::map(User::find()->all(), 'username', 'username'),
                'contentOptions'=>['style'=>'min-width: 150px;']
            ],
            [
                'attribute' => 'sitesCount',
                'header'    => 'Число сайтов'
            ],
            [
                'header' => 'Период поиска',
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return '';
                },
                'filter' => DateRangePicker::widget([
                    'language'  => 'ru',
                    'model'         => $searchModel,
                    'labelTo'       => 'до',
                    'attribute'     => 'beginDate',
                    'attributeTo'   => 'endDate',
                    'options'       => [
                        'class' => 'form-control'
                    ]
                ]),
            ],
        ]
    ]); ?>

</div>
