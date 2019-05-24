<?php

namespace vthang87\workflow\helpers;

use vthang87\workflow\models\Status;
use vthang87\workflow\models\Transition;
use vthang87\workflow\models\Workflow;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: dangvanthang
 * Date: 5/30/18
 * Time: 10:43 AM
 */
class WorkflowHelper
{
    /**
     *
     * @param $id_workflow
     * @param $from_status
     * @param $to_status
     *
     * @return bool
     */
    public static function validNextStatus($id_workflow, $from_status, $to_status)
    {
        return Transition::find()
            ->innerJoinWith([
                'statusStart' => function (ActiveQuery $q) use ($id_workflow, $from_status) {
                    $q->alias('s1')->where(['s1.id_status' => $from_status, 's1.id_workflow' => $id_workflow]);
                },
            ])
            ->innerJoinWith([
                'statusEnd' => function (ActiveQuery $q) use ($id_workflow, $to_status) {
                    $q->alias('s2')->where(['s2.id_status' => $to_status, 's2.id_workflow' => $id_workflow]);
                },
            ])->exists();
    }
    
    /**
     * Get next status list include curent status
     * format
     * [
     *      id_status => label,
     *      ...
     * ]
     *
     * @param $model     string Model Class Name
     * @param $column    string
     * @param $id_status integer
     *
     * @return array
     */
    public static function getNextStatusList($model, $column, $id_status)
    {
        $out = [];
        if ($id_status) {
            $result = Transition::find()
                ->where(['id_status_start' => $id_status])
                ->innerJoinWith([
                    'statusStart' => function (ActiveQuery $q) use ($model, $column) {
                        $q->innerJoinWith([
                            'workflow' => function (ActiveQuery $q) use ($model, $column) {
                                $q->where(['model' => $model, 'column' => $column]);
                            },
                        ]);
                    },
                ])->all();
            $status = Status::findOne($id_status);
            if ($status) {
                $out[$status->id_status] = $status->label;
            }
            foreach ($result as $row) {
                $out[$row->id_status_end] = $row->statusEnd->label;
            }
        } else {
            /** @var Workflow | null $wf */
            if (($wf = Workflow::findOne(['model' => $model, 'column' => $column])) && $wf->initStatus) {
                $out[$wf->initStatus->id_status] = $wf->initStatus->label;
            }
        }
        return $out;
    }
    
    /**
     * Check status is end
     *
     * @param $model
     * @param $column
     * @param $id_status
     *
     * @return bool
     */
    public function isEndStatus($model, $column, $id_status)
    {
        $result = Transition::find()
            ->where(['id_status_start' => $id_status])
            ->innerJoinWith([
                'statusStart' => function (ActiveQuery $q) use ($model, $column) {
                    $q->innerJoinWith([
                        'workflow' => function (ActiveQuery $q) use ($model, $column) {
                            $q->where(['model' => $model, 'column' => $column]);
                        },
                    ]);
                },
            ])->count();
        return $result === 0;
    }
    
    /**
     * Get next status list include curent status
     * format
     * [
     *      id_status => label,
     *      ...
     * ]
     *
     * @param $model  string Model Class Name
     * @param $column string
     *
     * @return array
     */
    public static function getAllStatusList($model, $column)
    {
        $query = Status::find();
        $query->innerJoinWith([
            'workflow' => function (ActiveQuery $q) use ($model, $column) {
                $q->where(['model' => $model])->andWhere(['column' => $column]);
            },
        ]);
        $statuses = $query->all();
        
        return ArrayHelper::map($statuses, 'id_status', 'label');
    }
}
