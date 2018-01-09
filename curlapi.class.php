<?php
/**************************************
* Project Name:盛传移动商务平台
* Time:2016-03-22
* Author:MarkingChanning QQ:380992882
**************************************/
error_reporting(0);
require 'querylist/phpQuery.php';
require 'querylist/QueryList.php';
use QL\QueryList;

class curlapi{
	public $url; //提交地址
	public $params; //登入的post数据
	public $postdata; //登入的post数据
	public $cookies=""; //cookie
	public $referer=""; //http referer
	
	/*
		获取验证码
	*/
	public function get_code(){
		$ch = curl_init($this -> url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		preg_match("/Set-Cookie:(.*);/siU", $output, $arr);
		$cookies = $arr[1];
		//cookies存SESSION
		session_start();
		$_SESSION['cookies'] = $cookies;
		//截取GIF二进制图片
		$explode = explode("HttpOnly",$output);
		return $explode = trim($explode[1]);
	}
	
	/*
		模拟登陆
	*/
	public function login(){
		session_start();
		$result = ihttp_request($this -> url, $this -> postdata, $extra = array(), $timeout = 60, $method = 'POST');
		$Cookie = $result['headers']['Set-Cookie'];
		$identity = explode(";", $Cookie[0]);
		$aspxauth = explode(";", $Cookie[1]);
		$Cookie = $identity[0].$aspxauth[0];
		//$_SESSION['cookies'] = $Cookie;
		echo "<pre>";
		print_r($Cookie);
		echo "</pre>";
		exit;
//		$ch=curl_init();

////		$headers = array();
////		$headers[] = 'X-Apple-Tz: 0';
////		$headers[] = 'X-Apple-Store-Front: 143444,12';
////		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
////		$headers[] = 'Accept-Encoding: gzip, deflate';
////		$headers[] = 'Accept-Language: en-US,en;q=0.5';
////		$headers[] = 'Cache-Control: no-cache';
////		$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
////		$headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
////		$headers[] = 'X-MicrosoftAjax: Delta=true';
////		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//		curl_setopt($ch, CURLOPT_URL,$this -> url);
//		curl_setopt($ch, CURLOPT_HEADER,0);
//		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//		curl_setopt($ch,CURLOPT_POST,1);
//		//curl_setopt($ch,CURLOPT_COOKIE,$_SESSION['cookies']);
//		curl_setopt($ch,CURLOPT_POSTFIELDS,$this -> params);
//		curl_setopt ($ch, CURLOPT_REFERER,$this -> url);
//		$result=curl_exec($ch);
//
//		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
//			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
//			$header = substr($result, 0, $headerSize);
//			$body = substr($result, $headerSize);
//		}
//
//		$info = curl_getinfo($ch);
//		echo "<pre>";
//		print_r($result);
//		echo "</pre>";
//		exit;
//		curl_close($ch);
//		return $result;
	}
	
	/*
		curl模拟采集数据
	*/
	public function curl(){
		session_start();
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$this -> url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch,CURLOPT_COOKIE,$_SESSION['cookies']);
		curl_setopt ($ch, CURLOPT_REFERER,$this -> referer);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/*
    curl模拟采集数据，会员数据
	*/
	public function getMembersPage(){
		$cookie = "__RequestVerificationToken=lg88GiqBhlN6RfN_eJY9S65NXk0yntye8UP1xlwKuHbzxtmDdc6v1QGCNBscnD-6tR5BuZZ-oz4H7cWf5ozQLnSASu7Rmvx9XiUw6Yg9jLPz1qNkbr6bSoNz_sk1; Identity=f90a64db5f0e0751d94b839a2f23a73b807c30b6ccb3c9d61b66c3b4a8a2f34746212f1de8d3718d873d0cd4ce2776ccb576f8288642ef7dd4d2c2949ec1f170993fdb1dea440fb2e7c4c7b92c447a03; .ASPXAUTH=E594BBA439C6B7EBDAB8BD2BE1797D8F7811751BA2C3669718DA2131717DC3C943D2AB4C1F0D4AEEA9E1C4D29F68B1C42B15634D71A60BB86CD5230846A1E97C183721838A4E56CA9931A4DE70D371AD4009A92129C5A4F425CFF9C0DCFB5F821D3B871E515CC35515EB928C";
 		$referer = 'http://vip.landee.com.cn/Customer/Customer';
		$result = ihttp_request($this -> url, $this -> params, $extra = array(), $timeout = 60, $method = 'POST', $cookie, $referer);
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		exit;
	}

	/*
	curl模拟采集数据，会员一些详细数据
	*/
	public function getMembersInfos($cookie){
		$result = ihttp_request($this -> url, $this -> params, $extra = array(), $timeout = 60, $method = 'POST', $cookie, $referer='');
		$content = json_decode($result['content'], true);
		return $content;
		echo "<pre>";
		print_r($content);
		echo "</pre>";
		exit;

		session_start();
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$this -> url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_COOKIE,$_SESSION['cookies']);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$this -> params);
		curl_setopt ($ch, CURLOPT_REFERER,$this -> url);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**分析会员数据
	 * @param $rs
	 * @param $page
	 * @return mixed|string
	 */
	public function getMembersInfo($rs, $page){
		$rsBlank = preg_replace("/\s\n\t/","",$rs);
		//$rsBlank = str_replace(' ', '', $rsBlank);
		preg_match_all("/delForm.*>(.*)<\/form>/isU", $rsBlank ,$tables);
		if(isset($tables[1][0])) {
			if($page>1) {
				return preg_replace("/<thead[^>]*>.*<\/thead>/isU", '', $tables[1][0]);
			} else {
				return $tables[1][0];
			}
		} else {
			return '';
		}
		return $tables[1][0];
	}

