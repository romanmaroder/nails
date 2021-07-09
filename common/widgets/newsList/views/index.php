<?php
/* @var $list \common\widgets\newsList\NewsList */

use yii\helpers\Html;

?>
<ul class="list-unstyled">
    <?php
    foreach ($list as $item): ?>
		<li class="mb-4">
			<h4>
                <?php
                echo Html::a($item['title'], ['/blog/post/post', 'id' => $item['id']]); ?>
			</h4>
			<small class="text-muted truncate-text"><?php
                echo $item['subtitle']; ?></small>
		</li>
    <?php
    endforeach; ?>
</ul>