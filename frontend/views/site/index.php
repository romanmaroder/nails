<?php

/* @var $this yii\web\View */

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
;*/?>
    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
