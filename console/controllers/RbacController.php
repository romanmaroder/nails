<?php


namespace console\controllers;


use Yii;

class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Разрешения
        $permCreateEvent              = $auth->createPermission('perm_create-event');//создали объект
        $permCreateEvent->description = 'Разрешение для создания события';           //добавили описание
        $auth->add($permCreateEvent);                                                //создали запись в базе данных

        $permViewCalendar              = $auth->createPermission('perm_view-calendar');//создали объект
        $permViewCalendar->description = 'Разрешение для просмотра календаря';         //добавили описание
        $auth->add($permViewCalendar);                                                 //создали запись в базе данных

        $permCreatePost = $auth->createPermission('perm_create-post');
        $permCreatePost->description='Создавать посты';
        $auth->add($permCreatePost);

        // добавляем роль "user"
        $user              = $auth->createRole('user');
        $user->description = 'Клиент';
        $auth->add($user);

        // добавляем роль "master"
        $master              = $auth->createRole('master');
        $master->description = 'Мастер';
        $auth->add($master);

        //добавляем роль "author"
        $author = $auth->createRole('author');
        $author->description='Автор';
        $auth->add($author);

        // добавляем роль "manager"
        $manager              = $auth->createRole('manager');
        $manager->description = 'Менеджер';
        $auth->add($manager);
        $auth->addChild($manager, $user);
        $auth->addChild($manager, $master);
        $auth->addChild($manager, $author);

        //-------------------Привяжем разрешение к роли
        //Роль - это родительский элемент. Разрешение дочерний элемент роли
        $auth->addChild($master, $permViewCalendar);
        $auth->addChild($manager, $permCreateEvent);
        $auth->addChild($manager, $permCreatePost);


        // добавляем роль "admin"
        $admin              = $auth->createRole('admin');
        $admin->description = 'Админ';
        $auth->add($admin);
        $auth->addChild($admin, $manager);
    }
}