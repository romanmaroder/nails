<?php

/* @var $this yii\web\View */

/* @var $postsList  \frontend\controllers\SiteController */

use common\widgets\newsList\NewsList;
use yii\helpers\Html;

?>

<div class="site-index">
    <?php
    /*
    if (!Yii::$app->user->isGuest) {
        $userId = \Yii::$app->user->getId();
    echo '<pre>';
        //Все роли текущего пользователя
        var_dump(\Yii::$app->authManager->getRolesByUser($userId));
        PHP_EOL;

        //Разрешение пользователя
        var_dump(\Yii::$app->authManager->getAssignment('admin', $userId));
        PHP_EOL;

        //Все разрешения пользователя
        var_dump(\Yii::$app->authManager->getAssignments($userId));
        PHP_EOL;

        //Проверка доступа пользователя
        echo 'Проверка доступа пользователя ';
        var_dump(\Yii::$app->authManager->checkAccess($userId, 'admin', $params = []));
        PHP_EOL;

        //Тоже проверка доступа пользователя
        echo 'Тоже проверка доступа пользователя ';
        var_dump(Yii::$app->user->can('admin'));
    echo '</pre>';

    } else {
        echo "Здравствуйте, Гость!";
    }
    ;*/ ?>


	<div class="body-content">

		<div class="row mt-3">
			<div class="d-none d-xl-block col-xl-2">
                <?php
                echo NewsList::widget(['showLimit' => 3]); ?>
			</div>
			<div class="col-xl-10">
				<div class="row ">
                    <?php
                    foreach ($postsList as $key => $post) : ?>
                        <?php
                        if ($key % 2 !== 0) {
                            $class = 'alt';
                        } else {
                            $class = '';
                        }; ?>
						<div class="col-12 col-lg-6 mb-3">
							<div class="blog-card mx-auto <?php echo $class; ?>"
								 id="<?php echo $post['id']; ?>">
								<div class="meta">
									<div class="photo"
										 style="background-image: url(<?php
                                         echo Yii::$app->storage->getFile($post['preview']) ?>)"></div>
									<ul class="details">
										<li class="author"><a href="#"><?php
                                                echo $post['user']['username']; ?></a></li>
										<li class="date"><?php
                                            echo date('d/m/y', $post['created_at']); ?></li>
										<!--<li class="tags">
											<ul>
												<li><a href="#">Learn</a></li>
												<li><a href="#">Code</a></li>
												<li><a href="#">HTML</a></li>
												<li><a href="#">CSS</a></li>
											</ul>
										</li>-->
									</ul>
								</div>
								<div class="description">
									<h1><?php
                                        echo Html::a($post['title'], ['/blog/post/post', 'id' => $post['id']]); ?></h1>
									<h2><?php echo $post['subtitle']; ?></h2>
									<p></p>
									<div class="truncate-text no-img"> <?php echo $post['description'] ;?></div>
									<p class="read-more">
                                        <?php
                                        echo Html::a(
                                            ' Читать <i class="fas fa-arrow-right"></i>',
                                            ['/blog/post/post', 'id' => $post['id']]
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
		</div>

	</div>
</div>
