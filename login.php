<?php
/**************************************
* Project Name:盛传移动商务平台
* Time:2016-03-22
* Author:MarkingChanning QQ:380992882
**************************************/
set_time_limit(0);
header("Content-Type: text/html;charset=utf-8");
include_once("curlapi.class.php");
$curl = new curlapi();

//抓取使用
$cookie = "__RequestVerificationToken=lg88GiqBhlN6RfN_eJY9S65NXk0yntye8UP1xlwKuHbzxtmDdc6v1QGCNBscnD-6tR5BuZZ-oz4H7cWf5ozQLnSASu7Rmvx9XiUw6Yg9jLPz1qNkbr6bSoNz_sk1; Identity=f90a64db5f0e0751d94b839a2f23a73b807c30b6ccb3c9d61b66c3b4a8a2f34746212f1de8d3718d873d0cd4ce2776ccb576f8288642ef7dd4d2c2949ec1f170993fdb1dea440fb2e7c4c7b92c447a03; .ASPXAUTH=E594BBA439C6B7EBDAB8BD2BE1797D8F7811751BA2C3669718DA2131717DC3C943D2AB4C1F0D4AEEA9E1C4D29F68B1C42B15634D71A60BB86CD5230846A1E97C183721838A4E56CA9931A4DE70D371AD4009A92129C5A4F425CFF9C0DCFB5F821D3B871E515CC35515EB928C";

if($_GET['action'] == "code"){//获取验证码
	$curl -> url = "http://vip8.sentree.com.cn/shair/vc";
	echo $curl -> get_code();
}else if($_GET['action'] == "login"){
	//$_SESSION['cookies'] = '__RequestVerificationToken=6HPENinz8h24Mdrq-6MMUSLpRAh3sM90rzlxsk7WhAmoKoY4bmYbJ1ZH9LjZ6sgaFQJLsaESEyA6rxGaOcZ366az56PmQd4T5ltGPjTW4Z9i91eYbrAqXif9ns81';
	//$corpid = urlencode($_POST['corpid']);
	//$__RequestVerificationToken = "nLkaEGSH29rdl6bwFvIOP8v3wSGyFiehzEOLRmTyoAjbmIgC0F8aMQjYGOQNVrzvPfR2aIOK5hnk4BTb3O-J5uPC9CUorJPF0BH_JWrNXzZE9cnXnk85I066ZSk1";
	$corpid = 'meixin';
	$uno = 'admin';
	$pwd = 'admin1';
	$params = "corpid={$corpid}&uno={$uno}&pwd={$pwd}";
	$curl -> url = "http://vip.landee.com.cn/Home/SignIn?ReturnUrl=%2f";
	$curl -> postdata = array(
		'corpid'=>$corpid,
		'uno'=>$uno,
		'pwd'=>$pwd,
	);
	$curl -> params = $params;
	$result = $curl -> login();
	$result = json_decode($result,true);
	if($result['code'] == 4){
		echo "验证码错误！";
	}else if($result['code'] == 5){
		echo "不存在的账号！";
	}else if($result['code'] == 6){
		echo "密码错误！";
	}else if($result['role']){
		echo 1;
	}
}else if($_GET['action'] == 'curlmember'){
	$shopname = 'admin';
	$data = '';

    //获取总数
	$params = "keywords=&shop=101&consultant=&vipCategoryId=&isChineseCalendar=0&birthday=&birthdayEnd=&searchType=1&begin=&end=&dateType=1&dateBegin=&dateEnd=&removed=0&isfrozen=0&_search=false&nd=1515464904546&rows=10&page=1&sidx=UpdateDate&sord=desc";
	$curl -> params = $params;
    $curl -> url = "http://vip.landee.com.cn/Customer/Customer/GetList";
	$data = $curl -> getMembersInfos($cookie);
	$data = $data['rows'];
	$curl -> downMembersCvs($data, $shopname, $cookie);
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	exit;
}else if($_GET['action'] == 'curlpackage'){
    $shopname = $_REQUEST['shopname'];
    $data = '';

    //获取总数
    $curl -> url = "http://vip8.sentree.com.cn/shair/timesItem!initTreat.action?set=cash";
    $rs = $curl -> curl();
    preg_match('/共(.*)条/isU', $rs, $totals);
    $totals = isset($totals[1])?$totals[1]:100;

	//总页数
    $pages = ceil($totals/100);
    for($i=1; $i<=$pages; $i++){
        $params = "page.currNum=$i&page.rpp=100&set=cash&r=0.3421386775783387";
        $curl -> params = $params;
        $curl -> url = "http://vip8.sentree.com.cn/shair/timesItem!initTreat.action";
        $pagesData = $curl -> getPackagePage();
        $data .= $curl ->getPackageInfo($pagesData, $i);
    };
    if($data == '') {
        header('Location: index.php');
    }
    $curl -> downPackageCvs($data, $shopname);
}else if($_GET['action'] == 'curlstaff'){
	$shopname = $_REQUEST['shopname'];
	$data = '';

	//获取员工数据
	$curl -> url = "http://vip8.sentree.com.cn/shair/employee!employeeInfo.action?set=manage&r=0.5704847458180489";
	$rs = $curl -> curl();

	$rsBlank = preg_replace("/\s\n\t/","",$rs);
	//$rsBlank = str_replace(' ', '', $rsBlank);
	preg_match_all("/table_fixed_head.*>(.*)<\/form>/isU", $rsBlank ,$tables);

    if(count($tables[0]) == 0) {
        header('Location: index.php');
    }
	$curl -> downStaffCvs($tables[1][0], $shopname);
}
?>