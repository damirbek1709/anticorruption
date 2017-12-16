<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Qwert;

/**
 * QwertSearch represents the model behind the search form about `frontend\models\Qwert`.
 */
class QwertSearch extends Qwert
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'views', 'authority_id', 'category_id', 'city_id', 'anonymous', 'type_id', 'status', 'user_id'], 'integer'],
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
        $query = Qwert::find();

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
            'date' => $this->date,
            'views' => $this->views,
            'authority_id' => $this->authority_id,
            'category_id' => $this->category_id,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'city_id' => $this->city_id,
            'anonymous' => $this->anonymous,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contact', $this->contact]);

        return $dataProvider;
    }
}
