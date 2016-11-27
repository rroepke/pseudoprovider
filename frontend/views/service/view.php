<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->created_by == Yii::$app->user->id): ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?php $attr = [
        'name',
        'url:url',
        'description',
        'timestamp:datetime',
        [
            'label' => Html::encode('Image URL'),
            'format' => 'raw',
            'value' => is_null($model->image_url)?$model->image_url:Html::a(Html::encode($model->image_url),$model->image_url),//" MAC over plaintext ".Html::tag("b","JSON")." string for integrity and authenticity",
        ],
    ]; ?>

    <?php
        if ($model->created_by == Yii::$app->user->id) {
            $attr[] = 'return_url:url';
        }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attr,
    ]) ?>

    <h2><?= Html::encode("Installation") ?></h2>

    <?php $attr = [
        'token',
        'chiffre',
        'hash',
        [
            'label' => Html::encode('Request URL'),
            'value' => Html::encode("http://localhost/advanced/frontend/web/index.php?r=pseudonym/request"),
        ],
        [
            'label' => Html::encode('Required parameters'),Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","service")." - Name used for service registration, i.e. ".Html::tag('b',$model->name),
        ],
        [
            'label' => Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","cipher")." - Encrypted ".Html::tag("b","JSON")." string containing:".Html::tag('br').
                Html::tag('ul',
                    Html::tag('li',
                        Html::tag("i","service")." - Name used for service registration, i.e. ".Html::tag('b',$model->name)
                    ).
                    Html::tag('li',
                        Html::tag("i","timestamp")." - Current Unix timestamp"
                    )
                ),
        ],
        [
            'label' => Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","mac")." - MAC over plaintext ".Html::tag("b","JSON")." string for integrity and authenticity",
        ],
        [
            'label' => Html::encode('Response parameters'),
            'format' => 'raw',
            'value' => Html::tag("code","code")." - SUCCESS or FAIL",
        ],
        [
            'label' => Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","cipher")." - Encrypted ".Html::tag("b","JSON")." string containing:".Html::tag('br').
                Html::tag('ul',
                    Html::tag('li',
                        Html::tag("i","code")." - SUCCESS or FAIL"
                    ).
                    Html::tag('li',
                        Html::tag("i","timestamp")." - Current Unix timestamp"
                    ).
                    Html::tag('li',
                        Html::tag("i","pseudonym")." - Requested pseudonym in case of SUCCESS"
                    )
                ),
        ],
        [
            'label' => Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","mac")." - MAC over plaintext ".Html::tag("b","JSON")." string for integrity and authenticity",
        ],
    ]; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attr,
    ]) ?>

    <p>
        You can download a PHP-client to connect to the Pseudonymity Provider more easily. To download click <?= Html::a(Html::encode("here"),['service/download'])?>.
    </p>

</div>
