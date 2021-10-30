<?php

/* @var $this yii\web\View */

/* @var $dataProvider  \frontend\controllers\SiteController */
/* @var $searchModel  \frontend\controllers\SiteController */

use common\widgets\newsList\NewsList;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/*echo '<pre>';
var_dump($dataProvider);
die();*/


?>
<?php
Pjax::begin(); ?>
	<div class="site-index">
		<div class="body-content">
			<div class="row">
				<div class="col-xl-2">
                    <?php
                    echo $this->render('@common/modules/blog/views/post/_search', ['model' => $searchModel]);
                    ?>
				</div>
				<div class="col-xl-8">
					<div class="row ">
                        <?php
                        foreach ($dataProvider->getModels() as $key => $post) : ?>
                            <?php
                            if ($key % 2 !== 0) {
                                $class = 'alt';
                            } else {
                                $class = '';
                            } ?>
							<div class="col-12 col-lg-6 mb-3">
								<div class="blog-card mx-auto <?php
                                echo $class; ?>"
									 id="<?php
                                     echo $post['id']; ?>">
									<div class="meta">
										<div class="photo"
											 style="background-image: url(<?php
                                             echo Yii::$app->storage->getFile($post['preview']) ?>)"></div>
										<ul class="details">
											<li class="author">
                                                <?php
                                                echo Html::a(
                                                    $post['user']['username'],
                                                    ['/site/view', 'id' => $post['user_id']],
                                                ); ?>

											</li>
											<li class="tags">
												<i class="fas fa-tag  mr-2"></i>
                                                <?= $post['category']['category_name']; ?>
											</li>

											<li class="date"><?php
                                                echo date('d/m/y', $post['created_at']); ?></li>
										</ul>
									</div>
									<div class="description">
										<h1><?php
                                            echo Html::a($post['title'], ['/blog/post/post', 'slug' => $post['slug']]);
                                            ?></h1>
										<h2><?php
                                            echo $post['subtitle']; ?></h2>
										<p></p>
										<!--<div class="truncate-text no-img"> <?php
                                        /*echo $post['subtitle'] ;*/ ?></div>-->
										<p class="read-more">
                                            <?php
                                            echo Html::a(
                                                ' Читать <i class="fas fa-arrow-right"></i>',
                                                ['/blog/post/post', 'slug' => $post['slug']]
                                            );
                                            ?>
										</p>
									</div>
								</div>
							</div>
                        <?php
                        endforeach; ?>
					</div>

				</div>
				<div class="d-none d-xl-block col-xl-2">
                    <?php
                    echo NewsList::widget(['showLimit' => 3]); ?>
				</div>
			</div>

		</div>
	</div>
<?php
Pjax::end(); ?>