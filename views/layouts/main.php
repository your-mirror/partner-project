<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Sites;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'CPA WAP',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $navItems = [];

    if (Yii::$app->user->isGuest) {
        array_push($navItems,
            ['label' => 'Войти', 'url' => ['/user/security/login']],
            ['label' => 'Зарегистрироваться', 'url' => ['/user/registration/register']]
        );
    } else {
        array_push($navItems,
            ['label' => 'Выйти (' . Yii::$app->user->identity->username . ')', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']]
        );
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $navItems,
    ]);
    NavBar::end();
    ?>
    <hr>
    <div class="container">
        <div class="col-md-2">
            <?php
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
        </div>
        <div class="col-md-10">
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
