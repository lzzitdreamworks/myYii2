<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Description of RuleController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RouteController extends Controller
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

    public static $routesData = [
        'available' => ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10'],
        'assigned' => ['B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10']
    ];

    /**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', ['routes' => self::$routesData]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->getResponse()->format = 'json';
        $routes = Yii::$app->getRequest()->post('route', '');
        $routes = preg_split('/\s*,\s*/', trim($routes), -1, PREG_SPLIT_NO_EMPTY);

        for($i = count($routes)-1; $i >= 0; $i--) {
            array_unshift(self::$routesData['assigned'], $routes[$i]);
        }
        return self::$routesData;
    }

    /**
     * Assign routes
     * @return array
     */
    public function actionAssign()
    {
        $routes = Yii::$app->getRequest()->post('routes', []);
        for($i = count($routes)-1; $i >= 0; $i--) {
            // 删除一维数组中某一个值元素，使用array_search和array_splice，这里array_splice自动实现重置序列值
            $key = array_search($routes[$i], self::$routesData['available']);
            array_splice(self::$routesData['available'], $key,1);
            // self::$routesData['available'] = array_merge(array_diff(self::$routesData['available'], array($routes[$i])));
            array_unshift(self::$routesData['assigned'], $routes[$i]);
        }
        Yii::$app->getResponse()->format = 'json';
        return self::$routesData;
    }

    /**
     * Remove routes
     * @return array
     */
    public function actionRemove()
    {
        $routes = Yii::$app->getRequest()->post('routes', []);
        for($i = count($routes)-1; $i >= 0; $i--) {
            // 删除一维数组中某一个值元素，array_merge()实现
            self::$routesData['assigned'] = array_merge(array_diff(self::$routesData['assigned'], array($routes[$i])));
            array_unshift(self::$routesData['available'], $routes[$i]);
        }
        Yii::$app->getResponse()->format = 'json';
        return self::$routesData;
    }

    /**
     * Refresh cache
     * @return type
     */
    public function actionRefresh()
    {
        Yii::$app->getResponse()->format = 'json';
        return self::$routesData;
    }

}
