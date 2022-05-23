<?php
/* @var $model SiteController */
/* @var $path SiteController */


use frontend\controllers\SiteController;

?>

<div class="col-md-4">
    <!-- Widget: user widget style 1 -->
    <div class="card card-widget widget-user">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header text-white"
             style="background: url(<?php

             use yii\helpers\Html;

             echo $path; ?>) center center;">
            <h3 class="widget-user-username text-right"><?php
                echo $model->username; ?></h3>
            <h5 class="widget-user-desc text-right">
                <?php
                echo /*$model->getRole()->description*/  implode(' ', $model->getRoles('description')); ?>
            </h5>

        </div>
        <?php
        echo Html::a(
            '<img class="img-circle" src="'.$model->getPicture().'" alt="User Avatar" title="User Avatar">',
            ['/site/view', 'id' => $model->id],
            ['class' => 'widget-user-image']
        ); ?>

        <div class="card-footer">
            <div class="row justify-content-center">
                <?php
                if ($model->getCountCertificate($model->id)) : ?>

                    <div class="col-sm-4 ">
                        <div class="description-block ">
                            <h5 class="description-header"><?php
                                echo $model->getCountCertificate($model->id); ?></h5>
                            <span class="description-text">СЕРТИФИКАТЫ</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                <?php else: ?>
                    <div class="col-sm-4 ">
                        <div class="description-block ">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">СЕРТИФИКАТЫ</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                <?php
                endif; ?>
                <!-- /.col -->
                <?php
                if ($model->getCountWorkMaster($model->id)) : ?>

                    <div class="col-sm-4 ">
                        <div class="description-block">
                            <h5 class="description-header"><?php
                                echo $model->getCountWorkMaster($model->id); ?></h5>
                            <span class="description-text">РАБОТ</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                <?php else: ?>
                    <div class="col-sm-4 ">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">РАБОТ</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                <?php
                endif; ?>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
    <!-- /.widget-user -->
</div>