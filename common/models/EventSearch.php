<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{

    public $service;
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
        $query->joinWith(['eventService', 'master', 'client', 'master.profile', 'client.profile']);
        $query->andWhere(' YEAR(event_time_start) = YEAR(NOW())');
        $query->orderBy(['event_time_start' => SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => false,
                'sort'       => [
                    'attributes' => [
                        'event_time_start' => [
                            'asc'  => ['event_time_start' => SORT_ASC],
                            'desc' => ['event_time_start' => SORT_DESC],
                        ],
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
                'id'        => $this->id,
                'master_id' => $this->master_id,
            ]
        );

        $query->andFilterWhere(['>=', 'event_time_start', $this->date_from ? $this->date_from . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'event_time_end', $this->date_to ? $this->date_to . ' 23:59:59' : null]);


        $query->joinWith(
            [
                'services' => function ($q) {
                    $q->andFilterWhere(['in', 'service.id', $this->service]);
                }
            ]
        );

        return $dataProvider;
    }
}
