<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;

$this->title = "Pseudonym Request";

?>
<h1><?= $this->title ?></h1>
<div class="row"><div class="col-md-12">
    <h4>
        <?= '<a href=" '.$service->url.'">'.$service->name."</a>" ?> requests your pseudonym.
    </h4>
    </div>
</div>
<div class="row">
    <div class="col-xs-2 col-sm-2">
        <img width="110" src="<?= $service->image_url?>" alt="Service image is not available.">
    </div>
    <div class="col-xs-10 col-sm-10">
        <p>
            <?php if (strlen($service->description)>150): ?>
            <h4>
                Service description:
                <button id="showhidebutton" class="btn btn-xs" onclick="changeCaption()" data-toggle="collapse" data-target="#demo">Show</button>
            </h4>
        <div id="demo" class="collapse">
            <?= $service->description ?>
        </div>
        <?php else: ?>
            <h4>
                Service description:
            </h4>
            <div>
                <?= $service->description ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="row margin-top-10">
    <div class="col-xs-12 col-sm-12">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

