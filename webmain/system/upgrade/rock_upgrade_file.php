<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var id = params.id;
	var a = $('#view_{rand}').bootstable({
		tablename:'chargems',url:js.getajaxurl('datadubi','{mode}','{dir}',{id:id}),
		checked:true,
		columns:[{
			text:'类型',dataIndex:'type',renderer:function(v){
				var s='文件';
				if(v==1)s='数据库';
				return s;
			}
		},{
			text:'文件路径',dataIndex:'filepath',align:'left'
		},{
			text:'文件大小',dataIndex:'filesize'
		},{
			text:'文件说明',dataIndex:'explain'
		},{
			text:'',dataIndex:'ishui',renderer:function(v, d){
				var s='<font color="green">可更新</font>';
				s+='&nbsp;<button type="button" onclick="upgradefile.upfile(this,'+d.id+')" class="btn btn-default btn-xs">更新</button>';
				if(v==1)s='已忽略';
				if(d.ting=='1')s='不同步更新模块';
				return s;
			}
		},{
			text:'状态',dataIndex:'zt'
		}]
	});
	
	var c={
		reloads:function(){
			a.reload();
		},
		huliesss:function(o1,lx){
			var sid = a.getchecked();
			if(sid==''){js.msg('msg','没有选中行');return;}
			
			js.ajax(js.getajaxurl('hullue','{mode}','{dir}'),{sid:sid,id:id,lx:lx},function(s){
				a.reload();
			},'post','','处理中...,处理完成');
		},
		upfile:function(o1, fid){
			o1.disabled = true;
			$(o1).html(js.getmsg('更新中...'));
			var ad = {};
			ad.id = id;
			ad.fileid = fid;
			ad.oii = 1;
			ad.lens = 0;
			ad.ban = '';
			js.ajax(js.getajaxurl('shengjianss','{mode}','{dir}'),ad,function(s){
				if(s=='ok'){
					$(o1).html('更新成功');
				}else{
					$(o1).html(s);
				}
			},'post',function(s){
				$(o1).html('失败');
			});
		}
	};

	js.initbtn(c);
	upgradefile = c;
	
	
});
</script>
<div>
	<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-default" click="reloads"  type="button"><i class="icon-refresh"></i> 刷新</button>
	</td>
	<td align="right">
		<button class="btn btn-default" click="huliesss,0"  type="button">忽略选中文件更新</button>&nbsp;
		<button class="btn btn-default" click="huliesss,1"  type="button">取消忽略选中文件更新</button>
	</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">没有记录表示没有可更新文件。</div>
