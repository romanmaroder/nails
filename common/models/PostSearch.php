<?php

namespace common\models;


use common\models\Post;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * PostSearch represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'subtitle', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        if (Yii::$app->id === 'app-frontend') {
            $query = Post::find()->where(['status' => 1]);
        }

//        if (Yii::$app->id ==='app-frontend' && Yii::$app->controller->route == 'blog/post/index') {
//
//            $query = Post::find()->where(['user_id'=>Yii::$app->user->identity->getId()]);
//        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
                                                   'query' => $query,
                                               ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
                                   'id' => $this->id,
                                   'user_id' => $this->user_id,
                                   'category_id' => $this->category_id,
                                   'created_at' => $this->created_at,
                                   'updated_at' => $this->updated_at,
                               ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function formName() {
        return '';
    }
}
