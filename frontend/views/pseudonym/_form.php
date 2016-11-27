<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pseudonym */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pseudonym-form">

    <?php $form = ActiveForm::begin(); ?>

    <input type="hidden" name="service" value="<?= $model->service ?>">
    <input type="hidden" name="cipher" value="<?= $model->cipher ?>">

    <div class="form-group">
        <?= Html::submitButton('Grant', ['name'=>'grant', 'value'=>'grant', 'class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Deny', ['name'=>'deny', 'value'=>'deny', 'class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
