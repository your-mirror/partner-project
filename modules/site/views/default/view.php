<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */
?>
<div class="sites-view">
    <?= DetailView::widget([
        'model' => $site,
        'attributes' => [
            'id',
            'domain',
            'contacts:ntext',
            'comments:ntext',
            'status',
            'created_at',
            'updated_at',
            'author_id',
        ],
    ]) ?>


    <p class="text-right">
        <?= Html::a('Обновить', ['update', 'id' => $site->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $site->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
