<?php

namespace common\modules\profile\controllers;

use common\models\Event;
use common\models\Profile;
use common\models\User;
use common\modules\profile\models\AddPhotoForm;
use common\models\Photo;
use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
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
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['login'],
                        'roles'   => ['?'],
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
     * @return mixed
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->getId();

        $user = User::findOne($userId);

        $profile = Profile::findOne(['user_id' => $userId]);

        $modelAvatar = new AvatarForm($user);

        $modelPhoto = new AddPhotoForm($user);

        $photo = new Photo();

        if (!isset($user, $profile)) {
            throw new NotFoundHttpException("Пользователь не найден.");
        }

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            $isValid = $user->validate();
            $isValid = $profile->validate() && $isValid;
            if ($isValid) {
                $user->save(false);
                $profile->save(false);
                return $this->redirect(['index']);
            }
        }

        $userInfo        = User::getUserInfo($userId);
        $userProfileInfo = Profile::getUserProfileInfo($userId);

        if (Yii::$app->user->can('master')) {
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => Event::findMasterEvents($userId),
                    'sort'  => false,

                ]
            );
        } else {
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => Event::findClientEvents($userId),
                ]
            );
        }


        if ($modelPhoto->load(Yii::$app->request->post())) {
            $modelPhoto->picture = UploadedFile::getInstance($modelPhoto, 'picture');

            if ($modelPhoto->save()) {
                Yii::$app->session->setFlash('success', 'Изображение добавлено!');
                return $this->goHome();
            }
        }

        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');

        // Ids юзеров с ролью 'master'

        $model = $photo->getPhotoList($userId, $masterIds);

        return $this->render(
            'index',
            [
                'dataProvider'    => $dataProvider,
                'userInfo'        => $userInfo,
                'userProfileInfo' => $userProfileInfo,
                'user'            => $user,
                'profile'         => $profile,
                'modelAvatar'     => $modelAvatar,
                'model'           => $model,
                'modelPhoto'      => $modelPhoto
            ]
        );
    }

    /**
     *Handle profile image upload via ajax request
     */
    public function actionUploadAvatar(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId                     = Yii::$app->user->getId();

        $user          = User::findOne($userId);
        $model         = new AvatarForm($user);
        $model->avatar = UploadedFile::getInstance($model, 'avatar');

        if ($model->save()) {
            return [
                'success'    => true,
                'pictureUri' => Yii::$app->storage->getFile($user->avatar),
            ];
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }


    /**
     * Delete user avatar
     *
     * @return array|\yii\web\Response
     */
    public function actionDeletePicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/login']);
        }

        /* @var $currentUser User */

        $currentUser = Yii::$app->user->identity;

        if ($currentUser->deletePicture() ) {
            return [
                'success'    => true,
                'message'    => 'Delete',
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
     * @return array|\yii\web\Response
     */
    public function actionDeletePhoto($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/login']);
        }

        $currenPhoto = Photo::findOne($id);

        if ($currenPhoto->deletePicture() ) {
            $currenPhoto->delete();
            return [
                'success'    => true,
                'message'    => 'Delete',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error occured',
            ];
        }
    }




//    public function actionDeletePicture()
//    {
//        if (Yii::$app->user->isGuest) {
//            return $this->redirect(['/admin/login']);
//        }
//
//        /* @var $currentUser User */
//
//        $currentUser = Yii::$app->user->identity;
//
//
//        if ($currentUser->deletePicture()) {
//            Yii::$app->session->setFlash('success', 'Picture deleted');
//        } else {
//            Yii::$app->session->setFlash('danger', 'Error occured');
//        }
//
//
//        return $this->redirect(['/profile/account/']);
//    }

    /*public function actionUpdate()
    {


        return $this->render('_form-profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }*/


    /**
     * Displays a single Event model.
     *
     * @param  int  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
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
     * @param $id
     *
     * @return string
     */
    public function actionGallery($id): string
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
     * @param  int  $id
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Event
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}