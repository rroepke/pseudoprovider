<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$service = ($model->type == 'app')?'App':'Web Service';

$this->title = 'Update ' . $service . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="service-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="service-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= ($model->type == 'app') ? '' : $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <?= ($model->type == 'app') ? '' : $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>

        <?= ($model->type == 'app') ? '' : $form->field($model, 'return_url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'cipher')->dropDownList($ciphers) ?>

        <?= $form->field($model, 'hash')->dropDownList($hashes) ?>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
