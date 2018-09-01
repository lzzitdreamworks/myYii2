<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Posts extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     * 添加了 TimestampBehavior 行为，这个行为的作用是在新建一条数据时，
     * 自动插入created_at 和 update_at ,修改一条数据时自动更新update_at。
     * 设置行为后，必须去掉 验证规则里关于created_at 和 update_at 的验证，不然会冲突。
     */
    public function rules()
    {
        return [
            //[['title', 'author', 'created_at', 'updated_at'], 'required'],
            [['title', 'author'], 'required'],
            [['content'], 'string'],
            [['status'], 'integer'],
            [['title', 'author'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'author' => 'Author',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
