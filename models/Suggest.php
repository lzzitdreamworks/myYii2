<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suggest".
 *
 * @property string $id 自增id
 * @property string $goods_id 商品id
 * @property string $goods_name 商品名称
 * @property string $goods_photo 商品主图
 * @property string $goods_brief 商品简介
 * @property string $add_time 添加时间
 * @property string $update_time 更新时间
 */
class Suggest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suggest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'goods_name', 'goods_photo', 'goods_brief', 'add_time', 'update_time'], 'required'],
            [['goods_id', 'add_time', 'update_time'], 'integer'],
            [['goods_name'], 'string', 'max' => 50],
            [['goods_photo', 'goods_brief'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'goods_photo' => 'Goods Photo',
            'goods_brief' => 'Goods Brief',
            'add_time' => 'Add Time',
            'update_time' => 'Update Time',
        ];
    }
}
