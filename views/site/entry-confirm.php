<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19 0019
 * Time: 下午 3:28
 */

use yii\helpers\Html;
?>
<p>You have entered the following information:</p>

<ul>
    <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
    <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
</ul>