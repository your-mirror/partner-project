<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Sites;
use app\models\SiteCallback;
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
                'attribute' => 'siteCallback',
                'format' => 'html',
                'value' => function ($model) {
                    if(isset($model->siteCallback))
                        return '<p class="text-center"><span class="label label-success">'.$model->siteCallback->types[$model->siteCallback->type] .'</span> <br/>'. $model->siteCallback->value . '</p>';

                    $modelSiteCallback = new SiteCallback();
                    return '<p class="text-center"><span class="label label-success">'.$modelSiteCallback->types[0].'</span>';
                }
            ],
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
