<?php

namespace vthang87\workflow\models;

use Exception;
use Yii;

/**
 * This is the model class for table "{{%status}}".
 *
 * @property int $id_status
 * @property int $id_workflow
 * @property int $position
 * @property string $label
 *
 * @property \vthang87\workflow\models\Workflow $workflow
 * @property \vthang87\workflow\models\Transition[] $transitionFrom
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wf_status}}';
    }

    public static function getLabel($id_status)
    {
        return self::findOne($id_status)->label;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_workflow', 'position'], 'integer'],
            [['label'], 'string', 'max' => 255],
            ['position', 'default', 'value' => self::getMaxPosition($this->id_workflow) + 1],
        ];
    }

    public static function getMaxPosition($id_workflow)
    {
        return self::find()->where(['id_workflow' => $id_workflow])->max('position');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_status' => Yii::t('app', 'Id Status'),
            'id_workflow' => Yii::t('app', 'Id Workflow'),
            'position' => Yii::t('app', 'Position'),
            'label' => Yii::t('app', 'Label'),
        ];
    }

    public function delete()
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            Transition::deleteAll([
                'OR',
                ['id_status_start' => $this->id_status],
                ['id_status_end' => $this->id_status]
            ]);
            parent::delete();
            $transaction->commit();
            return true;
        } catch (Exception $ex) {
            Yii::error($ex);
            $transaction->rollBack();
            return false;
        }
    }

    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['id_workflow' => 'id_workflow']);
    }

    public function getTransitionFrom()
    {
        return $this->hasMany(Transition::class, ['id_status_start' => 'id_status']);
    }
}
