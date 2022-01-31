<?php

use common\models\Service;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
                                        'action' => [''],
                                        'method' => 'get',
                                        'options' => [
                                            'data-pjax' => 1
                                        ],
                                    ]); ?>

    <?/*= $form->field($model, 'id') */?>

    <?/*= $form->field($model, 'client_id') */?>

    <?= $form->field($model, 'master_id')->dropDownList(\common\models\User::getMasterList(),
                                                        ['prompt' => 'Выберите мастера...',['class'=>'form-control form-control-sm']]) ?>

    <?/*= $form->field($model, 'service') */?>
    <?= $form->field($model, 'service')->widget(
        Select2::class,
        [
            'language' => 'ru',
            'data'          => Service::getServiceList(),
            'theme'         => Select2::THEME_MATERIAL,
            'options'       => [
                'placeholder'  => 'Выберите услугу ...',
                'multiple'     => true,
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'tags'            =>  true,
                'allowClear'      => true,
            ],
        ]
    )->label('Услуги') ?>


    <?= $form->field($model, 'date_from')->widget(
        DatePicker::class,
        [
            'model'         => $model,
            'attribute'     => 'date_from',
            'attribute2'    => 'date_to',
            'type'          => kartik\date\DatePicker::TYPE_RANGE,
            'size'          => 'sm',
            'separator'     => 'по',
            'options'=>[
                    'autocomplete'=>'off',
            ],
            'pluginOptions' => [
                'todayHighlight' => true,
                'weekStart'      => 1, //неделя начинается с понедельника
                'autoclose'      => true,
                'orientation'    => 'bottom auto',
                'clearBtn'       => true,
                'todayBtn'       => 'linked',
                'format'         => 'yyyy-mm-dd',
                ]
        ]
    )->label('Дата') ?>


    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-sm btn-primary']) ?>
        <?/*= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) */?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
