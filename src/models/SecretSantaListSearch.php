<?php declare(strict_types=1);

namespace evo\ssanta\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SecretSantaListSearch
 * @package common\models
 */
class SecretSantaListSearch extends SecretSantaList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id', 'user_id'], 'safe'],
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
        $query = static::find();

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
            'user_id' => $this->user_id
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
