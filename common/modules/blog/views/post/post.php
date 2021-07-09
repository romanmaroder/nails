<?php

/* @var $post \common\modules\blog\controllers\PostController */

use common\widgets\newsList\NewsList;

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

    </div>
</div>

