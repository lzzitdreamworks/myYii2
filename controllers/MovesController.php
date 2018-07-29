<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Description of MovesController
 * 左右互移Controller
 * @author Zane Lee <itdreamworks@163.com>
 * @since 1.0
 */
class MovesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                    'refresh' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @var array 用数组模拟数据
     */
    public static $movesData = [
        'available' => ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10'],
        'assigned' => ['B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10']
    ];

    /**
     * 左右互移列表
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', ['moves' => self::$movesData]);
    }

    /**
     * 新建1个或者多个新项到右边栏
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->getResponse()->format = 'json';
        $moves = Yii::$app->getRequest()->post('moves', '');
        $moves = preg_split('/\s*,\s*/', trim($moves), -1, PREG_SPLIT_NO_EMPTY);
        // 保持顺序,先加的在前面
        for($i = count($moves)-1; $i >= 0; $i--) {
            array_unshift(self::$movesData['assigned'], $moves[$i]);
        }
        return self::$movesData;
    }

    /**
     * 选中后向右分配移动 moves,并刷新数据
     * @return array
     */
    public function actionAssign()
    {
        $moves = Yii::$app->getRequest()->post('moves', []);
        for($i = count($moves)-1; $i >= 0; $i--) {
            // 删除一维数组中某一个值元素，使用array_search和array_splice，这里array_splice自动实现重置序列值
            $key = array_search($moves[$i], self::$movesData['available']);
            array_splice(self::$movesData['available'], $key,1);
            // self::$movesData['available'] = array_merge(array_diff(self::$movesData['available'], array($moves[$i])));
            array_unshift(self::$movesData['assigned'], $moves[$i]);
        }
        Yii::$app->getResponse()->format = 'json';
        return self::$movesData;
    }

    /**
     * 选中后向左分配移动,并刷新数据
     * @return array
     */
    public function actionRemove()
    {
        $moves = Yii::$app->getRequest()->post('moves', []);
        for($i = count($moves)-1; $i >= 0; $i--) {
            // 删除一维数组中某一个值元素，array_merge()实现
            self::$movesData['assigned'] = array_merge(array_diff(self::$movesData['assigned'], array($moves[$i])));
            array_unshift(self::$movesData['available'], $moves[$i]);
        }
        Yii::$app->getResponse()->format = 'json';
        return self::$movesData;
    }

    /**
     * 刷新数据
     * @return type
     */
    public function actionRefresh()
    {
        Yii::$app->getResponse()->format = 'json';
        return self::$movesData;
    }

}
