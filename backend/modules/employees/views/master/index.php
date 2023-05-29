<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Мастера';
$this->params['breadcrumbs'][] = $this->title;
//$assetDir                      = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');


?>

<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="card card-solid">
		<div class="card-body pb-0">
			<div class="row">
                <?php
                foreach ($dataProvider->getModels() as $item) : ?>

					<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
						<div class="card bg-light d-flex flex-fill">
							<div class="card-header text-muted border-bottom-0">
                                <?php
                                echo $item->getStatusUser($item->status); ?>

							</div>
							<div class="card-body pt-0">
								<div class="row">
									<div class="col-7">
										<h2 class="lead"><b> <?php
                                                echo $item->username; ?></b></h2>
                                        <?php
                                        echo $item->getRoles('description') ?
                                            '<p class="font-weight-light">('.implode(
                                                ', ',
                                                $item->getRoles('description')
                                            ).')</p>' : '' ?>
										<!--<p class="text-muted text-sm"><b>Обо мне: </b> <?php
/*                                            echo $item->description; */?></p>-->
										<ul class="ml-4 mb-0 fa-ul text-muted">
											<li class="small mb-3"><span class="fa-li"><i class="fas fa-lg
										fa-building"></i></span> <?php
                                                echo $item->address ? $item->address : 'бомж'; ?></li>
											<li class="small mb-3"><span class="fa-li"><i class="fas fa-lg
										fa-phone"></i></span> <?php
                                                echo $item->phone ? Html::a(
                                                    $item->phone,
                                                    'tel:'.$item->phone
                                                ) : 'нет номера'; ?></li>
											<li class="small mb-3"><span class="fa-li"><i class="fas
										fa-birthday-cake"></i></span>
                                                <?php
                                                echo $item->birthday
                                                    ? Yii::$app->formatter->asDate(
                                                        $item->birthday,
                                                        'php:d-m-Y'
                                                    ) : 'еще не родился'; ?>
											</li>
											<li class="small"><span class="fa-li"><i
															class="fas fa-paint-brush"></i></span>
                                                <?php
                                                $option = [
                                                    'style' => [
                                                        'width'            => '20px',
                                                        'height'           => '20px',
                                                        'border-radius'    => '20px',
                                                        'background-color' => $item->color
                                                    ]
                                                ];
                                                echo Html::tag('div', '', $option); ?>
											</li>
										</ul>
									</div>
									<div class="col-5 text-center">
										<!--<img src="<?/*= $assetDir */?>/img/avatar2.png" alt="user-avatar"
											 class="img-circle img-fluid"-->
										<img src="<?php
                                        echo $item->getPicture(); ?>" alt="user-avatar"
											 class="img-circle img-fluid">
									</div>
								</div>
							</div>
							<div class="card-footer">
								<div class="text-right">
                                    <?= Html::a('<i class="fas fa-user"></i> Подробнее...',
                                                ['view', 'id' =>$item->id], ['class' => 'btn btn-sm btn-primary']) ?>
								</div>
							</div>
						</div>
					</div>
                <?php
                endforeach;; ?>
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
