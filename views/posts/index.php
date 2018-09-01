<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'title',
            'author',
//            'content:ntext',
//            'status',
            [
                'attribute' => 'status',
                'value'=>function ($model,$key,$index,$column){
                    return $model->status==1?'正常':'已删除';
                },
                //在搜索条件（过滤条件）中使用下拉框来搜索
                'filter' => ['1'=>'正常','0'=>'已删除'],
                //or
                'filter' => Html::activeDropDownList($searchModel,
                    'status',['1'=>'正常','0'=>'已删除'],
                    ['prompt'=>'全部']
                )
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
            'header' => '操作',

        ],
    ]); ?>
</div>
