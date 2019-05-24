<?php

namespace vthang87\workflow\models;

use Exception;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%workflow}}".
 *
 * @property int $id_workflow
 * @property string $model
 * @property string $name
 * @property string $column
 * @property int $init_status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property \vthang87\workflow\models\Status[] $statuses
 * @property \vthang87\workflow\models\Status $initStatus
 */
class Workflow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wf_workflow}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['init_status', 'created_at', 'updated_at'], 'integer'],
            [['model', 'name', 'column'], 'string', 'max' => 255],
            [['name', 'model', 'column'], 'required'],
            [
                'model',
                function ($attribute, $params, $validator) {
                    if (!class_exists($this->model)) {
                        $this->addError($attribute, Yii::t('app', 'Class "' . $this->model . '" not exist'));
                    }
                },
            ],
            [
                'column',
                function ($attribute, $params, $validator) {
                    if (class_exists($this->model)) {
                        $object = new $this->model();
                        if ($object instanceof Model) {
                            if (!$object->hasProperty($this->column)) {
                                $this->addError($attribute, Yii::t('app', 'Column name not exist'));
                            }
                        } else {
                            $this->addError('model', Yii::t('app', 'Model class invalid'));
                        }
                    }
                }
            ],
            [['model', 'column'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_workflow' => Yii::t('app', 'ID'),
            'model' => Yii::t('app', 'Model'),
            'init_status' => Yii::t('app', 'Init Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return \app\models\Status[]|array|\yii\db\ActiveQuery
     */
    public function getStatuses()
    {
        return $this->hasMany(Status::class, ['id_workflow' => 'id_workflow'])->orderBy(['position' => SORT_ASC]);
    }

    /**
     * @return \app\models\Status|\yii\db\ActiveQuery
     */
    public function getInitStatus()
    {
        return $this->hasOne(Status::class, ['id_status' => 'init_status']);
    }

    public function delete()
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $statuses = Status::findAll(['id_workflow' => $this->id_workflow]);
            foreach ($statuses as $status) {
                $status->delete();
            }
            parent::delete();
            $transaction->commit();
            return true;
        } catch (Exception $ex) {
            Yii::error($ex);
            return false;
        }
    }
}
