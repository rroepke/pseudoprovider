<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Service */

$service = ($model->type == 'app')?'App':'Web Service';

$this->title = 'Register '.$service;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <!--
    <p>
        Fields with <b style="color:red;">*</b> are required.
    </p>
    -->

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
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
