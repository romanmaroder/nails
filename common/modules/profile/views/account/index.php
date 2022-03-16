<?php

use common\models\User;
use common\modules\profile\controllers\AccountController;
use common\modules\profile\models\AvatarForm;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\bootstrap4\Tabs;
use yii\helpers\Url;
use yii\widgets\Pjax;

//use hail812\adminlte3\assets\FontAwesomeAsset;
//use common\assets\AdminLteAsset;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile AccountController */
/* @var $setting AccountController */
/* @var $user AccountController */
/* @var $modelAvatar AvatarForm */
/* @var $modelPhoto AccountController */
/* @var $modelTodo AccountController */
/* @var $model AccountController */
/* @var $modelCertificate AccountController */
/* @var $certificateList AccountController */


//FontAwesomeAsset::register($this);
//AdminLteAsset::register($this);
//$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');


PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);


$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- Main content -->
<section class="content">
<div class="container-fluid">
    <div class="row justify-content-end">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="<?php echo $user->getPicture(); ?>"
                             id="profile-picture"
                             alt="User profile picture"/>
                    </div>

                    <h3 class="profile-username text-center"><?php
                        echo $user->username; ?></h3>
                    <?php
                    if (Yii::$app->authManager->getAssignment('master',$user->id)) : ?>
                        <p class="text-muted text-center">
                            <?php echo User::getRole()->description ?>
                        </p>
                    <?php endif; ?>
                        <?php
                        if (Yii::$app->authManager->getAssignment('manager',$user->id) && Yii::$app->id !== 'app-backend') : ?>
                            <p class="text-muted text-center">
                                <?php echo Html::a(
                                    '<i class="fas fa-user-cog"></i> Админка',
                                    ['/admin/profile/account/'],
                                    ['class' => 'btn btn-primary btn-sm']
                                ); ?>
                            </p>

                    <?php endif; ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">О себе</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                        </button>
                    </div>
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
            <div class="card card-outline card-primary ">
                <div class="card-header ">
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <?php if( Yii::$app->session->hasFlash('success') ): ?>
                        <div class="alert alert-success alert-dismissible mt-3" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('success'); ?>
                        </div>
                    <?php endif;?>

                    <div class="tab-content">
                        <?php
                        echo Tabs::widget(
                            [
                                'options' => ['class' => 'mb-3'],
                                'items' => [
                                    [
                                        'label' => 'Записи',
                                        'content' => $this->render('_event-list-tab', ['dataProvider' => $dataProvider]),
                                        //'active' => true, // указывает на активность вкладки
                                        'options' => ['id' => 'events'],

                                    ],
                                    [
                                        'label' => 'Настройки',
                                        'content' => $this->render(
                                            '_form-profile-tab',
                                            [
                                                'user' => $user,
                                                'profile' => $profile,
                                                'setting' => $setting,
                                                'modelAvatar' => $modelAvatar
                                            ]
                                        ),
                                        'options' => ['id' => 'settings'],
//                                        'headerOptions' => [
//                                            'id' => ''
//                                        ]
                                    ],
                                    [
                                        'label' => 'Добавить фото',
                                        'content' => $this->render(
                                            '_create-photo-form-tab',
                                            [
                                                'modelPhoto' => $modelPhoto,
                                            ]
                                        ),
                                        'options' => ['id' => 'upload'],
                                        'visible' => Yii::$app->user->can('perm_view-calendar'),
                                    ],
                                    [
                                        'label' => 'Добавить сертификат',
                                        'content' => $this->render(
                                            '_create-certificate-tab',
                                            [
                                                'modelCertificate' => $modelCertificate,
                                            ]
                                        ),
                                        'options' => ['id' => 'certificate'],
                                        'visible' => Yii::$app->user->can('perm_view-calendar'),
                                    ],
                                    [
                                        'label' => 'Дизайн',
                                        'content' => $this->render(
                                            '_gallery-tab',
                                            [
                                                'model' => $model,
                                            ]
                                        ),
                                        'options' => ['id' => 'design'],
                                        'visible' => true,
                                    ],
                                    [
                                        'label' => 'Сертификаты',
                                        'content' => $this->render(
                                            '_view-certificate-tab',
                                            [
                                                'certificateList' => $certificateList,
                                            ]
                                        ),
                                        'options' => ['id' => 'certificateList'],
                                        'visible' => Yii::$app->user->can('perm_view-calendar'),
                                    ],
                                    [
                                        'label' => 'Статьи',
                                        'url' => Url::toRoute(['/blog/post/index']),
                                        'options' => ['id' => 'post'],
                                        'headerOptions' => [
                                            'data-pjax'=>'0'
                                    ],
                                        'visible' => Yii::$app->user->can('perm_create-post'),
                                    ],
                                    [
                                        'label' => 'Заметки',
                                        'url' => Url::toRoute(['/todo/todo/index']),
                                        'options' => ['id' => 'todo'],
                                        'visible' => Yii::$app->id === 'app-frontend' ?? false,
                                    ],
                                ]
                            ]
                        );
                        ?>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
</section>
<!-- /.content -->



