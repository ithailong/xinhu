<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var a = $('#view_{rand}').bootstable({
		tablename:'table',fanye:true,modedir:'{mode}:{dir}',storebeforeaction:'tablebefore',celleditor:true,
		cellurl:js.getajaxurl('tablesm','{mode}','{dir}'),storeafteraction:'tableafter',
		columns:[{
			text:'表名',dataIndex:'id',sortable:true
		},{
			text:'引擎',dataIndex:'engine'
		},{
			text:'总记录数',dataIndex:'rows',sortable:true
		},{
			text:'说明',dataIndex:'explain',editor:true
		},{
			text:'创建时间',dataIndex:'cjsj',sortable:true
		},{
			text:'字符集',dataIndex:'TABLE_COLLATION'
		},{
			text:'更新时间',dataIndex:'gxsj',sortable:true
		},{
			text:'操作',dataIndex:'gengxin',renderer:function(v,d){
				return '<input type="button" onclick="up{rand}.dbupdatess(\''+d.id+'\',-1)" id="table{rand}_'+d.id+'" class="btn btn-default btn-xs" value="更新" />';
			}
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		},
		loadbefore:function(d){
			$('#dbupurl_{rand}').val(d.dbupurl);
		}
	});
	
	function btn(bo){
		get('edit_{rand}').disabled = bo;
		get('kanbtn_{rand}').disabled = bo;
	}
	var  c={
		clickwin:function(){
			var name=a.changeid;
			addtabs({num:'tablefields'+name+'',url:'system,table,fields,table='+name+'',name:'['+name+']字段管理'});
		},
		kanjili:function(){
			var name=a.changeid;
			addtabs({num:'tablerecord'+name+'',url:'system,table,record,table='+name+'',name:'['+name+']记录'});
		},
		search:function(){
			a.setparams({
				key:get('key_{rand}').value
			},true);
		},
		dbupdate:function(){
			//js.msg('success', '暂无功能');return;
			this.data = a.getData();
			this.dbupdates(0);
		},
		dbupdates:function(i){
			var d = this.data[i]
			if(!d){
				js.msg('success', '更新完成');
				return;
			}
			js.msg('wait', '更新中('+this.data.length+'/'+(i+1)+')'+d.id+'...');
			this.dbupdatess(d.id, i);
		},
		dbupdatess:function(tab, i){
			var o = get('table{rand}_'+tab+'');
			if(o){
				o.disabled = true
				o.value='更新中..';
			}
			js.ajax(js.getajaxurl('dbupdate','{mode}','{dir}'), {tab:tab}, function(ret){
				if(ret.success){
					o.value=ret.data;
					if(i>-1)c.dbupdates(i+1)
				}else{
					o.value='失败';
					js.msg('msg', ret.msg);
				}	
			},'get,json');
		},
		savedbupurl:function(o){
			var dz = o.value;
			js.ajax(js.getajaxurl('savedbupurl','{mode}','{dir}'), {dz:jm.base64encode(dz)});
		}
	};
	js.initbtn(c);
	$('#dbupurl_{rand}').blur(function(){
		c.savedbupurl(this)
	})
	
	up{rand} = c
});
</script>


<div>
	<table width="100%">
	<tr>
	<td >
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="表名">
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	
	<td  width="80%" style="padding-right:10px">
		
		
	</td>
	
	
	<td align="right" nowrap>
		<input class="form-control" style="width:180px" id="dbupurl_{rand}"  placeholder="默认更新地址">&nbsp;
		<button class="btn btn-info" click="dbupdate" type="button">一键更新</button>&nbsp;
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 表结构 </button>&nbsp;
		<button class="btn btn-default" id="kanbtn_{rand}" click="kanjili" disabled type="button">查看记录</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">数据库表格管理请谨慎操作，一键更新，只是更新表结果。</div>
