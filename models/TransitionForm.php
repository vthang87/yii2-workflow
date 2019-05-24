<?php
/**
 * Created by PhpStorm.
 * User: dangvanthang
 * Date: 5/28/18
 * Time: 5:42 PM
 */

namespace vthang87\workflow\models;

use Yii;
use yii\base\Model;

class TransitionForm extends Model
{
    public $transitions;
    public $id_workflow;

    public function rules()
    {
        return [
            ['transitions', 'safe'],
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        Yii::debug($this->transitions);
        if (is_array($this->transitions)) {
            $transaction = Yii::$app->db->beginTransaction();
            Transition::deleteAll(['id_workflow' => $this->id_workflow]);
            foreach ($this->transitions as $fromStatus => $toStatuses) {
                foreach ($toStatuses as $toStatus => $checked) {
                    $transition = new Transition();
                    $transition->id_workflow = $this->id_workflow;
                    $transition->id_status_start = $fromStatus;
                    $transition->id_status_end = $toStatus;
                    if (!$transition->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            $transaction->commit();
            return true;
        }
        return false;
    }
}
