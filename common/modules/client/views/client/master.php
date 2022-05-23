<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мастера';
$this->params['breadcrumbs'][] = $this->title;


?>
<?php
Pjax::begin(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- Default box -->
            <div class="card card-solid">
                <div class="card-body pb-0">
                    <?php if (Yii::$app->session->hasFlash('danger')): ?>
                        <div class="alert alert-danger alert-dismissible mt-3" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('danger'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">

                        <?=
                        ListView::widget(
                            [
                                'dataProvider'     => $dataProvider,
                                'options'          => [
                                    'tag' => false,
                                ],
                                'layout'           => "{pager}\n{items}",
                                'itemOptions'      => ['tag' => null],
                                'itemView'         => function ($model, $key, $index) {
                                    return $this->render(
                                        '_master_item',
                                        [
                                            'model' => $model,
                                            'index' => $index,
                                            'key'   => $key
                                        ]
                                    );
                                },
                                'emptyText'        => 'У вас нет сотрудников.',
                                'emptyTextOptions' => [
                                    'tag'   => 'div',
                                    'class' => 'col-12 col-lg-6 mb-3 text-info text-center'
                                ],
                            ]
                        );
                        ?>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
<?php
Pjax::end(); ?>
