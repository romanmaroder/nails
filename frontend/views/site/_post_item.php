<?php

use frontend\controllers\SiteController;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model SiteController */
/* @var $key SiteController */
/* @var $index SiteController */

?>

<?php
if ($index % 2 !== 0) {
    $class = 'alt';
} else {
    $class = '';
} ?>

    <div class="col-12 col-lg-6 mb-3">
        <div class="blog-card mx-auto <?php echo $class; ?>"
             id="<?php
             echo $model->id; ?>">
            <div class="meta">
                <div class="photo"
                     style="background-image: url(<?php
                     echo Yii::$app->storage->getFile($model->preview) ?>)"></div>
                <ul class="details">
                    <li class="author">
                        <?php
                        echo Html::a(
                            $model->user->username,
                            ['/site/view', 'id' => $model->user_id],
                        ); ?>

                    </li>
                    <li class="tags">
                        <i class="fas fa-tag  mr-2"></i>
                        <?= $model->category->category_name; ?>
                    </li>

                    <li class="date"><?php
                        echo date('d/m/y', $model->created_at); ?></li>
                </ul>
            </div>
            <div class="description">
                <h1><?php
                    echo Html::a($model->title, [Url::to(['/blog/post/post','slug'=>$model->slug,'category'=>$model->category->slug])]);
                    ?></h1>
                <h2><?php
                    echo $model->subtitle; ?></h2>
                <p></p>
                <!--<div class="truncate-text no-img"> <?php
                /*echo $post['subtitle'] ;*/ ?></div>-->
                <p class="read-more">
                    <?php
                    echo Html::a(
                        ' Читать <i class="fas fa-arrow-right"></i>',
                        ['/blog/post/post', 'slug' => $model->slug]
                    );
                    ?>
                </p>
            </div>
        </div>
    </div>
