<?php

use common\models\Service;
use common\models\User;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArchiveSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="archive-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],

    ]); ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        User::getMasterList(),
        ['prompt' => 'Выберите мастера...','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'service_id')->widget(
        Select2::class,
        [
            'language' => 'ru',
            'data'          => Service::getServiceList(),
            'theme'         => Select2::THEME_MATERIAL,
            'size'          => Select2::SMALL,
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
    ) ?>

    <?/*= $form->field($model, 'date')->input('text',['class'=>'form-control form-control-sm']) */?>
    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'options' => [
                'placeholder' => 'Выберите дату ...',
                'autocomplete'=>'off',
            ],
        'size' => 'sm',
        'pluginOptions' => [
            'todayHighlight' => true,
            'weekStart'      => 1, //неделя начинается с понедельника
            'autoclose'      => true,
            'orientation'    => 'bottom auto',
            'clearBtn'       => true,
            'todayBtn'       => 'linked',
            'format' => 'mm-yyyy'
        ]
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-sm btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
