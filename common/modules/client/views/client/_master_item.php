<?php

use common\models\User;
use yii\helpers\Html;


/* @var $model User */
/* @var $key  User */
/* @var $index  User */
?>

<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
    <div class="card bg-light d-flex flex-fill">
        <div class="card-header text-muted border-bottom-0">
            <?= $model->getStatusUser($model->status); ?>

        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-7">
                    <h2 class="lead"><b> <?= $model->username; ?></b></h2>
                    <?=$model->getRoles('description') ?
                        '<p class="font-weight-light">('.implode(
                            ', ',
                            $model->getRoles('description')
                        ).')</p>' : '' ?>

                    <ul class="ml-4 mb-0 fa-ul text-muted">

                        <?php if ($model->address):?>
                            <li class="small mb-3">
                                <span class="fa-li">
                                    <i class="fas fa-lg fa-building"></i>
                                </span>
                                <?=$model->address  ;?>
                            </li>
                        <?php endif;?>

                        <?php if ($model->phone):?>
                            <li class="small mb-3">
                                <span class="fa-li">
                                    <i class="fas fa-lg fa-phone"></i>
                                </span>
                                <?=Html::a(
                                    $model->phone,
                                    'tel:'.$model->phone
                                ) ;?>
                            </li>
                        <?php endif;?>

                        <?php if ($model->birthday):?>
                            <li class="small mb-3">
                                <span class="fa-li">
                                    <i class="fas fa-birthday-cake"></i>
                                </span>
                                <?=Yii::$app->formatter->asDate(
                                    $model->birthday,
                                    'php:d-m-Y'
                                ) ;?>
                            </li>
                        <?php endif;?>

                        <?php if ($model->profile->color):?>
                            <li class="small mb-3">
                                <span class="fa-li">
                                    <i class="fas fa-paint-brush"></i>
                                </span>
                                <?php  $option = [
                                    'style' => [
                                        'width'            => '20px',
                                        'height'           => '20px',
                                        'border-radius'    => '20px',
                                        'background-color' => $model->profile->color
                                    ]
                                ];
                                echo Html::tag('div', '', $option); ;?>
                            </li>
                        <?php endif;?>

                    </ul>
                </div>
                <div class="col-5 text-center">
                    <img src="<?= $model->getPicture();?>" alt="<?=$model->username?>"
                         class="img-circle img-fluid">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-right">
                <?= Html::a('<i class="fas fa-user"></i> Подробнее...',
                    ['view', 'id' =>$model->id], ['class' => 'btn btn-sm btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
