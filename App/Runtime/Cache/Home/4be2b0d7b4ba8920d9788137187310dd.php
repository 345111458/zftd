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

						<div class="demoTable" style="padding:10px;background-color:#e6e6e6;">
							<div class="layui-inline">
								<input class="layui-input" name="id" type="hidden" id="demoReload" autocomplete="off">
							</div>
							<div class="layui-inline">
								<label class="layui-form-label">支付状态:</label>
								<div class="layui-input-inline">
									<select name="status" id="status" lay-filter="province" class="layui-input" style="width:80px;">
										<option value="">-请选择-</option>
										<option value="9">未调用</option>
										<option value="1">已调用</option>
										<option value="2">支付失败</option>
									</select>
								</div>
							</div>
							<button class="layui-btn" data-type="reloads">搜索</button>
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

<script src="/Public/layui/layui.js"></script>
<script type="text/html" id="sexTpl">
    <!-- //此处的 d.status 是对应数据库里的 字段 status ，必须以 d. 开头 -->
	{{# if(d.status === '0'){ }}  
		<div style="color: white; background-color: #3B3B3B" align="center">未调用</div>
	{{#  }else
	if(d.status === '1'){ }}
		<div style="color: white; background-color: #009688" align="center">已调用</div>
	{{#  }else
	if(d.status === '2'){ }}
		<div style="color: white; background-color: #ff5722" align="center" >支付失败</div>
	{{#  } }}
</script>
<script type="text/html" id="toolbar">
	<a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="detail" >立即支付</a>
	<a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
</script>
<script>
    layui.use(['form', 'layedit', 'upload' , 'jquery','table','laydate'], function(){
        var form = layui.form
            ,$ = layui.jquery
            ,layer = layui.layer
            ,upload = layui.upload
            ,laydate = layui.laydate
            ,table = layui.table;


        form.render();
        //展示已知数据
    	table.render({
			elem: '#test-table-data'
			,url:'<?php echo U("WPayList");?>'
			,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
				layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
				,curr: 1 //设定初始在第 5 页
				,groups: 8 //只显示 1 个连续页码
				,first: false //不显示首页
				,last: false //不显示尾页
		    }
			,even:true
			,cols: [[ //标题栏
				{field: 'id', title: 'ID', width: 80, sort: true}
				,{field: 'username', title: '用户名', width: 100 }
				,{field: 'cardid', title: '身份证号', width: 200}
				,{field: 'bank', title: '银行支行', width: 150}
				,{field: 'bankcard', title: '银行卡号', minWidth: 180}
				,{field: 'money', title: '金额', minWidth: 70}
				,{field: 'money', title: '状态', minWidth: 160,templet:'#sexTpl'}
				,{field: 'result_code', title: '回调', minWidth: 200}
				,{field: 'addtime', title: '添加时间', width: 200}
				,{field: 'cz', title: '操作', width: 200 , templet:'#toolbar'}
			]]
			,height:"600px"
			,id: 'testReload' // 分页参数
			,status: 'testReload' // 分页参数
			,done:function(res){
				layer.closeAll();
			}
		});

    	// 分页传参数 开始
		var $ = layui.$, active = {
			reloads: function(){
                // id 这一句是必须有，不写就报错
                var demoReload = $('#demoReload');  
				var status = $('#status');

				//执行重载
				table.reload('testReload', {
					page: {
						curr: 1 //重新从第 1 页开始
					}
					,where: {
						key: {
                            //id 这一句是必须有，不写就报错
                            id: demoReload.val()   
							,status:status.val()
						}
					}
				});
			}
		};

		$('.demoTable .layui-btn').on('click', function(){
			var type = $(this).data('type');
			active[type] ? active[type].call(this) : '';
		});
    	// 分页传参数 结束


		/*监听工具条*/ 
		table.on('tool(test)', function(obj){ 
			var data = obj.data; /*获得当前行数据*/ 
			var layEvent = obj.event; /*获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）*/ 
			var tr = $(obj.tr).siblings().length; /*获得当前行 tr 的DOM对象*/ 

			if(layEvent === 'detail'){
				// layer.msg(data.id);
                layer.load(3,{shade:0.3});
                var url = "<?php echo U('yiJiFu');?>";
                $.post(url , {oid:data.id} , function(res){
                    layer.closeAll();
                    if (res.status == '1') {
                        layer.msg(res.info,{},function(){
                            obj.del(); /*删除对应行（tr）的DOM结构，并更新缓存*/
                        })
                    }else{
                        layer.msg(res.info,{},function(){
                        	// location.reload();
                        });
                    }
                });
			}else if(layEvent === 'del'){ 
				layer.confirm('真的删除行么', function(index){ 
					layer.close(index); /*向服务端发送删除指令*/ 
					// layer.msg(data.id); 
					$.ajax({ 
						url : "Yfdel", 
                        type : "post", 
                        data : {'id': data.id}, 
						success : function(data) { 
							if(data.status == 0){ 
                                layer.msg(data.info,{icon:5,time:1000});
							}else{
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存 
                                layer.msg(data.info,{icon:6,time:1000},function(){});/*后台定义的返回值方法*/ 
                                if (tr == 0) {
                                    location.reload();
                                }
                            } 
						} 
					}); 
				}); 
			}
		}); 


    	// 点击之后进行 ajax 轮训 开始
		form.on('submit(CallPay)',function(data){
			CallPay();
		});
    	//ajax 轮训
		window.CallPay = function(){
			layer.load(3,{shade:0.3});
			var url = "<?php echo U('CallPay');?>";
			$.post(url , "" , function(res){
                layer.closeAll();
				if (res.status == '1') {
					layer.msg(res.info,{},function(){
						location.reload();
					})
				}else{
                    layer.msg(res.info);
				}
			});
		}
    	// 点击之后进行 ajax 轮训 结束



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
    });
</script>


</html>