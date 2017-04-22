<?php

/**
 * Index view
 *
 * @author Rene Roepke
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
        <?= Html::a('Register Web Service', ['register', 'type' => 'web'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Register App', ['register', 'type' => 'app'], ['class' => 'btn btn-success']) ?>
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
                    return substr($model->description,0,30).((strlen($model->description)>30)?'...':'');
                },
            ],
            'timestamp:datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
