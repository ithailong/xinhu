<?php
/**
*	运行方式：E:\php\php-5.4.14\php.exe E:\IIS\app\xinhu\task.php beifen
*	url:http://demo.rockoa.com/task.php?m=beifen|runt
*	url:http://127.0.0.1/app/xinhu/task.php?m=beifen|runt
*/
class beifenClassAction extends runtAction
{
	//每天cli备份数据为sql文件的
	public function defaultAction()
	{
		if(PHP_SAPI != 'cli') return 'plase cli run';
		$alltabls 	= $this->db->getalltable();
		$nobeifne	= array(''.PREFIX.'log',''.PREFIX.'logintoken',''.PREFIX.'kqanay',''.PREFIX.'email_cont',''.PREFIX.'reads',''.PREFIX.'dailyfx',''.PREFIX.'todo',''.PREFIX.'city'); //不备份的表;
		$data 		= array();
		$strstr 	= "/*
	备份时间：".$this->now."		
*/

";
		foreach($alltabls as $tabs){
			if(in_array($tabs, $nobeifne))continue;	
			$strstr	.= "DROP TABLE IF EXISTS `$tabs`;\n";
			$sqla 	 = $this->db->getall('show create table `'.$tabs.'`');
			$strstr	.= "".$sqla[0]['Create Table'].";\n";
			
			$rows  	= $this->db->getall('select * from `'.$tabs.'`');
			foreach($rows as $k=>$rs){
				$vstr = '';
				foreach($rs as $k1=>$v1){
					if(!isempt($v1))$v1 = str_replace("\n",'\n', $v1);
					$v1 = ($v1==null) ? 'null' : "'$v1'";
					$vstr.=",$v1";
				}
				$strstr	.= "INSERT INTO `$tabs` VALUES(".substr($vstr,1).");\n";
			}
			
			$strstr	.= "\n";
		}

		$rnd  = str_shuffle('abcedfghijk').rand(1000,9999);
		$file = ''.DB_BASE.'_'.date('Y.m.d.H.i.s').'_'.$rnd.'.sql';
		$filepath = ''.UPDIR.'/data/'.$file.'';
		$this->rock->createtxt($filepath, $strstr);
		
		//给管理员邮箱发邮件
		m('email')->sendmail(''.TITLE.'数据库备份',''.TITLE.'数据库备份'.$this->rock->now.'', 1 , array(), 1, array(
			'attachname'=> $file,
			'attachpath'=> $filepath,
		));
		
		@unlink($filepath);
		
		return 'success';
	}
	
	/**
	*	备份数据库
	* 	php task.php beifen,create -table=menu
	*/
	public function createAction()
	{
		if(PHP_SAPI != 'cli') return 'plase cli run:php task.php beifen,create';
		$table = $this->getparams('table');
		ob_end_clean();
		$path 		= ''.ROOT_PATH.'/'.DB_BASE.''.$table.'_'.date('YmdHis').'.sql';
		$file 		= fopen($path, 'ab+');
		
		$nobeifne	= array(''.PREFIX.'log',''.PREFIX.'logintoken',''.PREFIX.'kqanay',''.PREFIX.'email_cont',''.PREFIX.'dailyfx',''.PREFIX.'reads',''.PREFIX.'todo',''.PREFIX.'city'); //不备份的表;
		
		if($table){
			$alltabls[] = ''.PREFIX.''.$table.'';
		}else{
			$alltabls 	= $this->db->getalltable();
		}
		
		foreach($alltabls as $tabs){
			$sqla 	 	= $this->db->getall('show create table `'.$tabs.'`');
			$createsql	= $sqla[0]['Create Table'];
			$createsql	= str_replace('`'.$tabs.'`', 'IF NOT EXISTS `'.$tabs.'`', $createsql).";\n";
			if(!in_array($tabs, $nobeifne)){
				$strstr	 = "\nDROP TABLE IF EXISTS `$tabs`;\n";
				$strstr	.= $createsql;
				fwrite($file,$strstr);
				
				$rows  	= $this->db->getall('select * from `'.$tabs.'`', function($rs, $cans){
					$vstr = '';
					foreach($rs as $k1=>$v1){
						if(!isempt($v1))$v1 = str_replace("\n",'\n', $v1);
						$v1 = ($v1===null) ? 'NULL' : "'$v1'";
						$vstr.=",$v1";
					}
					$vstr 	= substr($vstr,1);
					$tabs	= $cans['tabs'];
					$strstr	= "INSERT INTO `$tabs` VALUES($vstr);\n";
					fwrite($cans['file'], $strstr);
					return $vstr;
				}, array(
					'file' 	=> $file,
					'tabs'	=> $tabs
				));
				
				echo ''.$tabs.' success count('.count($rows).')'.PHP_EOL;
			}else{
				fwrite($file,$createsql);
				echo ''.$tabs.' break'.PHP_EOL;
			}
		}
		fclose($file);

		echo 'success'.PHP_EOL;
	}
	
}