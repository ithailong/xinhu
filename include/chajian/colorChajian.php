<?php 
/**
	颜色操作
*/
class colorChajian extends Chajian{

	/**
		颜色
	*/
	public function color($color,$l=127.5)
	{
		$r=hexdec(substr($color,1,2));
		$g=hexdec(substr($color,3,2));
		$b=hexdec(substr($color,5));
		$yb=127.5;
		if($l > $yb){
			$l = $l - $yb;
			$r = ($r * ($yb - $l) + 255 * $l) / $yb;
			$g = ($g * ($yb - $l) + 255 * $l) / $yb;
			$b = ($b * ($yb - $l) + 255 * $l) / $yb;
		}else{
			$r = ($r * $l) / $yb;
			$g = ($g * $l) / $yb;
			$b = ($b * $l) / $yb;
		}
		$nr=$this->tohex($r);
		$ng=$this->tohex($g);
		$nb=$this->tohex($b);
		return '#'.$nr.$ng.$nb;
	}
	
	private function tohex($n)
	{
		$hexch = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
		$n 	= round($n);
		$l 	= $n % 16;
		$h 	= floor(($n / 16)) % 16;
		return ''.$hexch[$h].''.$hexch[$l].'';
	}
	
	/**
	*	获取颜色值
	*/
	public function colorTorgb($color)
	{
		if(!empty($color)&&(strlen($color)==7)){
			$r=hexdec(substr($color,1,2));
			$g=hexdec(substr($color,3,2));
			$b=hexdec(substr($color,5));
		}else{
			$r=$g=$b='00';
		}
		return array($r, $g, $b);
	}
	
	/**
	*	获取样式的
	*/
	public function getApptheme($nohui=true, $ism=false)
	{
		$arr 		= $this->getColor();
		$color 		= $arr['color'];
		$colors 	= $arr['colors'];
		$colora 	= $arr['colora'];
		
		$bodybgcolor	= 'rgba('.$colors.',0.03)';
		$hgcolor  		= 'rgba('.$colors.',0.15)';
		$vgcolor  		= 'rgba('.$colors.',0.1)';
		$str[] = '<style type="text/css">';
		
		
		//暗黑模式的var(--rgb-r),var(--rgb-g),var(--rgb-b)
		$ishei	= false;
		if(($color == '#333333' || $color=='#555555') && $nohui)$ishei = true;
		
		$str[] = 'body{';
		$str[] = 'background:'.$bodybgcolor.';';
		
		$str[] = '--rgb-r:'.$colora[0].';';
		$str[] = '--rgb-g:'.$colora[1].';';
		$str[] = '--rgb-b:'.$colora[2].';';
		$str[] = '--main-color:'.$color.';';
		if($ism)$str[] = '--font-size:16px;';
		$str[] = '--main-hgcolor:'.$hgcolor.';';
		if(!$ishei){
			$str[] = '--main-vgcolor:'.$vgcolor.';';
			$str[] = '--border:0.5px rgba('.$colors.',0.1) solid;';
			$str[] = '--main-border:rgba('.$colors.',0.1);';
		}
		$str[] = '}';
		
		if($ishei){
			$str[] = '.rock-table .rock-table-tr:nth-child(odd){background:rgba(0,0,0,0.03);}';
			$str[] = '.rock-table .rock-table-tr:hover{background:rgba(0,0,0,0.1);}';
		}
		
		if($ishei){
			$bgcol  	= 'rgba('.$colors.',0.9)';
			$bgcolor  	= 'rgba('.$colors.',0.5)';
			$str[] = 'body{background:'.$bgcol.';color:white;--main-bgcolor:'.$bgcolor.'}';
			$str[] = 'a:link,a:visited,input{color:white;}';
			$str[] = '.input,.textarea,.form-control,.inputs{background:'.$bgcol.';color:white}';
			$str[] = '.input:focus,.inputs:focus,.textarea:focus,.form-control:focus{border:.5px rgba(255,255,255,0.1) solid}';
			$str[] = '.zhu{color:white}';
			$str[] = '.webbtn:disabled,.btn:disabled{background:var(--main-bgcolor);color:#888888}';
			$str[] = '.webbtn-default,.webbtn-default:link,.webbtn-default:visited,.btn{background:var(--main-color);color:white;border:none;}';
		}else{
			$str[] = '.rockmenuli{background:rgba('.$colora[0].','.$colora[1].','.$colora[2].',0.05)}';
		}
		$str[] = '</style>';
		$str[] = '<script type="text/javascript">';
		$str[] = 'maincolor = "'.$color.'";';
		if(!$ishei)$str[] = 'bootsSelectColor = "var(--main-hgcolor)";';
		$str[] = '</script>';
		$path  = 'webmain/css/rockmy.css';
		if(file_exists($path))$str[] = '<link rel="stylesheet" type="text/css" href="'.$path.'?'.time().'" />';
		$str[] = '';
		return join(PHP_EOL, $str);
	}
	
	/**
	*	获取样式
	*/
	public function getColor()
	{
		$color 			= $this->getTheme();
		$maincolora		= $this->colorTorgb($color);
		$colors			= ''.$maincolora[0].','.$maincolora[1].','.$maincolora[2].'';
		return array(
			'color'	 => $color,
			'colors' => $colors,
			'colora' => $maincolora,
		);
	}
	
	/**
	*	获取对应颜色
	*/
	public function getTheme()
	{
		$color 			= getconfig('apptheme','#1389D3');
		//$apptheme		= $this->rock->get('apptheme');
		//if(strlen($apptheme)==6)$this->rock->savecookie('apptheme', $apptheme);
		//if(!$apptheme)$apptheme = $this->rock->cookie('apptheme');
		//if(strlen($apptheme)==6)$color = '#'.$apptheme.'';
		return $color;
	}
}