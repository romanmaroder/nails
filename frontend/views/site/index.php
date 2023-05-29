<?php

/* @var $this yii\web\View */

/* @var $dataProvider  SiteController */

/* @var $searchModel  SiteController */

use common\widgets\buttonUp\ButtonUp;
use common\widgets\newsList\NewsList;
use frontend\controllers\SiteController;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<?php
Pjax::begin(
    [
        'enablePushState' => false,
    ]
); ?>

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
                        ListView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'options' => [
                                    'tag' => false,
                                    #'class' => 'col-12 col-lg-6 mb-3 list-wrapper',
                                    #'#id' => 'list-wrapper',
                                ],
                                'layout' => "{pager}\n{items}\n{summary}",
                                'itemOptions' => ['tag' => null],
                                'itemView' => function ($model, $key, $index) {
                                    return $this->render(
                                        '_post_item',
                                        [
                                            'model' => $model,
                                            'index' => $index,
                                            'key' => $key
                                        ]
                                    );

                                    // or just do some echo
                                    // return $model->title . ' posted by ' . $model->author;
                                },
                                'emptyText' => 'Увы, но статей пока нет.',
                                'emptyTextOptions' => [
                                    'tag' => 'div',
                                    'class' => 'col-12 col-lg-6 mb-3 text-info text-center'
                                ],
                                'summary' => 'Показаны записи {count} из {totalCount}',
                                'summaryOptions' => [
                                    'tag' => 'div',
                                    'class' => 'col-12 text-secondary'
                                ]
                            ]
                        );
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
<?php
echo ButtonUp::widget(); ?>
<?php

$js = <<< JS

 $(function () {
     $(document).on('change','#filterPost', function(event) {
     $('form[pjax-container]').submit();
    
    });
   
 })

JS;
$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null); ?>