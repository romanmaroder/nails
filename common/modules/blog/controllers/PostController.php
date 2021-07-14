<?php

namespace common\modules\blog\controllers;

use common\models\PostImage;
use common\models\User;
use Yii;
use common\models\Post;
use common\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

        ];
    }

    /**
     * Lists all Post models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
//            'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,

            ]
        );
    }

    /**
     * Displays a single Post model.
     *
     * @param  int  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Displays a single Post model.
     *
     * @param  int  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPost(int $id)
    {
        return $this->render(
            'post',
            [
                'post' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            $userId = Yii::$app->user->identity->getId();
            $user   = User::findOne($userId);

            $model->user_id = $user->id;

            $model->save();
            return $this->redirect(
                [
                    'view',
                    'id' => $model->id,
//                    'pictureUri' => Yii::$app->storage->getFile($user->files),
                ]
            );
        }

        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }


    /**
     * Uploading an image to a directory
     * @return array
     */
    public function actionUploadImageSummernote(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PostImage();

        $model->picture = UploadedFile::getInstanceByName('file');


        if ($model->upload()) {
            return [
                'success' => true,
                'uri'     => Yii::$app->storage->getFile($model->image),
                'message' => 'Фото загружено'
            ];
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }


    /**
     * Removing uploaded images to the editor
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImageSummernote(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model                      = new PostImage();


            $model->picture = Yii::$app->request->post('src');

            if ($model->delete()) {

                return [
                    'message' => 'Удалено'
                ];
            }
            return [
                'message' => 'Не удалось удалить'
            ];

    }


    /**
     * Publishing the current article
     *
     * @param $id
     *
     * @return array
     */
    public function actionPublish($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post                       = new Post();
        if ($post->toPublish($id)) {
            return [
                'success' => true,
                'message' => 'Статья опубликована',
                'status'  => 'Снять с публикации'
            ];
        }
        return [
            'success' => false,
            'message' => 'Статья не опубликована',
            'status'  => 'Опубликовать'
        ];
    }


    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param  integer  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param  integer  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id)
    {
        $postImage = PostImage::find()->select('image')->where(['post_id'=>$id])->all();

        foreach($postImage as $image){
            Yii::$app->storage->deleteFile($image->image);
        }


        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param  integer  $id
     *
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}