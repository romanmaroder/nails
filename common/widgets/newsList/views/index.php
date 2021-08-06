<?php
/* @var $list \common\widgets\newsList\NewsList */

use yii\helpers\Html;

?>
<ul class="list-unstyled">
    <?php
    foreach ($list as $item): ?>
		<li class="mb-4">
			<div class="blog-card"
				 id="<?php echo $item['id']; ?>">
				<div class="meta__list-views">
					<div class="photo"
						 style="background-image: url(<?php
                         echo Yii::$app->storage->getFile($item['preview']) ?>)"></div>
					<ul class="details">
						<li class="author"><a href="#"><?php
                                echo $item['user']['username']; ?></a></li>
						<li class="date"><?php
                            echo date('d/m/y', $item['created_at']); ?></li>
					</ul>
				</div>
				<div class="description">
					<h4><?php
                        echo Html::a($item['title'], ['/blog/post/post', 'id' => $item['id']]); ?></h4>
					<h5><?php echo $item['subtitle']; ?></h5>
					<p class="read-more">
                        <?php
                        echo Html::a(
                            ' Читать <i class="fas fa-arrow-right"></i>',
                            ['/blog/post/post', 'id' => $item['id']]
                        );
                        ?>
					</p>
				</div>
			</div>




		</li>
    <?php
    endforeach; ?>
</ul>