    /**
     * 获取会员信息下载到CVS
     * @param $html
     * @param $shopname
     */
	public function downMembersCvs($data,$shopname, $cookie){
		$k = 0;
		foreach ($data as &$customer) {
			//获取卡信息
			$customerId = $customer['ClientId'];
			$url = "http://vip.landee.com.cn/Customer/Customer/CustomerCards";
			$params = "customerId=$customerId";
			$result = ihttp_request($url, $params, $extra = array(), $timeout = 60, $method = 'POST', $cookie, $referer='');
			$cards = json_decode($result['content'], true);
			echo "<pre>";
			print_r($cards);
			echo "</pre>";
			exit;

			foreach($cards as $card) {
				//卡号
				$newdata[$k][0] = "\t".$other; //卡号
				$newdata[$k][1] = $other[2]; //姓名
				$newdata[$k][2] = $other[1]; //手机号
				$newdata[$k][3] = $other[3] == '男'?0:1; //性别

				//卡类型
				$newdata[$k][4] = $other[0]; //卡类型

				$newdata[$k][5] = $other[10]; //折扣

				//卡金余额(必填),疗程,
				$newdata[$k][6] = 0; //卡金余额
				$newdata[$k][7] = 0; //充值总额
				$newdata[$k][9] = 0; //消费总额
				$newdata[$k][10] = 0; //赠送金

				//卡金余额
				$k6 = 13+10*($i-1);
				preg_match_all('/(.*)元/isU', $other[$k6], $data1);
				if(isset($data1[1]) && count($data1[1]) == 2) {
					$newdata[$k][6] = str_replace('元:', '', $data1[1][0]);
					$newdata[$k][6] = str_replace('余:次:', '', $data1[1][0]);
					//$newdata[$k][7] = str_replace('疗程:', '', $data1[1][1]);
				} else {
					$newdata[$k][6] = str_replace('元', '', $other[$k6]);
					$newdata[$k][6] = str_replace('余:次', '', $other[$k6]);
					//$newdata[$k][7] = 0;
				}

				//充值总额
				$k7 = 11+10*($i-1);
				$newdata[$k][7] += str_replace('元', '', $other[$k7]); //充值总额

				//消费总额
				$k11 = 12+10*($i-1);
				$newdata[$k][9] += str_replace('元', '', $other[$k11]); //消费总额

				//赠送金
				$k13 = 14+10*($i-1);
				$newdata[$k][10] += str_replace('元', '', $other[$k13]); //赠送金

				$k17 = 17+10*($rows-1);
				$newdata[$k][8] = str_replace('次', '', $other[$k17]); //消费次数

				$newdata[$k][11] = $other[$k18]; //积分

				$newdata[$k][12] = 0; //开卡时间

				//日期格式转换
				$date1 = substr($other[5], 0, 3).' '.substr($other[5], 3, 3).' '.substr($other[5], 19, 4);
				$date1 = date('Y-m-d', strtotime($date1));

				$k19 = 19+10*($rows-1);
				$date2 = substr($other[$k19], 0, 3).' '.substr($other[$k19], 3, 3).' '.substr($other[$k19], 19, 4);

				$date2 = date('Y-m-d', strtotime($date2));
				$newdata[$k][13] = $date1; //最后消费时间
				$newdata[$k][14] = $date2 == '1970-01-01'?$date1:$date2; //生日
				$newdata[$k][15] = ''; //会员备注
				ksort($newdata[$k]);

				$k++;
			}
		}

		//导出CVS
		$cvsstr = "卡号(必填[唯一]),姓名(必填),手机号(必填[唯一]),性别(必填[“0”代表男，“1”代表女]),卡类型(必填[系统编号]),折扣(必填),卡金余额(必填),充值总额,消费次数,消费总额,赠送金,积分,欠款,开卡时间(格式：YYYY-mm-dd),最后消费时间(格式：YYYY-mm-dd),生日(格式：YYYY-mm-dd),生日类型（1阳历，0阴历）,会员备注\n";
		$filename = $shopname.'_会员信息.csv';
		$cvsstr = iconv('utf-8','gb2312//ignore',$cvsstr);

		foreach($newdata as &$v){
			//获取会员备注和欠款
			$keyword = trim($v[0]);
			$this -> url = "http://vip8.sentree.com.cn/shair/consumerHelp!find.action?searchType=1&keyType=1&keyword=$keyword";
			$rs = $this -> curl();

			//会员备注
			$rules = array(
				'mark' => array('textarea','html'),
			);
			$mark = QueryList::Query($rs, $rules)->data;
			$v[16] = $mark[0]['mark'];
			//欠款
			$debt = 0;
			$rules = array(
				'debt' => array('.table_list tr','html'),
			);
			$debtHtml = QueryList::Query($rs, $rules)->data;
			foreach ($debtHtml as $dk => $dv){
				if($dk > 0){
					$debtTmp = explode('</td>', $dv['debt']);
					foreach ($debtTmp as &$v1) {
						$v1 = strip_tags($v1);;
						$v1 = preg_replace("/\s\n\t/","",$v1);
						$v1 = str_replace(' ', '', $v1);
						$v1= trim(str_replace(PHP_EOL, '', $v1));
					}
					if($debtTmp[4] == '未还清' || strpos($debtTmp[4], '未还清')>=0) {
						$debt += $debtTmp[2];
					}
				}
			}
			$v[12] = $debt;

			foreach($v as $k=>&$v1){
				//时间转换
				if($k == 5 || $k == 19) {
					//$v1 = strtotime($v1);
				}
				//转码
				$cvsdata = iconv('utf-8','gb2312//ignore',$v1);
				$cvsstr .= $cvsdata; //用引文逗号分开
				if($k < 19) {
					$cvsstr .= ","; //用引文逗号分开
				}
			}
			$cvsstr .= "\n";
		}
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $cvsstr;
	}

