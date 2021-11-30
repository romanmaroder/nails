<?php

/* @var $this yii\web\View */

/* @var $dataProvider  \frontend\controllers\SiteController */

/* @var $searchModel  \frontend\controllers\SiteController */

use common\models\User;
use common\widgets\newsList\NewsList;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<?php
Pjax::begin(); ?>

    <div class="site-index">
        <div class="body-content">
            <div class="row">
                <div class="col-xl-2">
                    <?php
                    echo $this->render('@common/modules/blog/views/post/_search', ['model' => $searchModel]);
                    ?>
                </div>
                <div class="col-xl-8" id="pjax-container">
                    <div class="row ">
                        <?=
                        ListView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => [
                                'tag' => false,
                                #'class' => 'col-12 col-lg-6 mb-3 list-wrapper',
                                #'#id' => 'list-wrapper',
                            ],
                            'layout' => "{pager}\n{items}\n{summary}", //TODO исправить шаблон отображения
                            'itemOptions' => ['tag' => null],
                            'itemView' => function ($model, $key, $index, $widget) {

                                return $this->render('_post_item',
                                    [
                                        'model' => $model,
                                        'index' => $index,
                                        'key' => $key
                                    ]);

                                // or just do some echo
                                // return $model->title . ' posted by ' . $model->author;
                            },
                        ]);
                        ?>

                    </div>

                </div>
                <div class="d-none d-xl-block col-xl-2">
                    <?php
                    echo NewsList::widget(['showLimit' => 3]); ?>
                </div>
            </div>

        </div>
    </div>
<?php
Pjax::end(); ?>