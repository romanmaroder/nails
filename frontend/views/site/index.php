<?php

/* @var $this yii\web\View */

/* @var $postsList  \frontend\controllers\SiteController */

use common\widgets\newsList\NewsList;
use yii\helpers\Html;

$this->title = 'Nails';
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


	<div class="body-content" >

		<div class="row mt-3">
			<div class="d-none d-lg-block col-lg-3">
                <?php
                echo NewsList::widget(['showLimit' => 3]); ?>
			</div>
			<div class="col-lg-9">
				<div class="row ">
                    <?php
                    foreach ($postsList as $post) : ?>

						<div class="col-12 mb-3">
							<h2><?php
                                echo Html::a($post['title'], ['/blog/post/post', 'id' => $post['id']]); ?>
							</h2>
							<span class="truncate-text no-img"><?php
                            echo $post['description']; ?></span>
							<?php
                                echo Html::a('Подробнее...', ['/blog/post/post', 'id' => $post['id']],
											 ['class'=>'btn btn-outline-info btn-sm mt-3']);
                                ?>
						</div>

                    <?php
                    endforeach; ?>
				</div>

			</div>
		</div>

	</div>
</div>
