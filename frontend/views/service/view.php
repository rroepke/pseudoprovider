<?php

/**
 * Service view
 *
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$suffix = $model->type;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->created_by == Yii::$app->user->id): ?>

        <?= Html::a('Update', ['update', 'id' => $model->id, 'type' => $model->type], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?php $attr = ['name'];?>

    <?php if ($model->type == 'web') {
        $attr[] = 'url:url';
    }?>

    <?php $attr[] = 'description';?>

    <?php $attr[] = 'timestamp:datetime';?>

    <?php if ($model->type == 'web') {
        $attr[] = [
            'label' => Html::encode('Image URL'),
            'format' => 'raw',
            'value' => (strlen($model->image_url)==0)?'<span class="not-set">(not set)</span>':Html::a(Html::encode($model->image_url),$model->image_url),//" MAC over plaintext ".Html::tag("b","JSON")." string for integrity and authenticity",
        ];
    } ?>

    <?php
        if ($model->type == 'web' && $model->created_by == Yii::$app->user->id) {
            $attr[] = 'return_url:url';
        }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attr,
    ]) ?>

    <?php if ($model->created_by == Yii::$app->user->id): ?>

    <h2><?= Html::encode("Installation") ?></h2>

    <?php $attr = [
        'token',
        'cipher',
        'hash',
        [
            'label' => Html::encode('Request URL'),
            'value' => Html::encode(Url::toRoute('pseudonym/request', true)),//Yii::$app->homeUrl . "/frontend/web/pseudonym/request"),
        ],
        [
            'label' => Html::encode('Required parameters'),Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","service")." - Name used for service registration, i.e. ".Html::tag('b',$model->name),
        ],
        [
            'label' => Html::encode(''),
            'format' => 'raw',
            'value' => Html::tag("code","ciphertext")." - Encrypted ".Html::tag("b","JSON")." string containing:".Html::tag('br').
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
            'value' => Html::tag("code","ciphertext")." - Encrypted ".Html::tag("b","JSON")." string containing:".Html::tag('br').
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

    <?php endif; ?>
</div>
