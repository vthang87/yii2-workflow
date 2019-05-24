<?php /** @noinspection ALL */

namespace vthang87\workflow\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorkflowSearch represents the model behind the search form of `app\models\Workflow`.
 */
class WorkflowSearch extends Workflow
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_workflow', 'init_status', 'created_at', 'updated_at'], 'integer'],
            [['model', 'name', 'column'], 'safe'],
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
        $query = Workflow::find();

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
            'id_workflow' => $this->id_workflow,
            'init_status' => $this->init_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model]);

        return $dataProvider;
    }
}
