<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19 0019
 * Time: 下午 3:08
 */

namespace app\models;


use yii\base\Model;

class EntryForm extends Model
{
    public $name;
    public $email;

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
        ];
    }

}