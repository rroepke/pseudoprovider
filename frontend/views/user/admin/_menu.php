<?php

/**
 * Menu view
 *
 * @author Rene Roepke
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use yii\bootstrap\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label'   => Yii::t('user', 'User Management'),
            'url'     => ['/user/admin/index'],
        ],
    ],
]) ?>
