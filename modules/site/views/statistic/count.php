<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Sites;
use app\models\SiteCallback;
use yii\jui\DatePicker;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Button;
use yii\helpers\Url;

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
                'attribute' => 'sitesNewCount',
                'header'    => 'Новые'
            ],
            [
                'attribute' => 'sitesContactCount',
                'header'    => 'Ожидают контактов'
            ],
            [
                'attribute' => 'sitesAnswerCount',
                'header'    => 'Ожидают ответа'
            ],
            [
                'attribute' => 'sitesDenyCount',
                'header'    => 'Отказались'
            ],
            [
                'attribute' => 'sitesAgreeCount',
                'header'    => 'Согласились'
            ],
            [
                'label'=>'Войти под менеджером',
                'format' => 'html',
                'attribute' => 'userId',
                'value' => function($model) {
                    $userId = $model['userId'];
                    return Html::a('Войти', ['/managers/default/superlogin', 'id' => $userId], ['class'=>'btn btn-danger']);
                },
                'options' => ['class'=>'text-center']
            ],
        ]
    ]); ?>

</div>
