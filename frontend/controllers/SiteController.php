<?php

namespace frontend\controllers;

use common\models\Certificate;
use common\models\Photo;
use common\models\Post;
use common\models\PostSearch;
use common\models\Profile;
use common\models\User;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['logout', 'signup','view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash('info','Авторизуйтесь чтобы узнать больше');
                    return $this->redirect(['site/login']);
                    # throw new \Exception('У вас нет доступа к этой странице');
                }
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            /*'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
            'captcha' => [
                'class' => 'hr\captcha\CaptchaAction',
                'operators' => ['+','-','*'],
                'maxValue' => 10,
                'fontSize' => 16,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->setMeta(
            'Nails - красота и уход за вашими ногтями',
            'Маникюр, коррекция, дизайн, лак-гель, гель, кутикула, обрезной маникюр, топ, база, пилочки для ногтей, баф, фрезер, фреза, ноготь, лампа, вытяжка, масло, лечебный лак, восстанавливающий лак',
            'Блог о ногтевом сервисе, примеры дизайна ногтей. Оказание услуг в сфере ногтевого сервиса'
        );
        $searchModel  = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        $posts = new Post();
//        $postsList=  $posts->getAllPostList();

        return $this->render('index',[
            //'postsList'=>$postsList,
            'dataProvider'=>$dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {

        $this->setMeta(
            'Nails - блог об уходе за ноготочками, дизайны, советы, статьи. ',
            'Авторизация, регистрация, вход',
            'Войдите на сайт, чтобы увидеть дополнительные функции в своём личном кабинете'
        );

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $this->setMeta(
            'Nails - блог об уходе за ноготочками, дизайны, советы, статьи. ',
            'Контакты, адрес, телефон',
            'Наши контакты для связи. Форма обратной связи.'
        );

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash(
                    'success',
                    'Thank you for contacting us. We will respond to you as soon as possible.'
                );
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render(
                'contact',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Displays portfolio page.
     *
     * @return mixed
     */
    public function actionPortfolio()
    {
        $photo     = new Photo();
        $portfolio = $photo->getPortfolio();

        return $this->render('portfolio', ['portfolio' => $portfolio]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $this->setMeta(
            'Наши мастера маникюра и педикюра. Краткая информация о мастерах, количестве их работ и сертификатов ',
            'Мастер маникюра, мастер педикюра, бровист, сертификаты повывшения квалификации',
            'Краткая информация о наших мастерах.Узнайте немного больше о мастерах и их достижениях, перейдя в их профиль.'
        );


        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');

        $dataProvider = new ActiveDataProvider(
            [
                'query' =>  User::find()->with('certificate')->where(['id' => $masterIds]),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        $path = Photo::getBackgroundCard();

        return $this->render('about', ['path' => $path,'dataProvider'=>$dataProvider]);
    }


    /**
     * Wizard Information Page
     *
     * @param $id  - master id
     *
     * @return string
     */
    public function actionView($id): string
    {
        $photo = new Photo();
        $portfolio= $photo->getPortfolio($id);
        $images=[];
        foreach ($portfolio as $img) {
            $images[] =  '<img class="w-100 " src="'.Yii::$app->storage->getFile($img['image']).'"/>';
        }

        $certificates = Certificate::find()->where(['user_id'=>$id])->asArray()->all();

        $certificat=[];
        foreach ($certificates as $item) {
            $certificat[] =  '<img class="w-100 " src="'.Yii::$app->storage->getFile($item['certificate']).'"/>';
        }

        $master    = Profile::find()->with('user')->where(['user_id' => $id])->one();

        return $this->render(
            'view',
            [
                'master'    => $master,
                'images' => $images,
                'certificat'=>$certificat
            ]
        );
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        $this->setMeta(
            'Nails - блог об уходе за ноготочками, дизайны, советы, статьи. ',
            'Авторизация, регистрация, вход',
            'Пройдите регистрацию для доступа в личный кабинет'
        );
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash(
                'success',
                'Спасибо за регистрацию. Пожалуйста, проверьте свой почтовый ящик на наличие подтверждающего письма.'
            );
            return $this->goHome();
        }

        return $this->render(
            'signup',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        $this->setMeta(
            'Nails - блог об уходе за ноготочками, дизайны, советы, статьи. ',
            'Авторизация, регистрация, вход, сброс пароля',
            'Забыли пароль? Сбросьте его с помощью данной формы'
        );

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    'Sorry, we are unable to reset password for the provided email address.'
                );
            }
        }

        return $this->render(
            'requestPasswordResetToken',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Resets password.
     *
     * @param  string  $token
     *
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render(
            'resetPassword',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Verify email address
     *
     * @param  string  $token
     *
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Ваш email был подтверждён!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash(
            'error',
            'К сожалению, мы не можем подтвердить вашу учетную запись с помощью предоставленного токена.'
        );
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash(
                'error',
                'Sorry, we are unable to resend verification email for the provided email address.'
            );
        }

        return $this->render(
            'resendVerificationEmail',
            [
                'model' => $model
            ]
        );
    }

    /**
     * Sets the meta tags for keywords, descriptions and title
     *
     * @param  null  $title
     * @param  null  $keywords
     * @param  null  $description
     */
    protected function setMeta($title = null, $keywords = null, $description = null)
    {
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => strip_tags("$keywords")]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => strip_tags("$description")]);
    }
}