	/*
	curl模拟采集数据，会员套餐数据
	*/
	public function getPackagePage(){
		session_start();
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$this -> url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_COOKIE,$_SESSION['cookies']);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$this -> params);
		curl_setopt ($ch, CURLOPT_REFERER,$this -> url);
		curl_setopt ($ch, CURLOPT_REFERER,$this -> referer);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

    /**
     *获取套餐页面数据
     */
    public function getPackageInfo($rs, $page){
        $rsBlank = preg_replace("/\s\n\t/","",$rs);
        //$rsBlank = str_replace(' ', '', $rsBlank);
        preg_match_all("/table-responsive.*>(.*)<\/form>/isU", $rsBlank ,$tables);
        if(isset($tables[1][0])) {
            if($page>1) {
                return preg_replace("/<thead[^>]*>.*<\/thead>/isU", '', $tables[1][0]);
            } else {
                return $tables[1][0];
            }
        } else {
            return '';
        }
        return $tables[1][0];
    }

    /**
     * 获取会员套餐信息下载到CVS
     * @param $html
     * @param $shopname
     */
    public function downPackageCvs($html,$shopname){
		$rules = array(
			//采集tr中的纯文本内容
			'other' => array('tr','html'),
		);
		$newdata = array();
		$data = QueryList::Query($html, $rules)->data;
		foreach ($data as $k=>&$item) {
			$other = explode('</td>', $item['other']);
			if(count($other) > 8) {
				//unset($other[0]);//去掉第一空白项
				$item['other'] = $other;
				foreach ($other as $k1 => &$v1) {
					$v1 = strip_tags($v1);;
					$v1 = preg_replace("/\s\n\t/","",$v1);
					$v1 = str_replace(' ', '', $v1);
					$v1= trim(str_replace(PHP_EOL, '', $v1));
					if($k1 == 5) {
						$v1 = trim(str_replace(',', '，', $v1));
						$v1 = explode('项目编号:', $v1);
						unset($v1[0]);
					}
				}

				foreach($other[5] as $k2=>$v2) {
					$newA[0] = $other[0]; //手机号
					$newA[1] = "\t".$other[1]; //卡号
					$newA[2] = $other[2]; //姓名
					$newA[3] = $other[3]; //卡名称
					$newA[4] = $other[4]; //卡类型

					$v2 .= "#";
					//获取项目套餐信息
					preg_match('/(.*)，项目名称/isU', $v2, $p1);  //项目编号
					preg_match('/项目名称:(.*)，/isU', $v2, $p2);  //项目名称
					preg_match('/总次数:(.*)，/isU', $v2, $p3);  //总次数
					preg_match('/剩余次数:(.*)，/isU', $v2, $p4);  //剩余次数
					preg_match('/单次消费金额:(.*)，/isU', $v2, $p5);  //单次消费金额
					preg_match('/剩余金额:(.*)#/isU', $v2, $p6);  //剩余金额
                    if(!isset($p6[1])) {
                        preg_match('/剩余金额:(.*)，/isU', $v2, $p6);  //剩余金额
                    }
					preg_match('/失效日期：(.*)#/isU', $v2, $p7);  //失效日期
					$newA[5] = isset($p1[1])?$p1[1]:' ';//项目编号
					$newA[6] = isset($p2[1])?$p2[1]:' ';//项目名称
					$newA[7] = isset($p3[1])?$p3[1]:' ';//总次数
					$newA[8] = isset($p4[1])?$p4[1]:' ';//剩余次数
					$newA[9] = isset($p5[1])?$p5[1]:' '; //单次消费金额
					$newA[10] = isset($p6[1])?$p6[1]:' '; //剩余金额
					$newA[11] = isset($p7[1])?$p7[1]:' ';//失效日期

					$newA[12] = $newA[8];//总剩余次数
					$newA[13] = $newA[10]; //总剩余金额
					$newA[14] = $other[8];
					$newdata[] = $newA;
				}
			}
		}
		//导出CVS
		$cvsstr = "手机号,卡号,姓名,卡名称,卡类型,项目编号,项目名称,总次数,剩余次数,单次消费金额,剩余金额,失效日期,总剩余次数,总剩余金额\n";
		$filename = $shopname.'_会员套餐信息.csv';
		$cvsstr = iconv('utf-8','gb2312//ignore',$cvsstr);
		foreach($newdata as &$v){
			foreach($v as $k=>&$v1){
				//时间转换
				if($k == 5 || $k == 19) {
					//$v1 = strtotime($v1);
				}
				//转码
				$cvsdata = iconv('utf-8','gb2312//ignore',$v1);
				$cvsstr .= $cvsdata; //用引文逗号分开
				if($k < 14) {
					$cvsstr .= ","; //用引文逗号分开
				}
			}
			$cvsstr .= "\n";
		}
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $cvsstr;
    }

	/**
	 * 获取员工信息下载到CVS
	 * @param $html
	 * @param $shopname
	 */
	public function downStaffCvs($html,$shopname){
		$rules = array(
			//采集tr中的纯文本内容
			'other' => array('tr','html'),
		);
		$newdata = array();
		$data = QueryList::Query($html, $rules)->data;
		foreach ($data as $k=>&$item) {
			$other = explode('</td>', $item['other']);
			if(count($other) > 8) {
				//unset($other[0]);//去掉第一空白项
				$item['other'] = $other;
				foreach ($other as $k1 => &$v1) {
					$v1 = strip_tags($v1);;
					$v1 = preg_replace("/\s\n\t/","",$v1);
					$v1 = str_replace(' ', '', $v1);
					$v1= trim(str_replace(PHP_EOL, '', $v1));
				}

				$date1 = substr($other[11], 0, 3).' '.substr($other[11], 3, 3).' '.substr($other[11], 19, 4);
				$date1 = date('Y-m-d', strtotime($date1));
				$newdata[$k][0] = "\t".$other[1];
				$newdata[$k][1] = $other[2];
				$newdata[$k][2] = $other[3];
				$newdata[$k][3] = preg_match('/男/', $other[4])?0:1;
				$newdata[$k][4] = $other[9];
				$newdata[$k][5] = str_replace('阴', '', $other[10]);
				$newdata[$k][5] = str_replace('阳', '', $newdata[$k][5]);
				$newdata[$k][5] = str_replace('"', '', $newdata[$k][5]);
				$newdata[$k][6] = $date1;
				$newdata[$k][7] = $other[8];
				$newdata[$k][8] = '';

				//日期格式含有1900，设置为空
				if(preg_match("/1900/isU", $newdata[$k][5])) {
					$newdata[$k][5] = '';
				}
			}
		}
		unset($newdata[count($newdata)]);
		unset($newdata[count($newdata)]);

		//导出CVS
		$cvsstr = "编号(必填[唯一]),姓名(必填),级别(必填),性别,手机号码,生日,入职时间,身份证号,银行账号\n";
		$filename = $shopname.'_员工信息.csv';
		$cvsstr = iconv('utf-8','gb2312//ignore',$cvsstr);

		foreach($newdata as &$v){
			foreach($v as $k=>&$v1){
				//转码
				$cvsdata = iconv('utf-8','gb2312//ignore',$v1);
				$cvsstr .= $cvsdata; //用引文逗号分开
				if($k < 8) {
					$cvsstr .= ","; //用引文逗号分开
				}
			}
			$cvsstr .= "\n";
		}
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $cvsstr;
	}

}

