<?php
/**
 * Created by PhpStorm.
 * User: zane lee
 * Date: 2018/8/25
 * Time: 下午 14:15
 */

$this->title = 'bootstrap combox 搜索建议插件';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="Register-search">
    <form action="" method="get" id="forms">
        <div class="search">
            <span><p style="color:#398439"><strong>请输入goods_id：多个goods_id之间用英文逗号,隔开如：16,32，并展示列表表头。</strong></p>
                <div class="row">

                    商品名称: <div class="input-group" style="width: 25%">
                      <input type="text" id="keyword" name='keyword' class="form-control"
                             placeholder='<?php if(isset($param['keyword']) && !empty($param['keyword'])) {
                                 if (is_array($param['keyword'])) {
                                     echo implode(',', $param['keyword']);
                                 } else { echo $param['keyword']; }
                             } else {echo "商品名用英文,隔开如: 欧时纳,梦特娇";}?>'/>
                           <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu"></ul>
                           </div>
                      </div>

                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" id="goods_ids" name='goods_ids' class="form-control"
                                   placeholder='<?php if (isset($get['goods_ids'])) { echo $get['goods_ids'];} else {echo "多个goods_id之间用英文逗号,隔开如：16,32";}?>'/>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                </ul>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" name='back_ids' id='back_ids'
                           value='<?php if (isset($get['goods_ids'])) { echo $get['goods_ids'];} ?>'/>

                    <span>&nbsp;&nbsp;&nbsp;&nbsp;支付时间：<input class="form-control" style="width:10%; display:inline;" placeholder='开始时间' name="start_time" id="start_time"
                                                              value='<?php if (isset($get['goods_ids'])) { echo $get['start_time'];} ?>'/> ~
                    <span><input class="form-control" style="width:10%; display:inline;" placeholder='截止时间' name="end_time" id="end_time"
                                 value='<?php if (isset($get['goods_ids'])) { echo $get['end_time'];} ?>'/></span></span>

                    <?php foreach ($get AS $key => $val) { ?>
                        <?php if (in_array($key, array('start_time', 'end_time', 'goods_ids'))) continue; ?>
                        <input type='hidden' name='<?php echo $key ?>' value='<?php echo $val; ?>'/>
                    <?php } ?>
                    <a class="btn btn-primary" onclick="return searchs()" style="margin-left:8px;width:60px;">搜索</a>
                    <a class="btn btn-primary" onclick="reset()" style="margin-left:8px;width:60px;">重置</a>
                    <a class="btn btn-primary" onclick="export_data()" style="margin-left:8px;width:60px;">导出</a>
                </div>
            </span>
        </div>
    </form>
</div>

<br/><br/><br/>
<table class="table content" style='width:80%;'>
    <thead>
    <tr>
        <th style="min-width:6%;">序号</th>
        <th style="min-width:16%;">分销店铺名称</th>
        <th style="min-width:10%;">商品goods_id</th>
        <th style="min-width:20%;">商品名称</th>
        <th style="min-width:12%;">购买的商品数量</th>
    </tr>
    </thead>

    <?php foreach ($goods_data['data'] AS $key => $val) { ?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $val['store_name']; ?></td>
            <td><?php echo $val['goods_id']; ?></td>
            <td><?php echo $val['goods_name']; ?></td>
            <td><?php echo $val['goods_num']; ?></td>
        </tr>
    <?php } ?>
</table>

<script src="<?php echo Yii::$app->request->baseUrl?>/js/xlsx.core.min.js"></script>
<script src="<?php echo Yii::$app->request->baseUrl?>/js/downLoad_excl.js"></script>
<script src="<?php echo Yii::$app->request->baseUrl?>/js/bootstrap-suggest.js"></script>

<script>

    var search =$("#goods_ids").val();
    /**
     * 淘宝搜索 API 测试
     */
    $("#goods_ids").bsSuggest({
        indexId: 2,             //data.value 的第几个数据，作为input输入框的内容
        indexKey: 1,            //data.value 的第几个数据，作为input输入框的内容
        allowNoKeyword: false,  //是否允许无关键字时请求数据。为 false 则无输入时不执行过滤请求
        multiWord: true,        //以分隔符号分割的多关键字支持
        separator: ",",         //多关键字支持时的分隔符，默认为空格
        getDataMethod: "url",   //获取数据的方式，总是从 URL 获取
        showHeader: true,       //显示多个字段的表头
        autoDropup: true,       //自动判断菜单向上展开
        effectiveFieldsAlias:{Id: "序号", Keyword: "goods_id", Count: "商品名称"},
        //url: 'http://suggest.taobao.com/sug?code=utf-8&extras=1&q=', /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
        url: '/index.php?r=sales/statis&code=utf-8&extras=1&goods_ids='+search, /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
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
                    "Keyword": json.result[index][0],
                    "Count": json.result[index][1]
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
        var goods_ids = $('[name="goods_ids"]').val();
        if ($.trim(goods_ids, ',') == '') {
            //alert( '请输入要查找的goods_ids' );
            //return false;
        }
        //获取要搜索的goods_ids
        //$('#forms').submit();
        var start_time=$('#start_time').val();
        var end_time=$('#end_time').val();

        var params = "&goods_ids="+goods_ids+"&start_time="+start_time+"&end_time="+end_time;
        location.href = "/index.php?r=sales/statistics"+params;
    }

    function export_data(){
        var goods_ids = encodeURIComponent($('#back_ids').val());
        var condition = $('#forms input,select').serializeArray();
        var params = '';
        $.each(condition, function(index, element){
            params += element.name+'='+encodeURIComponent(element.value)+'&';
        });
        params = params.substr(0, params.length-1);
        $.ajax({
            url:"/index.php?r=sales/statistics&"+params+"&export=1"+"&goods_ids="+goods_ids,
            type:'get',
            dataType:'json',
            success:function(res){
                downloadExcl(res.title, res.data,'会员特价商品销量统计');
            }
        });
    }
</script>