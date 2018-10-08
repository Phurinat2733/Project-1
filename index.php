<?php
$access_token = 'gdfWCjyBwS+xb/EusK4cvcTATrcIgs1bK6kkU1cx/Bhi3Tta9n08ZPt/tFPS7iWFLIWMxLYckZaGVeXnrpOCxnK/5FkG+Vp6tadAk2DBLE29Ej0VrSgDFuHiOM16Qf2N2TPGvEu3iXHdOt62LUqLAAdB04t89/1O/w1cDnyilFU=';
$host = "ec2-107-22-211-182.compute-1.amazonaws.com";
$user = "mmdkvvqziulstc";
$pass = "e10240d71df70c411f5201bc37491e9091491ff276b8d8b66f8e507ea5b7dc22";
$db = "dcv361109jo6fh";
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");
function showtime($time)
{
	$date = date("Y-m-d");
	$h = split(":", $time);
	if ($h[1] < 15)
	{
		$h[1] = "00";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:0:00' and '$date $h[0]:15:00' order by \"DATETIME\" desc limit 1";
	}
	else
	if ($h[1] >= 15 && $h[1] < 30)
	{
		$h[1] = "15";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:15:01' and '$date $h[0]:30:00' order by \"DATETIME\" desc limit 1";
	}
	else
	if ($h[1] >= 30 && $h[1] < 45)
	{
		$h[1] = "30";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:30:01' and '$date $h[0]:45:00' order by \"DATETIME\" desc limit 1";
	}
	else
	if ($h[1] >= 45)
	{
		$h[1] = "45";
		$selectbydate = "select * from weatherstation where \"DATETIME\" BETWEEN '$date $h[0]:45:01' and '$date $h[0]:59:59' order by \"DATETIME\" desc limit 1";
	}
	
	return array(
		$h[0] . ":" . $h[1],
		$selectbydate
	);
}
// database
$dbconn = pg_connect("host=" . $GLOBALS['host'] . " port=5432 dbname=" . $GLOBALS['db'] . " user=" . $GLOBALS['user'] . " password=" . $GLOBALS['pass']) or die('Could not connect: ' . pg_last_error());
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
$Light = file_get_contents('https://api.thingspeak.com/channels/331361/fields/3/last.txt');
$water = file_get_contents('https://api.thingspeak.com/channels/331361/fields/4/last.txt');
$HUM = file_get_contents('https://api.thingspeak.com/channels/331361/fields/2/last.txt');
$TEM = file_get_contents('https://api.thingspeak.com/channels/331361/fields/1/last.txt');
$aba = ('https://i.imgur.com//yuRTcoH.jpg');
// convert
$sqlgetlastrecord = "select * from weatherstation order by \"DATETIME\" desc limit 1";
if (!is_null($events['events']))
{
	// Loop through each event
	foreach($events['events'] as $event)
	{
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text')
		{
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$messages = ['type' => 'text', 'text' => "ยินดีต้อนรับคุณเข้าสูู่ แอปของเรา."."\n". "กรุณาพิมพ์ [H] เพื่อดูเมนูนะครับ 😊😊😊" 
			// "text"
			];
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "H")
			{
				$messages = ['type' => 'text', 'text' => "พิมพ์ตัวอักษรตามที่กำหนดให้"."\n"."\n"."[1] เว็บไซต์ทางการของการยางแห่งประเทศไทย"."\n"."[2] ข้อมูลข่าวสารเกี่ยวกับยางพารา" . "\n"."[3] ราคาปุ๋ยที่มีวางจำหน่ายในสหกรณ์การเกษตรจังหวัดตรัง" ."\n"."[4] ราคายาง" . "\n" . "[5] ราคาของปุ๋ยยางพาราที่ถูกที่สุดในช่วงอายุต่างๆ" . "\n"  . "[6] วิธีการผสมปุ๋ยใช้เอง"."\n"."[7] ตารางคำนวณสูตรปุ๋ยเคมียางพารา"."\n"."[8] ตารางคำนวณสูตรปุ๋ยอินทรีย์ยางพารา"."\n". "[9] ข้อมูลสภาพอากาศจากกรมอุตุนิยมวิทยา"."\n". "[10] กราฟกำหนดการเชิงเส้นแสดงต้นทุนที่ต่ำที่สุดของการใส่ปุ๋ยอินทรีย์ควบคู่กับปุ๋ยเคมี"."\n". "[11] แผนภูมิแท่งแสดงการเปรียบเทียบราคาของปุ๋ยเคมี ปุ๋ยผสม และปุ๋ยอินทรย์"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "4"){
				
				$messages = ['type' => 'text', 'text' => "ราคายางวันนี้ : " . "ข้อมูลจากการยางแห่งประเทศไทย" .  "\n" . "http://www.rubber.co.th/rubber2012/menu5.php"  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "1"){
				
				$messages = ['type' => 'text', 'text' => "เว็บไซต์ทางการของการยางแห่งประเทศไทย " .  "\n" . "http://www.raot.co.th/main.php?filename=index." . "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "6"){
				
				$messages = ['type' => 'text', 'text' => "วิธีการผสมปุ๋ยใช้เอง : " . "ขอขอบคุณสำหรับข้อมูลจาก NanaGarden" .  "\n" . "https://www.nanagarden.com/topic/3829."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "3"){
				
				$messages = ['type' => 'text', 'text' => "ราคาปุ๋ย" .  "\n" . "https://docs.google.com/document/d/1CJaSBeO7fPn5N9c0lXvK2z7MOp6qqiL-WqSRVJ24dAg/edit."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "2"){
				
				$messages = ['type' => 'text', 'text' => "ดูข้อมูลข่าวสารเกี่ยวกับยางพารา"  .  "\n" . "http://www.raot.co.th/more_news.php?cid=10&filename=index/."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "7"){
				
				$messages = ['type' => 'text', 'text' => "ตารางคำนวณแม่ปุ๋ยเพื่อนำมาผสมใช้เอง" ."\n" . "คำนวณหาแม่ปุ๋ยมาผสมทำปุ๋ยโดยใช้ Microsoft Excel" .  "\n" ."\n" . "ตารางคำนวณแม่ปุ๋ยที่ต้องใช้ผสมสำหรับต้นยางพาราอายุ 1-2 ปี" . "\n" . "https://docs.google.com/spreadsheets/d/151hWN-xpXELAoAobiiPCeaMdo36XjanaIcruVy1Uwb4/edit#gid=1861185759" . "\n" . "ตารางคำนวณหาแม่ปุ๋ยที่ต้องใช้ผสมสำหรับต้นยางพาราอายุ 3-6 ปี" . "\n" . "https://docs.google.com/spreadsheets/d/1ImikdFx3pxiTdImsqEGHMRnRr0v1dOC0s9ZTN6hkwck/edit#gid=2133540489" . "\n" . "ตารางคำนวณหาแม่ปุ๋ยที่ต้องนำมาผสมสำหรับต้นยางพาราอายุ 7-15 ปี" . "\n"  . "https://docs.google.com/spreadsheets/d/1d2hC9fdd-ABVojhGcm_BrHcO7-Dkxb2Uic8hK78fWSY/edit?usp=sharing" . "\n" . "ตารางคำนวณหาแม่ปุ๋ยเพื่อนำมาผสมสำหรับต้นยางพาราอายุ 15 ปี ขึ้นไป" . "\n" . "https://docs.google.com/spreadsheets/d/1AcpnJdL-TozGhZWLJtlT4LJXsB1J8FdQInrqWmvznVo/edit#gid=1178761180" . "\n" . "[H] เพื่อดูเมนู"];
			}
			 
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "5"){
				
				$messages = ['type' => 'text', 'text' => "ราคาปุ๋ยใส่ยางพาราที่ถูกที่สุดในช่วงอายุต่างๆ" ."\n" . "คำนวณหาราคาปุ๋ยที่ถุกที่สุดโดยวิธีเปรียบเทียบอัตราส่วน" .  "\n" . "https://docs.google.com/document/d/1xnbQIHYP_yboKn3CEE819JvgdpLwhJVhgp2aACyc-ww/edit"  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "สวัสดี"){
				
				$messages = ['type' => 'text', 'text' => "สวัสดีครับ 😄😄😄" ."\n" . "ยินดีต้อนรับคุณเข้าสู่ CAL. "  .  "\n" . "พิมพ์ [H] เพื่อดูเมนูเลยนะครับ 😄😄😄 "];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "Hi"){
				
  				$messages = ['type' => 'text', 'text' => "Hi 😄😄😄" ."\n" . "Welcome to Cal "  .  "\n" . "Print [H] for menu 😄😄😄"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "รัก"){
				
  				$messages = ['type' => 'text', 'text' => "รักเหมือนกัน 😍😍😍" ."\n" . "Welcome to CAL. "  .  "\n" . "click [H] for menu 😄😄😄"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "ฝันดี"){
				
  				$messages = ['type' => 'text', 'text' => "นอนหลับฝันดีนะครับ 😍😍😍" ."\n" . "ขอบคุณที่ใช้บริการ. "  .  "\n" . "พิมพ์ [H] เพื่อดูเมนู 😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "ขอบคุณ"){
				
  				$messages = ['type' => 'text', 'text' => "ขอบคุณที่ใช้บริการนะครับ 😍😍😍" ."\n" . "Thanks for Use . "  .  "\n" . "พิมพ์ [H] เพื่อดูเเมนูนู 😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "บาย"){
				
  				$messages = ['type' => 'text', 'text' => "ขอบคุณที่ใช้บริการนะครับ 😍😍😍" ."\n" . "Thanks for Use CAL. "  .  "\n" . "พิมพ์ [H] เพื่อดูเมนู 😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "Thankyou"){
				
  				$messages = ['type' => 'text', 'text' => "Your'e Welcome 😍😍😍" ."\n" . "Thanks for Use CAL. "  .  "\n" . "Print [H] for menu 😄😄😄"];
			}
			
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "Bye"){
				
  				$messages = ['type' => 'text', 'text' => "Bye Bye 😍😍😍" ."\n" . "Thanks for Use CAL. "  .  "\n" . "Print [H] for menu 😄😄😄"];
			}
	                
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "เป็นไงบ้าง"){
				
  				$messages = ['type' => 'text', 'text' => "ฉันสบายดี ขอบคุณ 😍😍😍" ."\n" . "ขอบคุณที่ใช้บริการ. "  .  "\n" . "พิมพ์ [H] เพื่อดู นะครับ😄😄😄"];
			}
	               
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "แตก1"){
				
  				$messages = ['type' => 'text', 'text' => "สวยพี่สวย!!!!!" ."\n" . "ขอบคุณที่ใช้บริการ. " . "\n" . "พิมพ์ [H] เพื่อดูเมนู นะครับ😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "สวยพี่สวย"){
				
  				$messages = ['type' => 'text', 'text' => "แตก1!!!!!" ."\n" . "ขอบคุณที่ใช้บริการ. " . "\n" . "พิมพ์ [H] เพื่อดูเมนู นะครับ😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "8"){
				
				$messages = ['type' => 'text', 'text' => "ตารางคำนวณวัสดุธรมมชาติเพื่อนำมาผสมปุ๋ยอินทรีย์ใช้เอง" ."\n" . "คำนวณหาแม่ปุ๋ยมาผสมทำปุ๋ยโดยใช้ Microsoft Excel" .  "\n" ."\n" . "ตารางคำนวณวัสดุธรรมชาติที่ต้องใช้ผสมสำหรับต้นยางพาราอายุ 1-2 ปี" . "\n" . "https://docs.google.com/spreadsheets/d/1aX9VouWO6bQZ46oGtOFy-Hfl98xO0Ez53ptniZx0d8Y/edit#gid=158716413" . "\n" . "ตารางคำนวณหาวัสดุธรรมชาติที่ต้องใช้ผสมสำหรับต้นยางพาราอายุ 3-6 ปี" . "\n" . "https://docs.google.com/spreadsheets/d/1Kno--Czqum_9wd6femsedI9XD82JqUUz_Ki3dH4gDSY/edit#gid=2010124778" . "\n" . "ตารางคำนวณหาวัสดุธรรมชาติที่ต้องนำมาผสมสำหรับต้นยางพาราอายุ 7-15 ปี" . "\n"  . "https://docs.google.com/spreadsheets/d/1n7aAK7SAuuOr2RJBsfsA_UIKkKgakPVylVMfG-c8jvI/edit#gid=988927414" . "\n" . "ตารางคำนวณหาวัสดุธรรมชาติเพื่อนำมาผสมสำหรับต้นยางพาราอายุ 15 ปี ขึ้นไป" . "\n" . "https://docs.google.com/spreadsheets/d/1QHkA8JM9TdpXEN1rWOX_g6sQEZWUcV0QGzKv-Zikie8/edit#gid=1407124671" . "\n" . "[H] เพื่อดูเมนู"];
			}
			 
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "9"){
				
  				$messages = ['type' => 'text', 'text' => "https://www.tmd.go.th/daily_forecast.php" ."\n" . "ขอบคุณที่ใช้บริการ. " . "\n" . "พิมพ์ [H] เพื่อดูเมนู นะครับ😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "10")
			{
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://www.picz.in.th/images/2018/09/12/fANESe.jpg",
    				'previewImageUrl' => "https://www.picz.in.th/images/2018/09/12/fANESe.jpg"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "11"){
				
  				$messages = ['type' => 'text', 'text' => "พิมพ์ตัวอักษรตามที่กำหนดให้"."\n"."\n"."[12] แผนภูมิแท่งเปรียบเทียบราคาปุ๋ยเคมี ปุ๋ยผสม และปุ๋ยอินทรีย์ อายุ 1-2 ปี"."\n"."[13]แผนภูมิแท่งเปรียบเทียบราคาปุ๋ยเคมี ปุ๋ยผสม และปุ๋ยอินทรีย์ อายุ 3-6 ปี " . "\n"."[14] แผนภูมิแท่งเปรียบเทียบราคาปุ๋ยเคมี ปุ๋ยผสม และปุ๋ยอินทรีย์ อายุ 7-15 ปี" ."\n"."[15]แผนภูมิแท่งเปรียบเทียบราคาปุ๋ยเคมี ปุ๋ยผสม และปุ๋ยอินทรีย์ อายุ 15 ปี ขึ้นไป"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "12")
			{
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://www.picz.in.th/images/2018/09/12/fAPmU0.jpg",
    				'previewImageUrl' => "https://www.picz.in.th/images/2018/09/12/fAPmU0.jpg"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "13")
			{
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://www.picz.in.th/images/2018/09/12/fAs4Nv.jpg",
    				'previewImageUrl' => "https://www.picz.in.th/images/2018/09/12/fAs4Nv.jpg"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "14")
			{
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://www.picz.in.th/images/2018/09/12/fAuaSR.jpg",
    				'previewImageUrl' => "https://www.picz.in.th/images/2018/09/12/fAuaSR.jpg"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "15")
			{
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://www.picz.in.th/images/2018/09/12/fA69Y1.jpg",
    				'previewImageUrl' => "https://www.picz.in.th/images/2018/09/12/fA69Y1.jpg"];
			}
			// Message Event = TextMessage
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "112")
			{
				$messages = [
				'type' => 'video',
				'originalContentUrl' => "https://youtu.be/iByxlVvWrww",
    				'previewImageUrl' => "https://media.giphy.com/media/MuC9gjT2pE1XQDW8PH/giphy.gif"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "17")
			{
				$messages = ['type' => 'location','title'=> 'location','\n','เปิดทำการ','address'=> 'สหกรณ์การเกษตร',
				'latitude'=> 7.503257,'longitude'=> 99.631138];
			}
					
			//EndCase
			if (trim(strtoupper($text)) == "a")
			{
				$messages = ['type' => 'text', 'text' => "a"];
			}
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "a")
			{
				$messages = [
				'type' => 'text',
				'text' => "https://drive.google.com/open?id=14rP9TkpqLo3UwBcUzOu5zeoWu2tMp9eR"];
			}
			if (trim(strtoupper($text)) == "a")
			{
				$messages = ['type' => 'text', 'text' => "https://drive.google.com/open?id=14rP9TkpqLo3UwBcUzOu5zeoWu2tMp9eR"];
			}
			if ($text == "รูป")
			{
				$messages = ['type' => 'image', 'originalContentUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg", 'previewImageUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg"];
			}
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "info")
			{
				$messages = ['type' => 'text', 'text' => "ยางพาราเป็นพืชเศรฐกิจไทย" ."\n"."อ่านเพิ่มเติม: https://th.wikipedia.org/wiki/%E0%B8%A2%E0%B8%B2%E0%B8%87%E0%B8%9E%E0%B8%B2%E0%B8%A3%E0%B8%B2"];
			}
				
			if ( ereg_replace('[[:space:]]+', '', trim($text)) == "O")
			{
				$rs = pg_query($dbconn, $sqlgetlastrecord) or die("Cannot execute query: $query\n");
				$templink = "";
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
				}
				$messages = ['type' => 'image', 'originalContentUrl' => $templink, 'previewImageUrl' => $templink];
			}
			$textSplited = split(" ", $text);
			if ( ereg_replace('[[:space:]]+', '', trim($textSplited[0])) == "O")
			{
				$dataFromshowtime = showtime($textSplited[1]);
				$rs = pg_query($dbconn, $dataFromshowtime[1]) or die("Cannot execute query: $query\n");
				$templink = ""; 
				$qcount=0;
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
					$qcount++;
				}
				//$messages = ['type' => 'text', 'text' => "HI $dataFromshowtime[0] \n$dataFromshowtime[1] \n$templink"
				if ($qcount > 0){
				$messages = [
				'type' => 'image',
				'originalContentUrl' => $templink,
					'previewImageUrl' => $templink
				];}
				else {
					$messages = [
						'type' => 'image',
						'originalContentUrl' => "https://imgur.com/aOWIijh.jpg",
							'previewImageUrl' => "https://imgur.com/aOWIijh.jpg" 
		
						];
				}
			}
			if ($text == "O")
			{
				$rs = pg_query($dbconn, $sqlgetlastrecord) or die("Cannot execute query: $query\n");
				$templink = "";
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
				}
				$messages = ['type' => 'image', 'originalContentUrl' => $templink, 'previewImageUrl' => $templink];
			}
			
			/*if($text == "image"){
			$messages = [
			$img_url = "http://sand.96.lt/images/q.jpg";
			$outputText = new LINE\LINEBot\MessageBuilder\ImageMessageBuilder($img_url, $img_url);
			$response = $bot->replyMessage($event->getReplyToken(), $outputText);
			];
			}*/
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = ['replyToken' => $replyToken, 'messages' => [$messages], ];
			$post = json_encode($data);
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $access_token
			);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}
	}
}
echo "OK";
echo $date;
