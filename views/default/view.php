<?php

use vthang87\workflow\assets\WorkflowAsset;
use vthang87\workflow\models\Workflow;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Workflow */

WorkflowAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workflows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="workflow-view">

        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?= Html::a(
                Yii::t('app', 'Update'),
                ['update', 'id' => $model->id_workflow],
                [
                    'class' => 'btn btn-primary',
                ]
            ) ?>
            <?= Html::a(
                Yii::t('app', 'Delete'),
                ['delete', 'id' => $model->id_workflow],
                [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]
            ) ?>
            <?= Html::a(
                Yii::t('app', 'Create Status'),
                ['status/create', 'id_workflow' => $model->id_workflow],
                ['class' => 'btn btn-success']
            ) ?>
        </p>
        <div class="row">
            <div class="col-md-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        'model',
                        'column',
                        [
                            'attribute' => 'init_status',
                            'value' => $model->initStatus ? $model->initStatus->label : '',
                        ],
                    ],
                ]) ?>

                <?php ActiveForm::begin([
                    'id' => 'form-status-sort',
                ]) ?>
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => [
                        'class' => 'table table-bordered table-striped table-sortable',
                    ],
                    'columns' => [
                        ['class' => SerialColumn::class],
                        'label',
                        [
                            'label' => 'Sort',
                            'value' => function ($model) {
                                return '<span class="handle glyphicon glyphicon-move"></span>' .
                                    Html::hiddenInput('position[]', $model->id_status);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'class' => ActionColumn::class,
                            'template' => '{update} {make-default}',
                            'buttons' => [
                                'make-default' => function ($url, $model, $key) {
                                    return Html::a(
                                        '<span class=" glyphicon glyphicon-star"></span>',
                                        $url,
                                        ['title' => Yii::t('app', 'Make default')]
                                    );
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return Url::to(['status/' . $action, 'id' => $key]);
                            },
                        ],
                    ],
                ]); ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-6">
                <div id="workflow-network">
                    <?= \vthang87\workflow\widgets\WorkflowViewWidget::widget([
                        'workflow' => $model,
                        'containerId' => 'workflow-network',
                    ]); ?>
                </div>
            </div>
        </div>

        <?php $form = ActiveForm::begin(); ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>&nbsp;</td>
                <?php foreach ($model->statuses as $fromStatus) { ?>
                    <td><?= $fromStatus->label ?></td>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->statuses as $fromStatus) { ?>
                <tr>
                    <td><?= $fromStatus->label ?></td>
                    <?php foreach ($model->statuses as $toStatus) { ?>
                        <td>
                            <?= Html::checkbox(
                                "transitions[{$fromStatus->id_status}][{$toStatus->id_status}]",
                                \vthang87\workflow\models\Transition::checkTransition($model->id_workflow, $fromStatus->id_status, $toStatus->id_status),
                                ['disabled' => $fromStatus->id_status === $toStatus->id_status,]
                            ); ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('workflow', 'Save Transition'), ['class' => 'btn btn-success']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php
$url_update_sort = Url::to(['status/update-sort']);
$js = <<<JS
/**
 * Init class .table-sortable
 */
$('td, th', '.table-sortable').each(function () {
    var cell = $(this);
    cell.width(cell.width());
});

if ($('.table-sortable').length) {
    $('.table-sortable tbody').sortable({
        items: '> tr',
        forcePlaceholderSize: true,
        cursor : 'move',
        handle : '.handle',
        placeholder: 'must-have-class',
        start: function (event, ui) {
            // Build a placeholder cell that spans all the cells in the row
            var cellCount = 0;
            $('td, th', ui.helper).each(function () {
                // For each TD or TH try and get it's colspan attribute, and add that or 1 to the total
                var colspan = 1;
                var colspanAttr = $(this).attr('colspan');
                if (colspanAttr > 1) {
                    colspan = colspanAttr;
                }
                cellCount += colspan;
            });
            // Add the placeholder UI - note that this is the item's content, so TD rather thanTR
            ui.placeholder.html('<td colspan="' + cellCount + '">&nbsp;</td>');
        },
        stop: function (event, ui) {
            var data = $("#form-status-sort").serialize();
            $.ajax({
                url: "{$url_update_sort}",
                method: "POST",
                data: data,
                error: function (data) {
                    alert('error');
                    location.reload();
                }
            })
        }
    }).disableSelection();
}
JS;

$this->registerJs($js);

$css = <<<CSS
.handle {
    cursor: pointer;
}

#workflow-network {
    width: 100%;
    height: 400px;
    border: 1px solid lightgray;
    margin-bottom: 10px;
}
CSS;
$this->registerCss($css);
