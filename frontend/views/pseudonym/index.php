<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pseudonyms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pseudonym-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pseudonym', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user',
            'pseudonym:ntext',
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
    ]); ?>
</div>
