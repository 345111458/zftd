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
    <div id="layer-photos-demo" class="layer-photos-demo layui-tab-item layui-show" style="width:40%;">
        <form class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-block">
                    <input type="text" name="username" value="" placeholder="" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">银行支行</label>
                <div class="layui-input-block">
                    <input type="text" name="bank" value="" placeholder="" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">银行卡号</label>
                <div class="layui-input-block">
                    <input type="text" name="bankcard" value="" placeholder="" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">金额</label>
                <div class="layui-input-block">
                    <input type="text" name="money" value="" placeholder="" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">支付密码</label>
                <div class="layui-input-block">
                    <input type="password" name="paypass" value="" placeholder="" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
            

            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="demo2">立即提交</button>
            </div>
        </form>
    </div>
    <!-- 网站设置结束 -->
</div>

</body>

<script src="/Public/layui/layui.js"></script>

<script>
    layui.use(['form', 'layedit', 'upload' , 'jquery'], function(){
        var form = layui.form
            ,$ = layui.jquery
            ,layer = layui.layer
            ,upload = layui.upload;


        /**
         * [数据提交]
         */
        form.on('submit(demo2)', function(data){
            // var loadVal = layer.load(3);
            var data = data.field
            var url = "<?php echo U('Pay');?>";
            $.post(url, data, function(res) {
                // layer.close(loadVal);
                if(res.status == 1){
                    layer.msg(res.info, {icon: 6, shade: 0.1}, function(){
                        window.location.reload();
                    });
                }else{
                    layer.msg(res.info, {icon: 5, shade: 0.1});
                }
            }, 'json');
            
            return false;
        });
        

        /**
         * 修改
         * @param    {[type]} a [description]
         * @return  {[type]}   [description]
         */
        window.update = function(a){
            layer.open({
                type:2,
                shadeClose:true,
                title:'修改',
                area: ['800px', '500px'],
                content:a,
            });
        };


        /**
         * 删除
         */
        window.del = function(url , data){
            layer.alert('确定要删除吗？',{
                title:'删除操作',
                shadeClose:true,
                btn:['确定','取消']
            },function(){
                layer.load(1,{shade:0.2});
                var c = $('input[name=_token]').val();
                $.post(url , {admin_id:data,_token:c} , function(data){
                    layer.closeAll();
                    if (data.status == 1) {
                        /*成功*/
                        layer.msg(data.message, {icon: 6,time:1500, shade: 0.1},function(){
                            location.reload();
                        });
                    }else{
                        layer.msg(data.message, {icon: 5,time:1000, shade: 0.6},function(){});
                    }
                },'json');
            });
        };
    });


    $(function(){
        /**
         * [数据提交]
         */
        form.on('submit(demo2)', function(data){
            if(data.field.zfpz1 == "" && data.field.zfpz2 == "" && data.field.zfpz3 == ""){
                layer.msg('请上传您的付款截图', {icon: 5, shade: 0.1});
                return false;
            }

            var data_info = data.field;
            var tj_url = "{{ url('pay/recharge') }}";
            var load_ing = layer.load(3);
            $.post(tj_url, data_info, function (res) {
                layer.close(load_ing);
                if(res.status == 1){
                    $('.wz-pay-box').hide();
                    $('.recharge-completed-box').show();
                }else{
                    layer.msg('网络异常，请稍后重试', {icon: 5, shade: 0.1});
                }
            }, 'json');
            return false;
        });
        
    });

</script>


</html>