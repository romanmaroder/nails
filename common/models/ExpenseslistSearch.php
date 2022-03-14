<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Expenseslist;

/**
 * ExpenseslistSearch represents the model behind the search form of `common\models\Expenseslist`.
 */
class ExpenseslistSearch extends Expenseslist
{

    public $date_from;
    public $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'expenses_id', 'price', 'created_at', 'updated_at'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
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
        $query = Expenseslist::find();
        //$query->joinWith(['expenses']);

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
            'expenses_id' => $this->expenses_id,
            'price' => $this->price,
        ])
            ->andFilterWhere(['like', 'expenses_id', $this->expenses_id])
            ->andFilterWhere(['>=', 'expenseslist.created_at', $this->date_from ? strtotime( $this->date_from . ' 00:00:00') :
                null])
            ->andFilterWhere(['<=', 'expenseslist.created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);
        $query->joinWith(['expenses' => function ($q) {
            $q->andFilterWhere(['in', 'expenses.id', $this->id]);
        }]);

        return $dataProvider;
    }
}
