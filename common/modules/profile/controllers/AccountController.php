<?php

namespace common\modules\profile\controllers;

use common\models\Certificate;
use common\models\Event;
use common\models\Profile;
use common\models\Setting;
use common\models\Todo;
use common\models\User;
use common\modules\profile\models\AddCertificate;
use common\modules\profile\models\AddPhotoForm;
use common\models\Photo;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\profile\models\AvatarForm;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * AccountController implements the CRUD actions for Event model.
 */
class AccountController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Event models.
     *
     * @return string|Response
     * @throws NotFoundHttpException|Exception|Throwable
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->getId();

        $user = User::findIdentity($userId);

        $profile = Profile::getUserProfileInfo($userId);

        $setting = new Setting();

        $modelAvatar = new AvatarForm($user);

        $modelPhoto = new AddPhotoForm($user);

        $photo = new Photo();

        $modelCertificate = new AddCertificate($user);

        $certificate = new Certificate();

        $modelTodo = new Todo();

        $dataProvider = Event::getEventDataProvider($userId);


        if (!isset($user, $profile)) {
            throw new NotFoundHttpException("Пользователь не найден.");
        }

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->password) {
                $user->setPassword($user->password);
            }
            $isValid = $user->validate();
            $isValid = $profile->validate() && $isValid;
            if ($isValid) {
                $user->save(false);
                $profile->save(false);
                return $this->redirect(['index']);
            }
        }

        if ($setting->load(Yii::$app->request->post())) {
            if ($setting->checkbox == 1) {
                $setting->setCookies();
                return $this->refresh();
            }
            if ($setting->checkbox == 0) {
                $setting->deleteCookies();
                return $this->refresh();
            }
        }

        if ($modelPhoto->load(Yii::$app->request->post())) {
            $modelPhoto->picture = UploadedFile::getInstance($modelPhoto, 'picture');

            if ($modelPhoto->save()) {
                Yii::$app->session->setFlash('success', 'Изображение добавлено!');
                return $this->goHome();
            } else {
                $modelPhoto->getErrors();
            }
        }


        if ($modelCertificate->load(Yii::$app->request->post())) {
            $modelCertificate->image = UploadedFile::getInstance($modelCertificate, 'image');

            if ($modelCertificate->save()) {
                Yii::$app->session->setFlash('success', 'Сертификат добавлен!');
                return $this->refresh();
            } else {
                $modelCertificate->getErrors();
            }
        }

        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');

        // Ids юзеров с ролью 'master'

        $model = $photo->getPhotoList($userId, $masterIds);

        $certificateList = $certificate->getCertificates($userId);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'user' => $user,
                'profile' => $profile,
                'setting' => $setting,
                'modelAvatar' => $modelAvatar,
                'model' => $model,
                'modelCertificate' => $modelCertificate,
                'certificateList' => $certificateList,
                'modelPhoto' => $modelPhoto,
                'modelTodo'=>$modelTodo
            ]
        );
    }

    /**
     *Handle profile image upload via ajax request
     */
    public function actionUploadAvatar(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->getId();

        $user = User::findIdentity($userId);
//
        $model = new AvatarForm($user);
        $model->avatar = UploadedFile::getInstance($model, 'avatar');

        if ($model->save()) {
            return [
                'success' => true,
                'pictureUri' => Yii::$app->storage->getFile($user->avatar),
                'message' => 'Аватар загружен'
            ];
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

    /**
     * Delete user avatar
     *
     * @return array|Response
     */
    public function actionDeletePicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/login']);
        }

        /* @var $currentUser User */

        $currentUser = Yii::$app->user->identity;

        if ($currentUser->deletePicture()) {
            return [
                'success' => true,
                'message' => 'Аватар удален',
                'pictureUri' => User::DEFAULT_IMAGE,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error occured',
            ];
        }
    }

    /**
     * Delete user avatar
     *
     * @param int $id
     * @param $class
     * @return array|Response
     */
    public function actionDeletePhoto(int $id, $class)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/login']);
        }

        $currenPhoto = $class::findOne($id);


        if ($currenPhoto->deletePicture()) {
            $currenPhoto->delete();
            return [
                'success' => true,
                'message' => 'Удалено',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error occurred',
            ];
        }
    }

    /**
     * Displays a single Event model.
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Getting a list of user photos
     *
     * @param int $id
     *
     * @return string
     */
    public function actionGallery(int $id): string
    {
        $photo = new Photo();

        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');

        // Ids юзеров с ролью 'master'

        $model = $photo->getPhotoList($id, $masterIds);


        return $this->render(
            'gallery',
            [
                'model' => $model,

            ]
        );
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel(int $id): Event
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionAddTodo()
    {
        // Создаём экземпляр модели.
        $modelTodo = new Todo();
        // Устанавливаем формат ответа JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Если пришёл AJAX запрос
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            // Получаем данные модели из запроса
            if ($modelTodo->load($data)) {
                //Если всё успешно, отправляем ответ с данными
                return [
                    "data" => $modelTodo,
                    "error" => null
                ];
            } else {
                // Если нет, отправляем ответ с сообщением об ошибке
                return [
                    "data" => null,
                    "error" => "error1"
                ];
            }
        } else {
            // Если это не AJAX запрос, отправляем ответ с сообщением об ошибке
            return [
                "data" => null,
                "error" => "error2"
            ];
        }
    }


}
