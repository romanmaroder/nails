<?php

use common\models\User;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;

//use hail812\adminlte3\assets\FontAwesomeAsset;
//use common\assets\AdminLteAsset;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile \common\modules\profile\controllers\AccountController */
/* @var $user \common\modules\profile\controllers\AccountController */
/* @var $modelAvatar\common\modules\profile\models\AvatarForm */
/* @var $modelPhoto \common\modules\profile\controllers\AccountController */
/* @var $model\common\modules\profile\controllers\AccountController */
/* @var $modelCertificate\common\modules\profile\controllers\AccountController */
/* @var $certificateList\common\modules\profile\controllers\AccountController */


//FontAwesomeAsset::register($this);
//AdminLteAsset::register($this);
//$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);


$this->title                   = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- Main content -->

<div class="container-fluid">
	<div class="row justify-content-end">
		<div class="col-md-3">
			<!-- Profile Image -->
			<div class="card card-primary card-outline">
				<div class="card-body box-profile">
					<div class="text-center">
						<img class="profile-user-img img-fluid img-circle"
							 src="<?php
                             echo $user->getPicture(); ?>"
							 id="profile-picture"
							 alt="User profile picture"/>
					</div>

					<h3 class="profile-username text-center"><?php
                        echo $user->username; ?></h3>
                    <?php
                    if (Yii::$app->user->can('master')) : ?>
						<p class="text-muted text-center">
                            <?php
                            echo User::getRole()->description ?>
						</p>
                        <?php
                        if (Yii::$app->user->can('manager') && Yii::$app->id !== 'app-backend') : ?>
							<p class="text-muted text-center">
                                <?php
                                echo Html::a(
                                    '<i class="fas fa-user-cog"></i> Админка',
                                    ['/admin/profile/account/'],
                                    ['class' => 'btn btn-primary btn-sm']
                                ); ?>
							</p>
                        <?php
                        endif; ?>
                    <?php
                    endif; ?>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->

			<!-- About Me Box -->
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">О себе</h3>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
                    <?php
                    if ($profile->education) : ?>
						<strong><i class="fas fa-book mr-1"></i> Образование</strong>
						<p class="text-muted">
                            <?php
                            echo $profile->education; ?>
						</p>
						<hr>
                    <?php
                    endif; ?>
                    <?php
                    if ($user->address) : ?>


						<strong><i class="fas fa-map-marker-alt mr-1"></i> Место проживание</strong>

						<p class="text-muted"><?php
                            echo $user->address; ?></p>

                    <?php
                    endif; ?>
                    <?php
                    if ($profile->skill) : ?>
						<hr>

						<strong><i class="fas fa-pencil-alt mr-1"></i> Навыки и умения</strong>

						<p class="text-muted">
								<span class="tag tag-info"><?php
                                    echo $profile->skill; ?></span>
						</p>
                    <?php
                    endif; ?>
                    <?php
                    if ($profile->notes) : ?>
						<hr>
						<strong><i class="far fa-file-alt mr-1"></i> Заметки</strong>
						<p class="text-muted"><?php
                            echo $profile->notes; ?></p>
                    <?php
                    endif; ?>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col -->
		<div class="col-md-9">
			<div class="card card-outline card-primary">
				<div class="card-header p-2">
					<ul class="nav nav-pills">
						<li class="nav-item">
							<a class="nav-link active"
							   href="#events"
							   data-toggle="tab">Записи
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link"
							   href="#settings"
							   data-toggle="tab">Настройки
							</a>
						</li>
                        <?php
                        if (Yii::$app->user->can('perm_view-calendar')) : ?>
							<li class="nav-item">
								<a class="nav-link"
								   href="#upload"
								   data-toggle="tab">Добавить фото
								</a>
							</li>
                        <?php
                        endif; ?>
                        <?php
						 if (Yii::$app->user->can('perm_view-calendar')) : ?>
							<li class="nav-item">
								<a class="nav-link"
								   href="#certificate"
								   data-toggle="tab">Добавить сертификат
								</a>
							</li>
                        <?php
                        endif; ?>
						<li class="nav-item">
							<a class="nav-link"
							   href="#design"
							   data-toggle="tab">Дизайн
							</a>
						</li>
                        <?php
                        if (Yii::$app->user->can('perm_view-calendar')) : ?>
							<li class="nav-item">
								<a class="nav-link"
								   href="#certificateList"
								   data-toggle="tab">Сертификаты
								</a>
							</li>
                        <?php
                        endif; ?>
                        <?php
                        if (Yii::$app->user->can('perm_create-post')) : ?>
							<li class="nav-item">
								<?php echo Html::a('Статьи',['/blog/post/index'],['class'=>'nav-link',
																				   'data-toggle'=>''])	;?>
							</li>
                        <?php
                        endif; ?>
					</ul>
				</div><!-- /.card-header -->
				<div class="card-body">
					<div class="tab-content">
						<div class="active tab-pane" id="events">

							<!-- /.card-header -->
                            <?php
                            if ($dataProvider->getCount() === 0) {
                                echo 'У вас нет записей';
                            } else {
                                echo GridView::widget(
                                    [
                                        'dataProvider' => $dataProvider,
                                        'summary'      => "",
                                        'filterModel'  => null,

                                        'tableOptions' => [
                                            'class' => 'table table-bordered table-hover',
                                            'id'    => 'eventsList',
                                        ],
                                        'options'      => [
                                            'class' => 'table-responsive',
                                        ],
                                        'columns'      => [
                                            //['class' => 'yii\grid\SerialColumn'],
                                            [
                                                'attribute' => 'client_id',
                                                'format'    => 'raw',
                                                'visible'   => Yii::$app->user->can('perm_view-calendar'),
                                                'value'     => function ($client) {
                                                    return Html::a(
                                                        $client['client']['username'],
                                                        ['/client/client/view', 'id' => $client->client->id]
                                                    );
                                                }
                                            ],
                                            [
                                                'attribute' => 'master_id',
                                                'format'    => 'raw',
                                                'visible'   => Yii::$app->user->can('user'),
                                                'value'     => function ($master) {
                                                    return $master['master']['username'];
                                                }
                                            ],
                                            [
                                                'attribute'      => 'description',
                                                'contentOptions' => ['style' => 'white-space: nowrap;'],
                                            ],
                                            [
                                                'attribute'      => 'event_time_start',
                                                'contentOptions' => ['style' => 'white-space: nowrap;'],
                                                'label'          => 'Дата',
                                                'format'         => ['date', 'php:d-m-Y'],
                                            ],
                                            [
                                                'attribute' => 'event_time_start',
                                                'label'     => 'Время',
                                                'format'    => ['date', 'php:H:i'],
                                            ],
                                            //'notice',
                                            [
                                                'class'    => 'yii\grid\ActionColumn',
                                                'template' => '{view}'
                                            ],
                                        ],
                                    ]
                                );
                            } ?>
							<!-- /.card-body -->
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="settings">
                            <?= $this->render(
                                '_form-profile',
                                [
                                    'user'        => $user,
                                    'profile'     => $profile,
                                    'modelAvatar' => $modelAvatar
                                ]
                            ) ?>
						</div>
						<!-- /.tab-pane -->
                        <?php
                        if (Yii::$app->user->can('perm_view-calendar')) : ?>
							<div class="tab-pane" id="upload">

                                <?= $this->render(
                                    '_create-photo-form',
                                    [
                                        'modelPhoto' => $modelPhoto,
                                    ]
                                ) ?>
								<!-- /.card-body -->
							</div>
                        <?php
                        endif; ?>
						<!-- /.tab-pane -->
                        <?php
                        if (Yii::$app->user->can('perm_view-calendar')) : ?>
							<div class="tab-pane" id="certificate">

                                <?= $this->render(
                                    '_create-certificate',
                                    [
                                        'modelCertificate' => $modelCertificate,
                                    ]
                                ) ?>
								<!-- /.card-body -->
							</div>
                        <?php
                        endif; ?>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="design">
                            <?= $this->render(
                                '_gallery-tab',
                                [
                                    'model' => $model,
                                ]
                            ) ?>
						</div>
						<!-- /.tab-pane -->
                        <?php
                        if (Yii::$app->user->can('perm_view-calendar')) : ?>
							<div class="tab-pane" id="certificateList">

                                <?= $this->render(
                                    '_view-certificate',
                                    [
                                        'certificateList' => $certificateList,
                                    ]
                                ) ?>
								<!-- /.card-body -->
							</div>
                        <?php
                        endif; ?>
						<!-- /.tab-pane -->
                        <?php
/*                        if (Yii::$app->user->can('perm_create-post')) : */?><!--
							<div class="tab-pane" id="post">-->

								<!-- /.card-body -->
							<!--</div>
                        --><?php
/*                        endif; */?>
						<!-- /.tab-pane -->
					</div>
				</div>
				<!-- /.tab-content -->
			</div><!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
	<!-- /.col -->
</div>

<!-- /.content -->

<?php
$js = <<< JS
$(function () {
    $('#eventsList').DataTable({
    "bStateSave": true,
    "fnStateSave": function (oSettings, oData) {
        localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
    },
    "fnStateLoad": function () {
        var data = localStorage.getItem('DataTables_' + window.location.pathname);
        return JSON.parse(data);
    },
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "pageLength": 15,
      "info": false,
      "autoWidth": false,
      "responsive": true,
       "language": {
           "search": "Поиск:",
           "zeroRecords": "Совпадений не найдено",
    	  	"emptyTable": "В таблице отсутствуют данные",
           "paginate": {
                    "first": "Первая",
                    "previous": '<i class="fas fa-backward"></i>',
                    "last": "Последняя",
                    "next": '<i class="fas fa-forward"></i>'
                }
       }
    });
  });
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);
?>


