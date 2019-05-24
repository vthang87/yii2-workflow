<?php
/**
 * Created by PhpStorm.
 * User: dangvanthang
 * Date: 5/29/18
 * Time: 4:58 PM
 */

namespace vthang87\workflow\behavior;

use vthang87\workflow\helpers\WorkflowHelper;
use vthang87\workflow\models\Workflow;
use yii\base\Event;
use yii\db\ActiveRecord;

class WorkflowBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'workFlowValidate',
        ];
    }

    public function workFlowValidate(Event $event)
    {
        if ($this->owner instanceof \yii\db\BaseActiveRecord) {
            /** @var ActiveRecord $owner */
            $owner = $this->owner;
            $workflows = Workflow::findAll(['model' => get_class($owner)]);
            if (count($workflows)) {
                foreach ($workflows as $workflow) {
                    if ($owner->isNewRecord) {
                        if (empty($owner->getOldAttribute($workflow->column)) && empty($owner->getAttribute($workflow->column))) {
                            $owner->setAttribute($workflow->column, $workflow->init_status);
                        } else {
                            if ($owner->getAttribute($workflow->column) != $workflow->init_status) {
                                $this->owner->addError($workflow->column, $workflow->column . ' invalid status');
                            }
                        }
                    } else {
                        $from_status = $owner->getOldAttribute($workflow->column);
                        $to_status = $owner->getAttribute($workflow->column);
                        if ($from_status != $to_status &&
                            !WorkflowHelper::validNextStatus($workflow->id_workflow, $from_status, $to_status)) {
                            $this->owner->addError($workflow->column, $workflow->column . ' invalid next status');
                        }
                    }
                }
            }
        }
    }
}
