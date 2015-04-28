<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sites */

?>
<div class="sites-create">
    <?= $this->render('_form', [
        'site' => $site,
        'siteCallback' => $siteCallback
    ]) ?>

</div>
