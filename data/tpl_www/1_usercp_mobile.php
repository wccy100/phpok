<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","修改手机"); ?><?php $this->output("head","file"); ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#userinfo_mobile").submit(function(){
		$(this).ajaxSubmit({
			'type':'post',
			'dataType':'json',
			'url':api_url('usercp','mobile'),
			'success':function(rs){
				if(rs.status == 'ok'){
					$.dialog.alert("您的手机更新成功",function(){
						$.phpok.reload();
					},'succeed');
				}else{
					$.dialog.alert(rs.content);
					return false;
				}
			}
		});
		return false;
	});
});
</script>
<?php if($sendsms){ ?>
<script type="text/javascript">
var maxtime = 60;
var sms_send_lock = false;
var win_time_out;
function update_send_sms_loading()
{
	maxtime--;
	if(maxtime < 1){
		$("#sms_send_status").val('发送验证码');
		sms_send_lock = false;
		maxtime = 60;
		window.clearInterval(win_time_out);
		return true;
	}
	var tips = "验证码已发送("+maxtime+")";
	$("#sms_send_status").val(tips);
}

function send_sms()
{
	if(sms_send_lock){
		$.dialog.alert('验证码已发送，请一分钟后再执行');
		return false;
	}
	var url = api_url('usercp','smscode');
	var mobile = $("#mobile").val();
	if(!mobile){
		$.dialog.alert('手机号不能为空');
		return false;
	}
	url += "&mobile="+mobile;
	$.dialog.tips("正在执行中，请稍候…");
	update_send_sms_loading();
	$.ajax({
		'url':url,
		'dataType':'json',
		'cache':false,
		'async':true,
		'beforeSend': function (XMLHttpRequest){
			XMLHttpRequest.setRequestHeader("request_type","ajax");
		},
		'success':function(rs){
			if(rs.status == 'ok'){
				maxtime = 60;
				sms_send_lock = true;
				win_time_out = setInterval("update_send_sms_loading()",1000);
			}else{
				$.dialog.alert(rs.content);
				$("#sms_send_status").val('发送验证码');
			}
		}
	});
}
</script>
<?php } ?>

<div class="cp">
	<div class="left"><?php $this->output("block_usercp","file"); ?></div>
	<div class="right">
		<div class="pfw mbottom10">
			<h3>手机修改</h3>
			<form method="post" id="userinfo_mobile">
			<div class="table">
				<div class="l"><span class="red">*</span> 会员密码：</div>
				<input type="password" name="pass" id="pass" value="" class="input" />
			</div>
			<?php if($rs['mobile']){ ?>
			<div class="table">
				<div class="l">原手机：</div>
				<input type="text" name="oldmobile" id="oldmobile" value="<?php echo $rs['mobile'];?>" class="input" disabled />
			</div>
			<?php } ?>
			<div class="table">
				<div class="l"><span class="red">*</span> 新手机：</div>
				<input type="text" name="mobile" id="mobile" value="" class="input" />
				<?php if($sendsms){ ?>
				<input type="button" value="发送验证码" onclick="send_sms()" class="button blue" id="sms_send_status" />
				<?php } ?>
			</div>
			<?php if($sendsms){ ?>
			<div class="table">
				<div class="l"><span class="red">*</span> 验证码：</div>
				<input type="text" name="chkcode" class="input" />
			</div>
			<?php } ?>
			<div class="table">
				<div class="l">&nbsp;</div>
				<input type="submit" id="phpok_submit" value="提 交" class="button blue">
			</div>
			</form>
		</div>
		<div class="pfw mbottom10">
			<h3>友情说明</h3>
			<ul class="tip">
				<li>手机号修改需要您提供会员密码认证</li>
				<li>请确填写的手机号是11位有效数字，暂不接受其他类型的手机号</li>
				<li>我们推荐您使用插件来实现手机号变更验证</li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php $this->output("foot","file"); ?>