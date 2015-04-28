<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Sites;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sites-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'layout'  => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'domain',
            'contacts:ntext',
            'comments:ntext',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statuses[$model->status];
                },
                'filter' => $siteModel->statuses
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d.m.Y, H:m', $model->created_at);
                },
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'created_at',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control'
                    ]
                ]),
            ],
            // 'updated_at',
            // 'author_id',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],

        ],
    ]); ?>

</div>
