<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\editable\Editable;
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
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'domain',
                'hAlign'=>'center',
                'vAlign'=>'middle',
            ],
            [
                'attribute' => 'contacts',
                'class' => 'kartik\grid\EditableColumn',
                'editableOptions'=> [
                    'inputType' => Editable::INPUT_TEXTAREA,
                    'submitOnEnter' => false
                ],
                'hAlign'=>'center',
                'vAlign'=>'middle',
            ],
            [
                'attribute' => 'comments',
                'class' => 'kartik\grid\EditableColumn',
                'editableOptions'=> [
                    'inputType' => Editable::INPUT_TEXTAREA,
                    'submitOnEnter' => false
                ],
                'hAlign'=>'center',
                'vAlign'=>'middle',
            ],
            /*[
                'attribute' => 'siteCallback',
                'format' => 'html',
                'value' => function ($model) {
                    if(isset($model->siteCallback))
                        return '<p class="text-center"><span class="label label-success">'.$model->siteCallback->types[$model->siteCallback->type] .'</span> <br/>'. $model->siteCallback->value . '</p>';

                    $modelSiteCallback = new SiteCallback();
                    return '<p class="text-center"><span class="label label-success">'.$modelSiteCallback->types[0].'</span>';
                }
            ],*/
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'siteCallbackValue',
                'editableOptions'=> function ($model, $key, $index) {
                    $label = '';

                    //define label
                    switch ($model->siteCallback->type) {
                        case SiteCallback::TYPE_FORM:
                            $label = 'label-success';
                            break;
                        case SiteCallback::TYPE_SITE_CONTACT:
                            $label = 'label-info';
                            break;
                        case SiteCallback::TYPE_OTHER_CONTACT:
                            $label = 'label-danger';
                            break;
                    }

                    return [
                        'displayValue' => '<p class="text-center"><span class="label '.$label.'">'.$model->siteCallback->types[$model->siteCallback->type] .'</span></p>',
                        'inputType' => Editable::INPUT_TEXTAREA,
                        'submitOnEnter' => false
                    ];
                },
                'hAlign'=>'center',
                'vAlign'=>'middle',
            ],
            [
                'attribute' => 'status',
                'class' => 'kartik\grid\EditableColumn',
                'editableOptions' => [
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'data' => $siteModel->statuses
                ],
                'hAlign'=>'center',
                'vAlign'=>'middle',
                'value' => function ($model) {
                    return $model->statuses[$model->status];
                },
                'filter' => $siteModel->statuses
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d.m.Y, H:i', $model->created_at);
                },
                'hAlign'=>'center',
                'vAlign'=>'middle',
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'created_at',
                    'dateFormat' => 'php:d-m-Y',
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
