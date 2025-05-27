QOM='xinhuwx_'
js.wx={};
js.wx.alert=function(msg,fun,tit, cof1){
	js.alertclose();
	js.alert(msg,tit, fun);
}
js.wx.confirm=function(msg,fun,tit){
	js.confirm(msg,fun,tit);
}
js.wx.prompt=function(tit,msg,fun,nr){
	js.prompt(tit,msg,function(jg,txt){if(jg=='yes')fun(txt)},nr);
}
js.apiurl = function(m,a,cans){
	var url=''+apiurl+'api.php?m='+m+'&a='+a+'';
	var cfrom='mweb';
	if(adminid)url+='&adminid='+adminid+'';
	if(device)url+='&device='+device+'';
	url+='&cfrom='+cfrom+'';
	if(token)url+='&token='+token+'';
	if(!cans)cans={};
	for(var i in cans)url+='&'+i+'='+cans[i]+'';
	return url;
}
js.ajax  = function(m,a,d,funs, mod,checs, erfs, glx){
	if(js.ajaxbool && !js.ajaxwurbo)return;
	clearTimeout(js.ajax_time);
	var url = js.apiurl(m,a);
	js.ajaxbool = true;
	if(!mod)mod='mode';
	if(typeof(erfs)!='function')erfs=function(){};
	if(typeof(funs)!='function')funs=function(){};
	if(!checs)checs=function(){};
	var bs = checs(d);
	if(typeof(bs)=='string'&&bs!=''){
		js.msg('msg', bs);
		return;
	}
	if(typeof(bs)=='object')d=js.apply(d,bs);
	var tsnr = '努力处理中...';
	if(mod=='wait')js.msg(mod, tsnr);
	if(mod=='mode')js.wx.load(tsnr);
	function errsoers(ts, ds){
		js.wx.unload();
		js.setmsg(ts);
		js.msg('msg',ts);
		js.ajaxbool = false;
		erfs(ts, ds);
	}
	var type=(!d)?'get':'post';if(glx)type=glx;
	var ajaxcan={
		type:type,dataType:'json',data:d,url:url,
		success:function(ret){
			js.ajaxbool=false;
			js.wx.unload();
			clearTimeout(js.ajax_time);
			if(ret.code==199){
				js.wx.alert(ret.msg, function(){
					js.location('?d=we&m=login&backurl='+jm.base64encode(location.href)+'');
				});
				return;
			}
			if(ret.code!=200){
				errsoers(ret.msg, ret);
			}else{
				js.setmsg('');
				js.msg('none');
				funs(ret.data);
			}
		},
		error:function(e){
			errsoers('内部出错:'+e.responseText+'');
		}
	};
	$.ajax(ajaxcan);
	js.ajax_time = setTimeout(function(){
		if(js.ajaxbool){
			errsoers('Error:请求超时?');
		}
	}, 1000*30);
}
js.wx.load=function(txt){
	js.loading(txt);
}
js.wx.unload=function(){
	js.unloading();
}
js.wx.msgok=function(txt,fun,ms){
	if(js.msgok){
		js.msgok(txt,fun, ms);
	}else{
		js.alert(txt,'', fun);
	}
}

js.showmenu=function(d){
	$('#menulistshow').remove();
	var d=js.apply({width:200,top:'50%',renderer:function(){},align:'center',onclick:function(){},oncancel:function(){}},d);
	var a=d.data;
	if(!a)return;
	var h1=$(window).height(),h2=document.body.scrollHeight,s1;
	if(h2>h1)h1=h2;
	var col='',oix;
	var s='<div align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:198;" oncontextmenu="return false">';
	s+='<div id="menulistshow_s" style="width:'+d.width+'px;margin-top:'+d.top+';position:fixed;-webkit-overflow-scrolling:touch;" class="menulist">';
	for(var i=0;i<a.length;i++){
		oix = '0';
		if(i>0)oix='0.5';
		s+='<div oi="'+i+'" style="text-align:'+d.align+';color:'+a[i].color+';border-top:'+oix+'px solid #dddddd">';
		s1=d.renderer(a[i]);
		if(s1){s+=s1}else{s+=''+a[i].name+'';}
		s+='</div>';
	}
	s+='</div>';
	s+='</div>';
	$('body').append(s);
	var mh = $(window).height();
	var l=($(window).width()-d.width)*0.5,o1 = $('#menulistshow_s'),t = (mh-o1.height()-10)*0.5;
	if(t<10){
		t = 10;
		o1.css({height:''+(mh-20)+'px','overflow':'auto'});
	}
	o1.css({'left':''+l+'px','margin-top':''+t+'px'});
	$('#menulistshow div[oi]').click(function(){
		var oi=parseFloat($(this).attr('oi'));
		d.onclick(a[oi],oi);
	});
	$('#menulistshow').click(function(){
		$(this).remove();
		try{d.oncancel();}catch(e){}
	});
};

js.wx.actionsheet=function(d){
	$('#actionsheetshow').remove();
	var d=js.apply({onclick:function(){},oncancel:function(){}},d);
	var a=d.data,s='';
	if(!a)return;
	s+='<div onclick="$(this).remove();"  id="actionsheetshow">';
	s+='<div class="weui_mask_transition weui_fade_toggle" style="display:block"></div>';
	s+='<div class="weui_actionsheet weui_actionsheet_toggle" >';
	s+='	<div class="weui_actionsheet_menu">';
	for(var i=0;i<a.length;i++){
		s+='<div oi="'+i+'" style="color:'+a[i].color+'" class="weui_actionsheet_cell">'+a[i].name+'</div>';
	}
	s+='	</div>';
	s+='	<div class="weui_actionsheet_action"><div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div></div>';
	s+='</div>';
	s+='</div>';
	$('body').append(s);
	$('#actionsheetshow div[oi]').click(function(){
		var oi=parseFloat($(this).attr('oi'));
		d.onclick(a[oi],oi);
	});
	$('#actionsheetshow').click(function(){
		$(this).remove();
		try{d.oncancel();}catch(e){}
	});
}

