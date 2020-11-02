<?php declare(strict_types=1);

namespace evo\ssanta\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SecretSantaListPairSearch
 * @package common\models
 */
class SecretSantaListPairSearch extends SecretSantaListPair
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['list_id', 'receiver_id', 'giver_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
	    return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params = [])
    {
        $query = static::find()->alias('t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'      => $this->id,
            'list_id' => $this->list_id
        ]);

        if (!empty($this->giver_id)) {

            if (is_numeric($this->giver_id)) {
                $query->andWhere(['t.giver_id' => $this->giver_id]);
            } else {
                $query->innerJoinWith(['giver' => function($query) {
                    $query->alias('g');
                    $query->andWhere([
                        'or',
                        ['like', 'g.name', $this->giver_id],
                        ['like', 'g.email', $this->giver_id]
                    ]);
                }]);
            }
        }

        if (!empty($this->receiver_id)) {

            if (is_numeric($this->receiver_id)) {
                $query->andWhere(['t.receiver_id' => $this->receiver_id]);
            } else {
                $query->innerJoinWith(['receiver' => function($query) {
                    $query->alias('r');
                    $query->andWhere([
                        'or',
                        ['like', 'r.name', $this->receiver_id],
                        ['like', 'r.email', $this->receiver_id]
                    ]);
                }]);
            }
        }

        return $dataProvider;
    }
}
