<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $moves [] */
/* @author Zane Lee <itdreamworks@163.com>
 * @since 1.0
 */

$this->title = "左右互移Demo";
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);
$opts = Json::htmlEncode([
    'moves' => $moves,
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<h1><?= Html::encode($this->title); ?></h1>
<div class="row">
    <div class="col-sm-11">
        <div class="input-group">
            <input id="inp-moves" type="text" class="form-control"
                   placeholder="B11,B12,B13">
            <span class="input-group-btn">
                <?= Html::a('Add' . $animateIcon, ['create'], [
                    'class' => 'btn btn-success',
                    'id' => 'btn-new',
                ]); ?>
            </span>
        </div>
    </div>
</div>
<p>&nbsp;</p>
<div class="row">
    <div class="col-sm-5">
        <div class="input-group">
            <input class="form-control search" data-target="available"
                   placeholder="Search for available">
            <span class="input-group-btn">
                <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh'], [
                    'class' => 'btn btn-default',
                    'id' => 'btn-refresh',
                ]); ?>
            </span>
        </div>
        <select multiple size="20" class="form-control list" data-target="available"></select>
    </div>
    <div class="col-sm-1">
        <br><br>
        <?= Html::a('&gt;&gt;' . $animateIcon, ['assign'], [
            'class' => 'btn btn-success btn-assign',
            'data-target' => 'available',
            'title' => '右移',
        ]); ?><br><br>
        <?= Html::a('&lt;&lt;' . $animateIcon, ['remove'], [
            'class' => 'btn btn-danger btn-assign',
            'data-target' => 'assigned',
            'title' => '左移',
        ]); ?>
    </div>
    <div class="col-sm-5">
        <input class="form-control search" data-target="assigned"
               placeholder="Search for assigned">
        <select multiple size="20" class="form-control list" data-target="assigned"></select>
    </div>
</div>
