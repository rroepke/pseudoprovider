<?php

/**
 * Register view
 *
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dektrium\user\widgets\Connect;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">

        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
        ]); ?>
        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title)." with Google" ?></h3>
            </div>

            <div class="panel-body">

                <?= Connect::widget([
                    'baseAuthUrl' => ['/user/security/auth']
                ]) ?>
            </div>

        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>

            <div class="panel-body">
                <p>
                    Fields with <b style="color:red;">*</b> are required.
                </p>
                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'email') ?>

                <?php if (Yii::$app->getModule('user')->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
    </div>
</div>