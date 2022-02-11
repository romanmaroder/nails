<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\widgets\metric\Counter;
use common\widgets\xmas\Xmas;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
    <?php $this->registerLinkTag(['rel' => 'apple-touch-icon', 'size' => '180x180', 'href' => Url::to(['/favicon/apple-touch-icon.png'])]);?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png','size' => '32x32', 'href' => Url::to(['/favicon/favicon-32x32.png'])]);?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png','size' => '16x16', 'href' => Url::to(['/favicon/favicon-16x16.png'])]);?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png','size' => '192x192', 'href' => Url::to(['/favicon/android-chrome-192x192.png'])]);?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png','size' => '512x512', 'href' => Url::to(['/favicon/android-chrome-512x512.png'])]);?>
    <?php $this->registerLinkTag(['rel' => 'manifest', 'href' => Url::to(['/favicon/site.webmanifest'])]);?>
    <?php $this->registerLinkTag(['rel' => 'mask-icon','color'=>'#5bbad5', 'href' => Url::to(['/favicon/safari-pinned-tab.svg'])]);?>
    <?php $this->registerLinkTag(['rel' => 'icon','size' => '120x120','type' => 'image/svg+xml', 'href' => Url::to(['/favicon/favicon-120x120.svg'])]);?>
    <?php $this->registerLinkTag(['rel' => 'shortcut icon', 'href' => Url::to(['/favicon/favicon.ico'])]);?>
    <?php $this->registerLinkTag(['name' => 'msapplication-TileColor','content'=>'#2d89ef']);?>
    <?php $this->registerLinkTag(['name' => 'msapplication-TileImage','content'=>'/favicon/mstile-144x144.png']);?>
    <?php $this->registerLinkTag(['name' => 'msapplication-config','content'=>'/favicon/browserconfig.xml']);?>
    <?php $this->registerLinkTag(['name' => 'theme-color','content'=>'#ffffff']);?>
    <?php $this->head() ?>

</head>
<body class="<?php if(Yii::$app->request->cookies->has('theme')){echo Yii::$app->request->cookies->getValue('theme','');};?>">
<?php
$this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin(
        [
            'brandLabel'            => Yii::$app->name,
            'brandUrl'              => Yii::$app->homeUrl,
            'innerContainerOptions' => ['class' => 'container-fluid'],
            'options'               => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark justify-content-between fixed-top',
            ],

        ]
    );
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
    ];
    if (yii::$app->user->can('perm_view-calendar')) {
        $menuItems[] = ['label' => 'Календарь', 'url' => ['/calendar/event/index']];
        $menuItems[] = ['label' => 'Клиенты', 'url' => ['/client/client/index']];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'Портфолио', 'url' => ['/site/portfolio']];
        $menuItems[] = ['label' => 'О нас', 'url' => ['/site/about']];
        $menuItems[] = [
            'label' => '<i class="fa fa-user" aria-hidden="true"></i> '.Yii::$app->user->identity->username,
            'url'   => ['/profile/account'],
			'options'=>[
                'title'          => 'Личный кабинет',
                'data-toggle'    => 'tooltip',
                'data-placement' => 'auto',
            ],
            'active' => in_array(\Yii::$app->controller->module->id, ['profile']),
        ];

        $menuItems[] = '<li>'
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(
                'Выйти',
                ['class' => 'btn nav-link']
            )
            .Html::endForm()
            .'</li>';
    }
    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav navbar-right ml-auto'],
            'encodeLabels' => false,
            'items'   => $menuItems,
        ]
    );
    NavBar::end();
    ?>

	<div class="container-fluid ">

        <?= Breadcrumbs::widget(
            [
                'itemTemplate'       => "\n\t<li class=\"breadcrumb-item\">{link}</li>\n",
                // template for all links
                'activeItemTemplate'=>"<!--noindex--><li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n<!--/noindex-->",
                // template for the active link
                'links'              => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'homeLink'           => ['label' => 'Главная', 'url' => ['/site/index']],
            ]
        ) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <span class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></span>
            </div>
        </div>


		<!--<p class="pull-right"><?/*= Yii::powered() */?></p>-->
	</div>
</footer>
<!-- Metrika counter -->
<?php echo Counter::widget();?>
<!-- /Yandex.Metrika counter -->

<?php
$this->endBody() ?>

</body>
</html>
<?php
$this->endPage() ?>
