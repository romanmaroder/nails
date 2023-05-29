<?php

/* @var $user AccountController */

/* @var $profile AccountController */

/* @var $setting AccountController */

/* @var $modelAvatar AvatarForm */

use common\models\User;
use common\modules\profile\controllers\AccountController;
use common\modules\profile\models\AvatarForm;
use dosamigos\fileupload\FileUpload;
use hail812\adminlte3\assets\PluginAsset;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;


PluginAsset::register($this)->add(['sweetalert2']);
?>
<p class="text-muted">Редактировать данные аккаунта</p>


<?php $theme = ActiveForm::begin(['layout' => 'horizontal']); ?>

<?= $theme->field($setting, 'checkbox')->checkbox(
    [
        'id' => 'checkTheme',
        'class' => 'check-theme-input',
        'template' => '<div class="col-6 col-sm-2 text-bold">Светлая/Темная</div><div class="col-6 col-sm-10 text-right"><label class="check-theme">{input}
<span class="check-theme-span"></span><i class="check-theme-indicator"></i></label></div>',
    ]
) ?>



<?php ActiveForm::end(); ?>




<?php $form = ActiveForm::begin(
    ['layout' => 'horizontal',]
); ?>



<?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

<?php if (Yii::$app->user->can('master')):?>

<?= $form->field($profile, 'notes')->textarea(['rows' => 4]) ?>
<?= $form->field($profile, 'education')->textarea(['rows' => 4]) ?>
<?= $form->field($profile, 'skill')->textarea(['rows' => 4]) ?>
<?php endif;?>

<?= $form->field($user, 'phone')->widget(
    MaskedInput::class,
    [
        'mask' => '+38(099) 999-99-99',
        'options' => [
            'class' => 'form-control',
            'id' => 'phone',
            'placeholder' => ('Телефон')
        ],
        'clientOptions' => [
            'greedy' => false,
            'clearIncomplete' => true
        ]
    ]
) ?>

<?= $form->field($user, 'address')->textInput(['maxlength' => true]) ?>
<?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
<?= $form->field($user, 'password')->input('password', ['maxlength' => true]) ?>

<?php
if ($user->getPicture() !== User::DEFAULT_IMAGE) : ?>
    <div class="form-group row">
        <div class="col-sm-2 col-form-label"></div>
        <div class="col-sm-10">
            <div class="wrap-button" id="delete-block">
                <img class="img-square" id="profile-picture-form" src="<?php
                echo $user->getPicture(); ?>" alt="">
                <span class=" slide-button">
					<i class="fas fa-trash"></i>
					<a id="delete-link" href="<?php
                    echo Url::to(['/profile/account/delete-picture']); ?>">
						<span class="slide-button-info">Удалить</span>
					</a>
				</span>
            </div>
        </div>
    </div>
<?php
endif; ?>


<div class="form-group row">
    <?= Html::label('Аватар', 'avatar', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= FileUpload::widget(
            [
                'model' => $modelAvatar,
                'attribute' => 'avatar',
                'url' => ['/profile/account/upload-avatar'],
                // your url, this is just for demo purposes,
                'options' => ['accept' => 'image/*'],
                'clientEvents' => [
                    'fileuploaddone' => 'function(e, data) {
                              					if (data.result.success) {
													$("#profile-picture, #profile-picture-form, .elevation-2").attr("src", data.result.pictureUri);
													$(function() {
															var Toast = Swal.mixin({
															  toast: true,
															  position: "top-end",
															  showConfirmButton: false,
															  timer: 5000,
															});
															  Toast.fire({
																icon: "success",
																title: data.result.message
															  });
													  })
												} else {
													   $(function() {
															var Toast = Swal.mixin({
															  toast: true,
															  position: "top-end",
															  showConfirmButton: false,
															  timer: 5000
															});
															  Toast.fire({
																icon: "error",
																title: data.result.errors.avatar
															  })
													  })
												}
                            				}',
                ],
            ]
        ); ?>
    </div>
</div>
<div class="form-group row">
    <div class="offset-sm-2 col-sm-10">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php
ActiveForm::end(); ?>


<?php
$js = <<< JS
$('#delete-link').on('click',function (e){
    e.preventDefault();
    var path = $(this).attr('href');
    $.ajax({
     	type: "POST",
    	url: path,
    	success: function(msg){ 
     	    
     	    var Toast = Swal.mixin({
						  toast: true,
						  position: "top-end",
						  showConfirmButton: false,
						  timer: 5000,
						  didOpen:()=>{
						      $('#delete-block').hide();
						      $("#profile-picture, #profile-picture-form, .elevation-2").attr("src", msg.pictureUri);
						  }
						});
						  Toast.fire({
							icon: "error",
							title: msg.message
						  });
						  
    	},
    	error: function (error){
     	    alert(error);
    	}
    })
});

$("#checkTheme").on('change',function(){

    
        if($(this).is(':checked')){
           
            $(this).closest('form').submit();
        }
        if ($(this).is(':not(:checked)')){
            $("#checkTheme").removeAttr('checked');
            $(this).closest('form').submit();
        }

    });

if ( $('body').hasClass('dark-mode') ){
        $("#checkTheme").attr('checked','checked');
       
};
    



JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);
?>

