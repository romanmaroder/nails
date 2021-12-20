<?php
/* @var $this yii\web\View */

/* @var $dataProvider SiteController */

/* @var $path SiteController */


use frontend\controllers\SiteController;
use yii\widgets\ListView;



?>
<div class="row mt-4">

    <?= ListView::widget(
        [
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => false,
            ],
            'layout' => "{pager}\n{items}",
            'itemOptions' => ['tag' => null],
            'itemView' => '_about_master',
            'viewParams' => ['path' => $path],
            'emptyText' => 'У нас пока нет сотрудников.',
            'emptyTextOptions' => [
                'tag' => 'div',
                'class' => 'col-12 col-lg-6 mb-3 text-info text-center'
            ],
        ]
    );
    ?>

    <!-- /.col -->
</div>