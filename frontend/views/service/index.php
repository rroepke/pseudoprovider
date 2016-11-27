<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Services';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Service', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>   'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->name),['service/view','id'=>$model->id]);
                },
            ],
            'url:raw',
            [
                'attribute' =>   'description',
                'format' => 'raw',
                'value' => function ($model) {
                    return substr($model->description,0,20).((strlen($model->description)>20)?'...':'');
                },
            ],
            'timestamp:datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