/**
 * Http协议
 *
 * [WDL] Copyright (c) 2013 B2CTUI.COM
 */
function ihttp_request($url, $post = '', $extra = array(), $timeout = 60, $method = 'GET', $cookie = '', $referer = '') {
	$urlset = parse_url($url);
	if(empty($urlset['path'])) {
		$urlset['path'] = '/';
	}
	if(!empty($urlset['query'])) {
		$urlset['query'] = "?{$urlset['query']}";
	} else {
		$urlset['query'] = '';
	}
	if(empty($urlset['port'])) {
		$urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
	}
	if (strexists($url, 'https://') && !extension_loaded('openssl')) {
		if (!extension_loaded("openssl")) {
			return json_encode(['status' => false, 'msg' => '请开启您PHP环境的openssl']);
			//message('请开启您PHP环境的openssl');
		}
	}
	if(function_exists('curl_init') && function_exists('curl_exec')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlset['scheme']. '://' .$urlset['host'].($urlset['port'] == '80' ? '' : ':'.$urlset['port']).$urlset['path'].$urlset['query']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			if (is_array($post)) {
				$post = http_build_query($post);
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);

		if($cookie != ''){
			curl_setopt($ch,CURLOPT_COOKIE,$cookie);
		}
		if($referer != ''){
			//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt ($ch, CURLOPT_REFERER, $referer);

		}
		if (defined('CURL_SSLVERSION_TLSv1')) {
			curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
		if (!empty($extra) && is_array($extra)) {
			$headers = array();
			foreach ($extra as $opt => $value) {
				if (strexists($opt, 'CURLOPT_')) {
					curl_setopt($ch, constant($opt), $value);
				} elseif (is_numeric($opt)) {
					curl_setopt($ch, $opt, $value);
				} else {
					$headers[] = "{$opt}: {$value}";
				}
			}
			if(!empty($headers)) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}
		}
		$data = curl_exec($ch);
		$status = curl_getinfo($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($errno || empty($data)) {
			return error(1, $error);
		} else {
			return ihttp_response_parse($data);
		}
	}
	//$method = empty($post) ? 'GET' : 'POST';
	$fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
	$fdata .= "Host: {$urlset['host']}\r\n";
	if(function_exists('gzdecode')) {
		$fdata .= "Accept-Encoding: gzip, deflate\r\n";
	}
	$fdata .= "Connection: close\r\n";
	if (!empty($extra) && is_array($extra)) {
		foreach ($extra as $opt => $value) {
			if (!strexists($opt, 'CURLOPT_')) {
				$fdata .= "{$opt}: {$value}\r\n";
			}
		}
	}
	$body = '';
	if ($post) {
		if (is_array($post)) {
			$body = http_build_query($post);
		} else {
			$body = urlencode($post);
		}
		$fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
	} else {
		$fdata .= "\r\n";
	}
	if($urlset['scheme'] == 'https') {
		$fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
	} else {
		$fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
	}
	stream_set_blocking($fp, true);
	stream_set_timeout($fp, $timeout);
	if (!$fp) {
		return error(1, $error);
	} else {
		fwrite($fp, $fdata);
		$content = '';
		while (!feof($fp))
			$content .= fgets($fp, 512);
		fclose($fp);
		return ihttp_response_parse($content, true);
	}
}

function ihttp_response_parse($data, $chunked = false) {
	$rlt = array();
	$pos = strpos($data, "\r\n\r\n");
	$split1[0] = substr($data, 0, $pos);
	$split1[1] = substr($data, $pos + 4, strlen($data));

	$split2 = explode("\r\n", $split1[0], 2);
	preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
	$rlt['code'] = $matches[2];
	$rlt['status'] = $matches[3];
	$rlt['responseline'] = $split2[0];
	$header = explode("\r\n", $split2[1]);
	$isgzip = false;
	$ischunk = false;
	foreach ($header as $v) {
		$row = explode(':', $v);
		$key = trim($row[0]);
		$value = trim($row[1]);
		if (isset($rlt['headers'][$key]) && is_array($rlt['headers'][$key])) {
			$rlt['headers'][$key][] = $value;
		} elseif (isset($rlt['headers'][$key]) && !empty($rlt['headers'][$key])) {
			$temp = $rlt['headers'][$key];
			unset($rlt['headers'][$key]);
			$rlt['headers'][$key][] = $temp;
			$rlt['headers'][$key][] = $value;
		} else {
			$rlt['headers'][$key] = $value;
		}
		if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
			$isgzip = true;
		}
		if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
			$ischunk = true;
		}
	}
	if($chunked && $ischunk) {
		$rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
	} else {
		$rlt['content'] = $split1[1];
	}
	if($isgzip && function_exists('gzdecode')) {
		$rlt['content'] = gzdecode($rlt['content']);
	}

	$rlt['meta'] = $data;
	if($rlt['code'] == '100') {
		return ihttp_response_parse($rlt['content']);
	}
	return $rlt;
}

/**
 * 是否包含子串
 */

function strexists($string, $find) {
	return !(strpos($string, $find) === FALSE);
}
?>