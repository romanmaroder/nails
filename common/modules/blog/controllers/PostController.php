<?php

namespace common\modules\blog\controllers;

use common\components\behaviors\DeleteCacheBehavior;
use common\models\PostImage;
use common\models\User;
use common\modules\blog\models\AddPost;
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
            [
                'class' => DeleteCacheBehavior::class,
                'cache_key' => ['events_list'],
                'actions' => ['create', 'update', 'delete'],
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
        $cache = Yii::$app->cache;
        $key = 'post_list';  // Формируем ключ
        // Данный метод возвращает данные либо из кэша, либо из откуда-либо и записывает их в кэш по ключу на 1 час
        $dataProvider = $cache->getOrSet(
            $key,
            function () {
                $searchModel  = new PostSearch();
               return $searchModel->search(Yii::$app->request->queryParams);
            },
            3600
        );


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
     * @param $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPost($slug)
    {
        $post = $this->findModelBySlug($slug);

        $this->setMeta(
            $post->title,
            $post->subtitle,
            $post->description
        );
        if ($post == null) {
            throw new NotFoundHttpException('Запрошенная страница не существует.');
        }

        return $this->render(
            'post',
            [
                'post' => $post,
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
        $userId = Yii::$app->user->identity->getId();
        $user   = User::findOne($userId);
        $model  = new AddPost($user);

        if ($model->load(Yii::$app->request->post())) {
            $model->picture = UploadedFile::getInstance($model, 'picture');
            if ($model->saved()) {
                return $this->redirect('index');
                //          return $this->redirect(['view', 'id' => $post->id]);
            }
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
     *
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
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImageSummernote(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PostImage();


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

        if ($model->load(Yii::$app->request->post())) {

            if (!empty($preview = $model->preview = UploadedFile::getInstance($model, 'picture'))) {
                if ($oldPreview = Post::getPreview($id)) {
                    Yii::$app->storage->deleteFile($oldPreview->preview);
                }
                $model->preview = Yii::$app->storage->saveUploadedFile($preview);
            } else {
                $preview        = Post::getPreview($id);
                $model->preview = $preview->preview;
            }

                $model->save(false);

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
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $postImage = PostImage::find()->select('image')->where(['post_id' => $id])->all();

        foreach ($postImage as $image) {
            Yii::$app->storage->deleteFile($image->image);
        }

        $postPreview = Post::find()->select('preview')->where(['id' => $id])->one();
        Yii::$app->storage->deleteFile($postPreview->preview);

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

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     * Finds the Post model based on its slug
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $slug
     * @return array|\yii\db\ActiveRecord
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($slug){
        if (($model = Post::find()->where(['slug'=>$slug,'status'=>1])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
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
