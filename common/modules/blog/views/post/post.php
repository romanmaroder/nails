<?php

/* @var $post \common\modules\blog\controllers\PostController */

use common\widgets\newsList\NewsList;
use yii\helpers\Html;

$this->title = $post->title;
?>

<div class="row mt-3">
    <div class="col-md-3 d-none d-md-block">
        <?php echo NewsList::widget(['showLimit' => 8]) ;?>
    </div>
    <div class="col-md-9">
		<article class="post__inner">
			<h1 class="post__title"> <?php echo $post->title ;?></h1>
			<div class="post__description"><?php echo $post->description ;?></div>
		</article>
		<?php echo Html::a('Назад',Yii::$app->request->referrer,['class'=>'btn btn-outline-info btn-sm mt-3']) ;?>
    </div>
</div>

