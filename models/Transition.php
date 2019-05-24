<?php

namespace vthang87\workflow\models;

use Yii;

/**
 * This is the model class for table "{{%transition}}".
 *
 * @property int $id_workflow
 * @property int $id_status_start
 * @property int $id_status_end
 * @property \vthang87\workflow\models\Status $statusStart
 * @property \vthang87\workflow\models\Status $statusEnd
 */
class Transition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wf_transition}}';
    }

    public static function checkTransition($id_workflow, $id_status_start, $id_status_end)
    {
        return self::find()->where([
            'id_workflow' => $id_workflow,
            'id_status_start' => $id_status_start,
            'id_status_end' => $id_status_end,
        ])->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_workflow', 'id_status_start', 'id_status_end'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_workflow' => Yii::t('app', 'Id Workflow'),
            'id_status_start' => Yii::t('app', 'Id Status Start'),
            'id_status_end' => Yii::t('app', 'Id Status End'),
        ];
    }

    public function getStatusStart()
    {
        return $this->hasOne(Status::class, ['id_status' => 'id_status_start']);
    }

    public function getStatusEnd()
    {
        return $this->hasOne(Status::class, ['id_status' => 'id_status_end']);
    }
}
