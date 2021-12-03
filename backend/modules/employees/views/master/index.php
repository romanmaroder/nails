<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Мастера';
$this->params['breadcrumbs'][] = $this->title;

?>

<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="card card-solid">
		<div class="card-body pb-0">
			<div class="row">

                <?=
                ListView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'tag' => false,
                    ],
                    'layout' => "{pager}\n{items}\n{summary}", //TODO исправить шаблон отображения
                    'itemOptions' => ['tag' => null],
                    'itemView' => function ($model, $key, $index) {

                        return $this->render('_master_item',
                            [
                                'model' => $model,
                                'index' => $index,
                                'key' => $key
                            ]);

                        // or just do some echo
                        // return $model->title . ' posted by ' . $model->author;
                    },
                    'emptyText' => 'У вас нет сотрудников.',
                    'emptyTextOptions' => [
                        'tag' => 'div',
                        'class' => 'col-12 col-lg-6 mb-3 text-info text-center'
                    ],
                    'summary' => false,
                    #'summary' => 'Показаны записи {count} из {totalCount}',
                    'summaryOptions' => [
                        'tag' => 'div',
                        'class' => 'col-12 text-secondary'
                    ]
                ]);
                ?>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->

</section>
<!-- /.content -->


<div class="user-index">

	<!--<h3><?
    /*= Html::encode($this->title) */ ?></h3>-->

	<p>
        <?
        /*= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) */ ?>
	</p>

    <?php
    Pjax::begin(); ?>

    <?
    /*= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'options'      => [
                    'class' => 'table-responsive-md',
                ],
                'columns'      => [
    //            ['class' => 'yii\grid\SerialColumn'],

                    'username',
                    [
                        'attribute' => 'roles',
                        'value'     => function ($user) {
                            return implode(', ', $user->getRoles('description')) ? implode(', ', $user->getRoles('description')) : '---';
                        }
                    ],
                    [
                        'attribute' => 'color',
                        'format'    => 'raw',
                        'filter'    => false,
                        'value'     => function ($model) {
                            $option = [
                                'style' => [
                                    'width'            => '100px',
                                    'height'           => '20px',
                                    'border-radius'    => '20px',
                                    'background-color' => $model->color
                                ]
                            ];
                            return Html::tag('div', '', $option);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'format'=>'raw',
                        'value'     => function ($model) {
                            return $model->getStatusUser($model->status);
                        },
                    ],
                    'email:email',
                    [
                        'attribute' => 'created_at',
                        'format'    => ['date', 'dd/MM/Y']
                    ],
                    //'updated_at',
                    //'verification_token',

                    [
                        'class'          => 'yii\grid\ActionColumn',
                        'visibleButtons' => [
                            'delete' => Yii::$app->user->can('admin'),

                        ],
                    ],
                ],
            ]
        ); */ ?>

    <?php
    Pjax::end(); ?>

</div>
