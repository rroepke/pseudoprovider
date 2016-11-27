<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = 'Create Service';
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'chiffres' => $chiffres,
        'hashes' => $hashes,
        'model' => $model,
    ]) ?>

</div>
