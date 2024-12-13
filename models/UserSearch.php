<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $name;
    public $login;
    public $image;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'login', 'image'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // Бездоглядне впровадження сценаріїв у батьківській моделі
        return Model::scenarios();
    }

    /**
     * Створює data provider із застосованим пошуковим запитом
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // Створення data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        // Завантаження параметрів
        $this->load($params);

        if (!$this->validate()) {
            // Якщо валідація не проходить, повертаємо без результатів
            $query->where('0=1');
            return $dataProvider;
        }

        // Фільтрація за ID
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        // Фільтрація за іншими атрибутами
        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'login', $this->login])
              ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
