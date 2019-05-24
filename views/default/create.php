<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Workflow */

$this->title = Yii::t('app', 'Create Workflow');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
