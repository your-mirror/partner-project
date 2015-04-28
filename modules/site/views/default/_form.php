<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sites-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($site, 'domain')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($site, 'contacts')->textarea(['rows' => 6]) ?>

    <?= $form->field($site, 'comments')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($siteCallback, 'type')->dropDownList($siteCallback->types) ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($siteCallback, 'value', ['options'=>['class'=>'field-sitecallback-div']])->textInput() ?>
            <?php
                /*foreach($siteCallback->types as $key=>$type) {
                    if ($key == 0) {
                        echo $form->field($siteCallback, 'value[' . $key . ']', ['options'=>['class'=>'field-sitecallback-div']])->textInput();
                    } else {
                        echo $form->field($siteCallback, 'value[' . $key . ']', ['options'=>['class'=>'field-sitecallback-div']])->hiddenInput()->label(false);
                    }
                }*/
            ?>
        </div>
    </div>

    <?= $form->field($site, 'status')->dropDownList($site->statuses) ?>

    <div class="form-group text-right">
        <?= Html::submitButton($site->isNewRecord ? 'Создать' : 'Обновить', ['class' => $site->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*$script = "
        $('select#sitecallback-type').on('click', function(e) {
            e.preventDefault();
            var select = $(this);
            $('div.field-sitecallback-div input').attr('type', 'hidden');
            $('div.field-sitecallback-value-'+select.val()+' input').attr('type', 'text');
        });
    ";
$this->registerJs($script, $this::POS_READY);*/
?>