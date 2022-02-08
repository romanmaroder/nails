<?php

namespace common\models;

use common\models\Event;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{

    public $service;
    public $cost;
    public $salary;
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'updated_at'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [
                [
                    'master_id',
                    'client_id',
                    'service',
                    'cost',
                    'salary',
                    'description',
                    'notice',
                    'event_time_start',
                    'event_time_end',
                    'created_at'
                ],
                'safe'
            ],
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
        $query = Event::find();
        $query->joinWith(['services', 'eventService', 'master','client']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => false,
                'sort'       => [
                    'attributes' => [
                        'cost' => [
                            'asc'  => ['cost' => SORT_ASC],
                            'desc' => ['cost' => SORT_DESC],
                        ],
                        /*'salary' => [
                            'asc'  => ['salary' => SORT_ASC],
                            'desc' => ['salary' => SORT_DESC],
                        ]*/

                    ]

                ]
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(
            [
                'id' => $this->id,
                'master_id'=>$this->master_id,
            ]
        );

            $query->andFilterWhere(['>=', 'event_time_start', $this->date_from ? $this->date_from . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'event_time_end', $this->date_to ? $this->date_to . ' 23:59:59' : null])
            ->andFilterWhere(['=', 'service.cost', $this->salary]);


        $query->joinWith(['services' => function ($q) {
            $q->andFilterWhere(['in', 'service.id', $this->service]);
        }]);

        return $dataProvider;
    }
}
