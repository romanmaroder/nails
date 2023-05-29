<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title                   = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'мастера', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>


<!-- Main content -->
<section class="content">

	<!-- Default box -->
	<div class="card card-solid">
		<div class="card-body pb-0">
			<div class="row">
				<div class="col-12 col-sm-6 d-flex align-items-stretch flex-column">
					<div class="card bg-light d-flex flex-fill">
						<div class="card-header text-muted border-bottom-0">
                            <?php echo  $model->getStatusUser($model->status);?>
						</div>
						<div class="card-body pt-0">
							<div class="row">
								<div class="col-7">
									<h2 class="lead"><b><?php echo $model->username ;?></b></h2>
                                    <?php echo $model->getRoles('description') ?
                                        '<p class="font-weight-light">('.implode(', ',$model->getRoles('description')
                                        ).')</p>':''?>
									<p class="text-muted text-sm"><b>Обо мне: </b> <?php echo $model->description ;?>
									</p>
									<ul class="ml-4 mb-0 fa-ul text-muted">
										<li class="small mb-3">
											<span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                            <?php echo $model->address ? $model->address : 'бомж';?>
										</li>
										<li class="small mb-3">
											<span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                            <?php echo $model->phone ? Html::a ($model->phone,'tel:'.$model->phone): 'нет номера' ;?>
										</li>
										<li class="small mb-3"><span class="fa-li"><i class="fas
										fa-birthday-cake"></i></span>
                                            <?php echo $model->birthday
                                                ? Yii::$app->formatter->asDate($model->birthday,'php:d-m-Y'):'еще не родился' ;?>
										</li>
										<li class="small"><span class="fa-li"><i
														class="fas fa-paint-brush"></i></span>
                                            <?php
                                            $option = [
                                                'style' => [
                                                    'width'            => '20px',
                                                    'height'           => '20px',
                                                    'border-radius'    => '20px',
                                                    'background-color' => $model->color
                                                ]
                                            ];
                                            echo Html::tag('div', '', $option); ?>
										</li>
									</ul>
								</div>
								<div class="col-5 text-center">
									<img src="<?php
                                    echo $model->getPicture(); ?>" alt="user-avatar <?php echo
                                    $model->username ;?>"
										 class="img-circle img-fluid">
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="text-right">
								<!--<a href="#" class="btn btn-sm bg-teal">
									<i class="fas fa-comments"></i>
								</a>-->
                                <?= Html::a(
                                    '<i class="fas fa-trash"></i> Удалить',
                                    ['delete', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-sm btn-danger',
                                        'data'  => [
                                            'confirm' => 'Удалить этого мастера?',
                                            'method'  => 'post',
                                        ],
                                    ]
                                ) ?>
                                <?= Html::a('<i class="fas fa-user"></i> Редактировать',
                                            ['update', 'id' =>$model->id], ['class' => 'btn btn-sm btn-primary']) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->

</section>
<!-- /.content -->

