<?php

use vthang87\workflow\models\Status;
use vthang87\workflow\models\Workflow;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Workflow */
/* @var $form yii\widgets\ActiveForm */

$statuses = ArrayHelper::map(Status::find()->all(), 'id_status', 'label');
?>

<div class="workflow-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'column')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'init_status')->dropDownList(
        $statuses,
        ['prompt' => 'Default status']
    ) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
