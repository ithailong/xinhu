<?php
/**
*	模块：goods.物品
*	说明：自定义区域内可写你想要的代码
*	来源：流程模块→表单元素管理→[模块.物品]→生成列表页
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	{params}
	var modenum = 'goods',modename='物品',isflow=0,modeid='9',atype = params.atype,pnum=params.pnum,modenames='',listname='Z29vZHM:';
	if(!atype)atype='';if(!pnum)pnum='';
	var fieldsarr = [],fieldsselarr= [],chufarr= {"stock":"\u603b\u5e93\u5b58","stock_1":"\u9ed8\u8ba4\u4ed3\u5e93","stock_3":"\u6cc9\u5dde\u4ed3\u5e93","stock_2":"\u4ed3\u5e932"};
	
	<?php
	include_once('webmain/flow/page/rock_page.php');
	?>
	
//[自定义区域start]

if(pnum=='all'){
	bootparams.checked=true;
	bootparams.autoLoad=false;
	bootparams.celleditor=true;

	var shtm = '<table width="100%"><tr valign="top"><td><div style="border:var(--border);width:220px"><div id="optionview_{rand}" style="height:400px;overflow:auto;"></div></div></td><td width="8" nowrap><div style="width:8px;overflow:hidden"></div></td><td width="95%"><div id="viewgoods_{rand}"></div></td></tr></table>';
	$('#viewgoods_{rand}').after(shtm).remove();
	c.stable = 'goods';
	c.optionview = 'optionview_{rand}';
	c.optionnum = 'goodstype';
	c.title = '物品分类';
	c.rand = '{rand}';

	var c = new optionclass(c);

	$('#'+c.optionview+'').css('height',''+(viewheight-130)+'px');
	$('#tdright_{rand}').prepend(c.getbtnstr('刷新库存','kuncus')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('所有物品','allshow')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('入库','rukuchu,0')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('出库','rukuchu,1')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend(c.getbtnstr('打印二维码','prinwem,1')+'&nbsp;&nbsp;');
	$('#tdright_{rand}').prepend('<span id="megss{rand}"></span>&nbsp;&nbsp;');
	setTimeout(function(){c.mobj=a},5);//延迟设置，不然不能双击分类搜索
	
	c.rukuchu=function(o1, lx){
		var s='物品入库';
		if(lx==1)s='物品出库';
		addtabs({num:'rukuchugood'+lx+'',url:'main,goods,churuku,type='+lx+'',icons:'plus',name:s});
	}
	
	c.prinwem=function(){
		var sid = a.getchecked();
		if(sid==''){
			js.msg('msg','没有选中记录');
			return;
		}
		var url = '?a=printewm&m=goods&d=main&sid='+sid+'';
		window.open(url);
	}
}

c.kuncus=function(){
	js.ajax(publicmodeurl('goods','reloadstock'),{},function(){
		a.reload();
	},'get','','刷新中...,刷新完成');
}

//[自定义区域end]
	c.initpagebefore();
	js.initbtn(c);
	var a = $('#view'+modenum+'_{rand}').bootstable(bootparams);
	c.init();
	
});
</script>
<!--SCRIPTend-->
<!--HTMLstart-->
<div>
	<table width="100%">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" disabled type="button"><i class="icon-plus"></i> <?=lang('新增')?></button></td>
		
		<td><select class="form-control" style="width:110px;border-top-right-radius:0;border-bottom-right-radius:0;padding:0 2px" id="fields_{rand}"></select></td>
		<td><select class="form-control" style="width:60px;border-radius:0px;border-left:0;padding:0 2px" id="like_{rand}"><option value="0"><?=lang('包含')?></option><option value="1"><?=lang('等于')?></option><option value="2"><?=lang('大于')?><?=lang('等于')?></option><option value="3"><?=lang('小于')?><?=lang('等于')?></option><option value="4"><?=lang('不包含')?></option></select></td>
		<td><select class="form-control" style="width:130px;border-radius:0;border-left:0;display:none;padding:0 5px" id="selkey_{rand}"><option value="">-<?=lang('请选择')?>-</option></select><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px" id="keygj_{rand}" placeholder="<?=lang('关键字')?>"><input class="form-control" style="width:130px;border-radius:0;border-left:0;padding:0 5px;display:none;" id="key_{rand}" placeholder="<?=lang('关键字')?>">
		</td>
		
		<td>
			<div style="white-space:nowrap">
			<button style="border-right:0;border-radius:0;border-left:0" class="btn btn-default" click="searchbtn" type="button"><?=lang('搜索')?></button><button class="btn btn-default" id="downbtn_{rand}" type="button" style="padding-left:8px;padding-right:8px;border-top-left-radius:0;border-bottom-left-radius:0"><i class="icon-angle-down"></i></button> 
			</div>
		</td>
		<td  width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<span style="display:none" id="daoruspan_{rand}"><button class="btn btn-default" click="daoru,1" type="button"><?=lang('导入')?></button>&nbsp;&nbsp;&nbsp;</span><button class="btn btn-default" style="display:none" id="daobtn_{rand}" disabled click="daochu" type="button"><?=lang('导出')?> <i class="icon-angle-down"></i></button> 
		</td>
	</tr>
	</table>
</div>
<div class="blank10"></div>
<div id="viewgoods_{rand}"></div>
<!--HTMLend-->