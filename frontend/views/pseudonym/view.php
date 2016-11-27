<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pseudonym */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pseudonyms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pseudonym-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user',
            'pseudonym:ntext',
        ],
    ]) ?>

</div>
