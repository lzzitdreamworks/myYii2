<?php

namespace app\controllers;

use Yii;
use app\models\Suggest;
use yii\data\Pagination;

class SuggestController extends \yii\web\Controller
{
    //todo 未完善
    public function actionIndex($page = 1, $size = 10)
    {
        $goodsList = Suggest::find()->orderBy('goods_id DESC');
        $keyword = \Yii::$app->request->post('keyword');
        $keyword = strpos($keyword,',') !== false ? explode(",", $keyword) : $keyword;

        if ($keyword) {
            $goodsList->andFilterWhere(['OR LIKE', "goods_id", $keyword])
                ->orFilterWhere(['OR LIKE', "goods_name", $keyword]);
            //  ->asArray()->all();
            //  $sql = $goodsList->createCommand()->getRawSql();
            //  echo "<pre>sql: "; print_r($sql); exit;
        }
        $pages = new Pagination(['totalCount' => $goodsList->count(), 'pageSize' => $size]);
        $goodsList = $goodsList->offset(($page - 1) * $size)->limit($size)->asArray()->all();

        return $this->render('search', [
            'goods_data' => $goodsList,
            'keyword' => $keyword,
            'pages' => $pages,
        ]);
    }

    /**
     * @desc   同时支持多goods_id和goods_name作为关键字模糊匹配，联想记忆
     * @access public
     * @author zane lee 2018-08-25 下午3:50:17
     * @return array|string
     */
    public function actionStorestatis(){
        $get = \Yii::$app->request->get();
        $callback = Yii::$app->request->get('callback','');
        $keyword = isset($get['keyword']) ? $get['keyword'] : '';

        $rows = (new \yii\db\Query())
            ->select(['goods_id', 'goods_name'])
            ->from('suggest')
            ->where(['like', 'goods_id', $keyword])
            ->orWhere(['like', 'goods_name', $keyword])
            ->limit(20)
            ->all();

        $formatDat = [];
        foreach ($rows AS $index => $value) {
            foreach ($value AS $val) {
                $formatDat["result"][$index][] = $val;
            }
        }
        $result = json_encode($formatDat);
        unset($rows, $formatDat);

        $format = $callback  ? \yii\web\Response::FORMAT_JSONP : \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->format = $format;

        if($callback){
            return[
                'callback' => $callback,
                'data' => $result
            ];
        }
        return $result;
    }

}
