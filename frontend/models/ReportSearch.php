<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Report;

/**
 * ReportSearch represents the model behind the search form about `app\models\Report`.
 */
class ReportSearch extends Report
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'views','authority_id', 'category_id', 'city_id', 'anonymous'], 'integer'],
            [['title', 'date', 'author', 'text', 'email', 'contact'], 'safe'],
            [['lon', 'lat'], 'number'],
        ];

    }

    /**
     * @inheritdoc
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
        $query = Report::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        /*$dataProvider->setSort([
            'attributes' => [
                'type_id' => [
                    'asc' => ['type_id' => SORT_ASC],
                    'desc' => ['type_id' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'city_id'
            ]
        ]);*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'views' => $this->views,
            'authority_id' => $this->authority_id,
            'category_id' => $this->category_id,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'city_id' => $this->city_id,
            'anonymous' => $this->anonymous,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contact', $this->contact]);

        return $dataProvider;
    }
}
