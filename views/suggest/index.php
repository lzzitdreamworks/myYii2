<?php
/* @var $this yii\web\View */
use yii\widgets\LinkPager;
?>
<h4>bootstrap combox 搜索建议插件之淘宝搜索</h4>

<div class="push-search">
    <form class="form-inline" action="" method="get" id="forms">
        <div class="search">
            商品名称: <div class="input-group" style="width: 30%">
                <input type="text" id="keyword" name='keyword' class="form-control" placeholder='商品名用英文,隔开如: 欧时纳,梦特娇',
                       value='<?php if(isset($keyword) && !empty($keyword)) {
                           if (is_array($keyword)) {
                               echo implode(',', $keyword);
                           } else { echo $keyword; }
                       } ?>'/>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>
                </div>
            </div>
            <a class="btn btn-primary" onclick="return searchs()" style="margin-left:8px;width:60px;">搜索</a>
            <a class="btn btn-primary" onclick="reset()" style="margin-left:8px;width:60px;">重置</a>
        </div>
    </form>
    <div class="clear"></div>
</div>

<br/><br/>
<table class="table content" style='width:80%;'>
    <thead>
    <tr>
        <th style="min-width:6%;">序号</th>
        <th style="min-width:10%;">商品goods_id</th>
        <th style="min-width:20%;">商品名称</th>
        <th style="min-width:12%;">商品简介</th>
    </tr>
    </thead>

    <?php foreach ($goods_data AS $key => $val) { ?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $val['goods_id']; ?></td>
            <td><?php echo $val['goods_name']; ?></td>
            <td><?php echo $val['goods_brief']; ?></td>
        </tr>
    <?php } ?>
</table>

<?= LinkPager::widget(['pagination' => $pages]); ?>

<script type="text/javascript" src="<?php echo Yii::$app->request->baseUrl?>/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo Yii::$app->request->baseUrl?>/js/bootstrap-suggest.js"></script>

<script type="text/javascript">
    var search = '';
    $("#keyword").keyup(function(){
        search = $(this).val();
    }),focus(function(){
        search = $(this).val();
    })
    // var search = $("#keyword").val();
    /**
     * 淘宝搜索
     */
    $("#keyword").bsSuggest({
        indexId: 3,             //data.value 的第几个数据，作为input输入框的内容
        indexKey: 2,            //data.value 的第几个数据，作为input输入框的内容
        allowNoKeyword: false,  //是否允许无关键字时请求数据。为 false 则无输入时不执行过滤请求
        multiWord: true,        //以分隔符号分割的多关键字支持
        separator: ",",         //多关键字支持时的分隔符，默认为空格
        getDataMethod: "url",   //获取数据的方式，总是从 URL 获取
        showHeader: true,       //显示多个字段的表头
        autoDropup: true,       //自动判断菜单向上展开
        effectiveFieldsAlias:{Id: "序号", GoodsId: "goods_id" , GoodsName: "商品名称" },
        //url: 'http://suggest.taobao.com/sug?code=utf-8&extras=1&q=', /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
        url: '/index.php?r=suggest/storestatis&code=utf-8&extras=1&keyword='+search, /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
        jsonp: 'callback',               //如果从 url 获取数据，并且需要跨域，则该参数必须设置
        // url 获取数据时，对数据的处理，作为 fnGetData 的回调函数
        fnProcessData: function(json){
            console.log('淘宝搜索 API: ', json);
            json = JSON.parse(json);

            var index, len, data = {value: []};

            if(! json || ! json.result || ! json.result.length) {
                return false;
            }

            len = json.result.length;

            for (index = 0; index < len; index++) {
                data.value.push({
                    "Id": (index + 1),
                    "GoodsId": json.result[index][0],
                    "GoodsName": json.result[index][1]
                });
            }

            return data;
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', JSON.parse(result));
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
    }).on('onUnsetSelectValue', function () {
        console.log("onUnsetSelectValue");
    });

    function reset(){
        $('form .search input').val('');
    }

    function searchs() {
        var keyword = $('[name="keyword"]').val();
        if ($.trim(keyword, ',') == '') {
            //alert( '请输入要查找的goods_ids' );
            //return false;
        }
        //获取要搜索的goods_ids
        //$('#forms').submit();
        var start_time=$('#start_time').val();
        var end_time=$('#end_time').val();

        var params = "&keyword="+keyword;
        location.href = "/index.php?r=suggest/index"+params;
    }

</script>
