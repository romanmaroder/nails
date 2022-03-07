<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Archive;

/**
 * ArchiveSearch represents the model behind the search form of `common\models\Archive`.
 */
class ArchiveSearch extends Archive
{

    public $service_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['date', 'service_name', 'user_name', 'service_id', 'user_id'], 'safe'],
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
        $query = Archive::find();
        $query->joinWith(['service', 'user']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => false,
            ]
        );

        /* $dataProvider->setSort(
             [
                 'attributes' => [
                     'service_name' => [
                         'asc'   => ['service.name' => SORT_ASC],
                         'desc'  => ['service.name' => SORT_DESC],
                         'label' => 'Country Name'
                     ]
                 ]
             ]
         );*/


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(
            [
                'id'         => $this->id,
                'user_id'    => $this->user_id,
                'service_id'    => $this->service_id,
                'amount'     => $this->amount,
                'salary'     => $this->salary,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        );

        $query->andFilterWhere(['like', 'date', $this->date]);

        return $dataProvider;
    }
}
