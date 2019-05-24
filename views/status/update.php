<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \vthang87\workflow\models\Status */

$this->title = Yii::t('app', 'Update Status: ' . $model->label);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflows'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->workflow->name,
    'url' => ['default/view', 'id' => $model->id_workflow],
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update: ' . $model->label);
?>
<div class="status-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
