<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
    
    <head>
  <meta charset="UTF-8">
  
  <title><?php echo L('USER_INDEX_XGHQYH');?></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

  <link rel="stylesheet" href="/Public/layui/css/layui.css">
  <link rel="stylesheet" href="/Public/layui/css/admin.css">

</head>
<body>
<style>

    .laber-height{line-height: 34px;}
    .zfb,.wx,.yhk,.submit{display:none;}
    .line{line-height:30px!important;}
</style>

<div class="layui-tab-content" >
    <!-- 网站设置 -->
    <div id="layer-photos-demo" class="layer-photos-demo layui-tab-item layui-show" ">
		<div class="layui-fluid">
			<div class="layui-row layui-col-space15">
				<div class="layui-col-md12">
					<div class="layui-card">



						<div class="demoTable" style="padding:10px;background-color:#e6e6e6;">用户名：
							<div class="layui-inline">
								<input class="layui-input" name="id" type="hidden" id="demoReload" autocomplete="off">
								<input class="layui-input" name="username" id="username" autocomplete="off">
							</div>
							<div class="layui-inline">
								<label class="layui-form-label">银行卡号</label>
								<div class="layui-input-inline">
									<input type="text" class="layui-input" name="bankcard" id="bankcard" style="width:200px;" >
								</div>
							</div>
							<div class="layui-inline">
								<label class="layui-form-label">日期范围</label>
								<div class="layui-input-inline">
									<input type="text" class="layui-input" name="addtime" id="test6" style="width:200px;" placeholder="/">
								</div>
							</div>
							<button class="layui-btn" data-type="reload">搜索</button>
						</div>

						<div class="layui-card-body ">
							
							<table class="layui-hide" id="test-table-data" lay-filter="test"></table>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <!-- 网站设置结束 -->
</div>

</body>
<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="detail">查看</a>
	<a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
</script>

<script src="/Public/layui/layui.js"></script>
<script>
    layui.use(['form', 'layedit', 'upload' , 'jquery','table','laydate'], function(){
        var form = layui.form
            ,$ = layui.jquery
            ,layer = layui.layer
            ,upload = layui.upload
            ,laydate = layui.laydate
            ,table = layui.table;


        //展示已知数据
    	table.render({
			elem: '#test-table-data'
			,url:'<?php echo U("YPayList");?>'
			,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
				layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
				,curr: 1 //设定初始在第 5 页
				,groups: 8 //只显示 1 个连续页码
				,first: false //不显示首页
				,last: false //不显示尾页
		    }
			,cols: [[ //标题栏
				{field: 'id', title: 'ID', width: 80, sort: true}
				,{field: 'username', title: '用户名', width: 100 }
				,{field: 'cardid', title: '身份证号', width: 220}
				,{field: 'bank', title: '银行支行', width: 250}
				,{field: 'bankcard', title: '银行卡号', minWidth: 260}
				,{field: 'money', title: '金额', minWidth: 160}
				,{field: 'addtime', title: '添加时间', width: 200}
				//,{field: 'cz', title: '操作', width: 200 , templet:'#toolbar'}
			]]
			,height:"600px"
			,id: 'testReload'
			,username: 'testReload'
			,bankcard: 'testReload'
			,addtime: 'testReload'
		});


		/*监听工具条*/ 
		table.on('tool(test)', function(obj){ 
			var data = obj.data; /*获得当前行数据*/ 
			var layEvent = obj.event; /*获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）*/ 
			var tr = obj.tr; /*获得当前行 tr 的DOM对象*/ 
			if(layEvent === 'detail'){
				layer.msg(data.id);
			}else if(layEvent === 'del'){ 
				layer.confirm('真的删除行么', function(index){ 
					layer.close(index); /*向服务端发送删除指令*/ 
					layer.msg(data.id); 
					// $.ajax({ 
					// 	url : prefix + "/delete", type : "post", data : {'id' : data.id}, 
					// 	success : function(data) { 
					// 		if (data.code == 0) { 
					// 			obj.del(); /*删除对应行（tr）的DOM结构，并更新缓存*/ 
					// 			layer.msg(data.msg);/*后台定义的返回值方法*/ 
					// 		} else { 
					// 			layer.msg(data.msg); 
					// 		} 
					// 	} 
					// }); 
				}); 
			} 
		});


		//搜索数据
		form.on('submit(submits)',function(data){

			layer.msg('ssss');
		});


    	//日期范围
		laydate.render({
			elem: '#test6'
			,format: 'yyyy-MM-dd'
			,range: true
		});
		

		var $ = layui.$, active = {
			reload: function(){
				var demoReload = $('#demoReload');
				var username = $('#username');
				var bankcard = $('#bankcard');
				var test6 = $('#test6');
				//执行重载
				table.reload('testReload', {
					page: {
						curr: 1 //重新从第 1 页开始
					}
					,where: {
						key: {
							id: demoReload.val()
							,username:username.val()
							,bankcard:bankcard.val()
							,addtime:test6.val()
						}
					}
				});
			}
		};

		$('.demoTable .layui-btn').on('click', function(){
			var type = $(this).data('type');
			active[type] ? active[type].call(this) : '';
		});

		
    });

    // $(function(){
    //     /**
    //      * [数据提交]
    //      */
    //     form.on('submit(demo2)', function(data){
    //         if(data.field.zfpz1 == "" && data.field.zfpz2 == "" && data.field.zfpz3 == ""){
    //             layer.msg('请上传您的付款截图', {icon: 5, shade: 0.1});
    //             return false;
    //         }

    //         var data_info = data.field;
    //         var tj_url = "{{ url('pay/recharge') }}";
    //         var load_ing = layer.load(3);
    //         $.post(tj_url, data_info, function (res) {
    //             layer.close(load_ing);
    //             if(res.status == 1){
    //                 $('.wz-pay-box').hide();
    //                 $('.recharge-completed-box').show();
    //             }else{
    //                 layer.msg('网络异常，请稍后重试', {icon: 5, shade: 0.1});
    //             }
    //         }, 'json');
    //         return false;
    //     });
        
    // });

</script>


</html>