js.isqywx=false;
js.iswxbo=function(){
	var bo = true;
	var ua = navigator.userAgent.toLowerCase(); 
	if(ua.indexOf('micromessenger')<0)bo=false;
	if(bo && ua.indexOf('wxwork')>0)js.isqywx=true;
	return bo;
}
js.jssdkcall  = function(bo){
	
}
js.jssdkstate = 0;
js.jssdkwixin = function(qxlist,afe){
	if(!js.iswxbo())return js.jssdkcall(false);
	//if(js.isqywx)var wxurl = 'https://res.wx.qq.com/open/js/jweixin-1.1.0.js';
	var wxurl = 'https://res.wx.qq.com/open/js/jweixin-1.2.0.js';
	if(!afe)$.getScript(wxurl, function(){
		js.jssdkwixin(qxlist, true);
	});
	if(!afe)return;
	var surl= location.href;
	if(!qxlist)qxlist= ['openLocation','getLocation','chooseImage','getLocalImgData','previewImage'];
	js.ajax('weixin','getsign',{url:jm.base64encode(surl),agentid:js.request('agentid')},function(ret){
		if(!ret.appId)return js.jssdkcall(false);
		wx.config({
			debug: false,
			appId: ret.appId,
			timestamp:ret.timestamp,
			nonceStr: ret.nonceStr,
			signature: ret.signature,
			jsApiList:qxlist
		});
		wx.ready(function(){
			if(js.jssdkstate==0)js.jssdkstate = 1;
			js.jssdkcall(true);
		});
		wx.error(function(res){
			js.jssdkstate = 2;
		});
	});
}

/**
*	微信公众号jssdk授权
*/
js.jssdkwxgzh = function(qxlist,afe){
	if(!js.iswxbo())return js.jssdkcall(false);
	var wxurl = 'https://res.wx.qq.com/open/js/jweixin-1.2.0.js';
	if(!afe)$.getScript(wxurl, function(){
		js.jssdkwxgzh(qxlist, true);
	});
	if(!afe)return;
	var surl= location.href;
	if(!qxlist)qxlist= ['openLocation','getLocation','chooseImage','getLocalImgData','previewImage'];
	js.ajax('wxgzh','getsign',{url:jm.base64encode(surl)},function(ret){
		if(!ret.appId)return js.jssdkcall(false);
		wx.config({
			debug: false,
			appId: ret.appId,
			timestamp:ret.timestamp,
			nonceStr: ret.nonceStr,
			signature: ret.signature,
			jsApiList:qxlist
		});
		wx.ready(function(){
			if(js.jssdkstate==0)js.jssdkstate = 1;
			js.jssdkcall(true);
		});
		wx.error(function(res){
			js.jssdkstate = 2;
		});
	});
}

//长按处理
function touchclass(cans){
	var me = this;
	this.onlongclick = function(){}
	this.onclick	 = function(){}
	this.onlongmenu	 = function(){}
	this.initbool 	 = false;
	this.islongbool	 = false;
	
	for(var i in cans)this[i]=cans[i];
	this.touchstart=function(o1,evt){
		touchnowobj 	= this;
		this.islongbool = false;
		if(!this.initbool){
			o1.addEventListener('click', function(){
				me.onclicks(this, event);
			}, false);
		}
		this.obj = o1;
		this.initbool	= true;
		clearTimeout(this.touchtime);
		this.touchtime  = setTimeout('touchnowobj=false',1000);
		return true;
	}
	this.ismobile=function(){
		var llq = navigator.userAgent;
		llq 	= llq.toLowerCase();
		var sarr= ['android','mobile','iphone'],bo=false,i;
		for(i=0;i<sarr.length;i++){
			if(llq.indexOf(sarr[i])>-1){
				bo=true;
				break;
			}
		}
		return bo;
	}
	this.onclicks=function(o1, evt){
		var lx = evt.target.nodeName.toLowerCase();
		if(!this.islongbool && lx!='a')this.onclick(o1, evt);
	}
	this.touchstring=function(){
		var rnd = 'a'+js.getrand();
		touchnowoba[rnd] = this;
		var str = ' ontouchstart="return touchnowoba.'+rnd+'.touchstart(this,event)"';
		if(!this.ismobile()){
			str = ' onmouseover="touchnowoba.'+rnd+'.touchstart(this,event)"';
			str+= ' oncontextmenu="touchnowoba.'+rnd+'.onlongclick();return false;"';
		}
		return str;
	}
	this.reglongmenu=function(){
		touchnowobj		= false;
		touchnowoba		= {};
		document.addEventListener('touchstart', function(){
			clearTimeout(me.longtapv);
			me.longtapv = setTimeout(function(){me.longmenu();},300);
		}, false);
		document.addEventListener('touchmove', function(){
			clearTimeout(me.longtapv);
		}, false);
		document.addEventListener('touchend', function(){
			clearTimeout(me.longtapv);
		}, false);
	}
	this.longmenu	 = function(){
		setTimeout('touchnowobj=false',200);
		if(!touchnowobj)return;
		touchnowobj.islongbool = true;
		touchnowobj.onlongclick();
		this.onlongmenu();
	}
}

js.ling = function(w){
	var sve = 'style="height:'+w+'px;width:'+w+'px"';
	if(!w)sve='';
	return '<i '+sve+' class="rock-loading"></i>';
}