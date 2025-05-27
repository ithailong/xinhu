/**
*	在线编辑获取内容的
*/

js.plugin_rockoffice = function(conf){
	if(conf){
		this.plugin_rockoffice_conf = conf;
		this.plugin_rockofficeopen();
	}
}


js.plugin_rockofficeopen = function(){
	clearInterval(js.plugin_rockofficetime);
	if(js.plugin_rockofficebool)return;
	var conf = this.plugin_rockoffice_conf;
	if(!conf)return;
	var ws 	= new WebSocket(jm.base64decode(conf.wsurl));
	ws.onopen = function(){
		this.send('{"from":"'+conf.recid+'","adminid":"'+conf.adminid+'","atype":"connect","sendname":"'+conf.adminname+'"}');
		js.plugin_rockofficebool = true;
	}
	ws.onclose = function(e){
		js.plugin_rockofficebool = false;
		js.plugin_rockofficetime = setTimeout('js.plugin_rockofficeopen()',3000);
	};
	ws.onerror = function(e){
		js.plugin_rockofficebool = false;
		//setTimeout('js.plugin_rockofficeopen()',3000);
	};
	ws.onmessage = function(evt){
		js.plugin_rockofficebool = true;
		var ds = JSON.parse(evt.data);
		js.plugin_rockofficemessage(ds);
	};
	js.plugin_rockofficews = ws;
}

js.plugin_rockofficemessage = function(d){
	var xxtype = d.xxtype;
	if(d.waitmsg)js.msg('wait',jm.base64decode(d.waitmsg));
	if(d.msg)js.msg('success',jm.base64decode(d.msg));
	if(d.xxtype=='glast'){
		$.get('api.php?m=upload&a=editfileb&fileid='+d.fileid+'', function(s){
			js.plugin_rockoffice_conf = '';
			if(s)js.msg('success',s);
		});
	}
}