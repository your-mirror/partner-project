<?php

/* 
 * This file is part of the Dektrium project
 * 
 * (c) Dektrium project <http://github.com/dektrium>
 * 
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

?>

<?php
if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin') {
    $navItems = [
        ['label' => 'Менеджеры', 'url' => ['/managers/default/index']],
        ['label' => 'Статистика по статусам', 'url' => ['/site/statistic/count']],
        ['label' => 'Стайтистика за период', 'url' => ['/site/statistic/period']]
    ];
    echo Nav::widget([
        'items' => $navItems,
        'options' => ['class' =>'nav nav-pills'],
    ]);
    echo '<hr>';
}

if(!Yii::$app->user->isGuest) {
    $navItems = [
        ['label' => 'Добавить сайт', 'url' => ['/site/default/create']],
        ['label' => 'Список всех сайтов', 'url' => ['/site/default/index']],
        ['label' => 'В ожидании контактов', 'url' => ['/site/default/contact']],
        ['label' => 'В ожидании ответа', 'url' => ['/site/default/answer']],
        ['label' => 'Отказанные', 'url' => ['/site/default/deny']],
        ['label' => 'Согласные', 'url' => ['/site/default/agree']],
    ];
    echo Nav::widget([
        'items' => $navItems,
        'options' => ['class' =>'nav nav-pills'],
    ]);
}
?>
