<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;

/**
 * NewsSearch represents the model behind the search form about `common\models\News`.
 */
class NewsSearch extends News
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'count_attach'], 'integer'],
            [['title', 'text', 'image'], 'safe'],
            'createdDefault' => [['created_at', 'updated_at'], 'default', 'value' => null],
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
        $query = News::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query->joinWith(['files'])
                        ->select([
                            'news.id', 
                            'news.title', 
                            'news.active', 
                            'news.created_at', 
                            'news.updated_at', 
                            'count(files.id) as count_attach'])
                        ->groupBy('news.id'),
        ]);
        
        $dataProvider->sort->attributes['count_attach'];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text]);
        
        if (isset($this->count_attach) && $this->count_attach >= 0 && $this->count_attach !== '') {
            
            $query->having([
                '=',
                'count_attach',
                $this->count_attach
            ]);
        }
                    
        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }
        if ($this->updated_at !== null) {
            $date = strtotime($this->updated_at);
            $query->andFilterWhere(['between', 'updated_at', $date, $date + 3600 * 24]);
        }

        return $dataProvider;
    }
}
