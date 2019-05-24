<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \vthang87\workflow\models\Workflow */

$this->title = Yii::t('app', 'Update Workflow: ' . $model->name, [
    'nameAttribute' => '' . $model->id_workflow,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_workflow]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="workflow-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
