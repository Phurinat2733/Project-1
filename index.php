<?php
$access_token = 'DcVdiO4eO10Ba1aHOV4YvUkRvD5vsC9gNEvWflhwPHS0KRRpANO0mqVzdXsOiaTpu9z7SnZxm7aE6GwYQeqD1Y+YvvoSs6VFfQrgNQoGEa3jOPA9o7j3q+U6GyWgauLBORRoTbXJgtxA5Tx5wx1yBAdB04t89/1O/w1cDnyilFU=';
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
			$messages = ['type' => 'text', 'text' => "ยินดีต้อนรับเข้าสูู่ Cal"."\n". "กรุณากด [H] เพื่อดูเมนูนะครับ 😊😊😊" 
			// "text"
			];
			if (ereg_replace('[[:space:]]+', '', strtoupper($text)) == "H")
			{
				$messages = ['type' => 'text', 'text' => "พิมพ์ตัวอักษรตามที่กำหนดให้"."\n"."\n"."[1] ข้อมูลยางพารา"."\n"."[2] ข้อมูลปุ่ยยางพาราและธาตุอาหารที่ยางพาราต้องการ" . "\n"."[3] ราคาปุ๋ยที่มีวางจำหน่ายในสหกรณ์การเกษตรจังหวัดตรัง" ."\n"."[4] ราคายาง" . "\n" . "[5] ราคาของปุ๋ยยางพาราที่ถูกที่สุดในช่วงอายุต่างๆ" . "\n"  . "[6] วิธีการผสมปุ๋ยใช้เอง"."\n"."[7] ตารางคำนวณสูตรปุ๋ยยางพารา"."\n"."#ข้อมูลนี้จะคำนวณโดยใช้โปรแกรม microsoft excel"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "4"){
				
				$messages = ['type' => 'text', 'text' => "ราคายางวันนี้ : " . "ข้อมูลจากการยางแห่งประเทศไทย" .  "\n" . "http://www.rubber.co.th/rubber2012/menu5.php."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "1"){
				
				$messages = ['type' => 'text', 'text' => "ยางพารา : " . "ข้อมูลจากวิกิพีเดีย" .  "\n" . "th.wikipedia.org/wiki/ยางพารา."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "6"){
				
				$messages = ['type' => 'text', 'text' => "วิธีการผสมปุ๋ยใช้เอง" .  "\n" . "https://www2.moac.go.th/ewt_news.php?nid=436."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "3"){
				
				$messages = ['type' => 'text', 'text' => "ราคาปุ๋ย" .  "\n" . "https://docs.google.com/document/d/1CJaSBeO7fPn5N9c0lXvK2z7MOp6qqiL-WqSRVJ24dAg/edit."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "2"){
				
				$messages = ['type' => 'text', 'text' => "ปุ๋ย" ."\n" . "ดูข้อมูลปุ๋ยและวิธีการใช้ปุ๋ยในแต่ละช่วงอายุ" .  "\n" . "http://www.pravitgroup.co.th/2016/fertilizer_rubber_tree/."  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "7"){
				
				$messages = ['type' => 'text', 'text' => "ตารางคำนวณแม่ปุ๋ยเพื่อนำมาผสมใช้เอง" ."\n" . "คำนวณหาแม่ปุ๋ยมาผสมทำปุ๋ยโดยใช้ Microsoft Excel" .  "\n" ."\n" . "ตารางคำนวณแม่ปุ๋ยที่ต้องใช้ผสมสำหรับต้นยางพาราอายุ 1-2 ปี" . "\n" . "https://docs.google.com/spreadsheets/d/1U97mzmDEU4y3amdJBGJOXJydq0PjFAyFvqSnGwdcVgg/edit#gid=2078396931" . "\n" . "ตารางคำนวณหาแม่ปุ๋ยที่ต้องใช้ผสมสำหรับต้นยางพาราอายุ 3-6 ปี" . "\n" . "https://docs.google.com/spreadsheets/d/1wNmXscKpbDh36H2883s2daVxpQCq_wd_geGMn-10YeQ/edit#gid=419917429" . "\n" . "ตารางคำนวณหาแม่ปุ๋ยที่ต้องนำมาผสมสำหรับต้นยางพาราอายุ 7-15 ปี" . "\n"  . "https://docs.google.com/spreadsheets/d/1xWtCHXIVOJ-dTH0wNPs8mwU7LXT9iz07svlbwe29DW0/edit#gid=598783784" . "\n" . "ตารางคำนวณหาแม่ปุ๋ยเพื่อนำมาผสมสำหรับต้นยางพาราอายุ 15 ปี ขึ้นไป" . "\n" . "https://docs.google.com/spreadsheets/d/1B1O2ro23FUxQUXMbZayJ2JPYACAn1jrLlt92dvkezqM/edit#gid=232299637" . "\n" . "[H] เพื่อดูเมนู"];
			}
			 
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "5"){
				
				$messages = ['type' => 'text', 'text' => "ราคาปุ๋ยใส่ยางพาราที่ถูกที่สุดในช่วงอายุต่างๆ" ."\n" . "คำนวณหาราคาปุ๋ยที่ถุกที่สุดโดยวิธีเปรียบเทียบอัตราส่วน และขั้นตอนวิธีซิมเพล็กซ์ " .  "\n" . "https://docs.google.com/document/d/1xnbQIHYP_yboKn3CEE819JvgdpLwhJVhgp2aACyc-ww/edit"  .  "\n" . "[H] เพื่อดูเมนู"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "สวัสดี"){
				
				$messages = ['type' => 'text', 'text' => "สวัสดีครับ 😄😄😄" ."\n" . "ยินดีต้อนรับเข้าสู่ ASTF "  .  "\n" . "กด [H] เพื่อดูเมนูเลยนะครับ 😄😄😄 "];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "Hi"){
				
  				$messages = ['type' => 'text', 'text' => "Hi 😄😄😄" ."\n" . "Welcome to Cal "  .  "\n" . "click [H] for menu 😄😄😄"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "รัก"){
				
  				$messages = ['type' => 'text', 'text' => "รักเหมือนกันนะ 😍😍😍" ."\n" . "Welcome to Cal "  .  "\n" . "click [H] for menu 😄😄😄"];
			}
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "ฝันดี"){
				
  				$messages = ['type' => 'text', 'text' => "ฝันดีเช่นกัน 😍😍😍" ."\n" . "Welcome to Cal "  .  "\n" . "click [H] for menu 😄😄😄"];
			}
			
			//BeginCase
			if (ereg_replace('[[:space:]]+', '', trim($text)) == "ขอบคุณ"){
				
  				$messages = ['type' => 'text', 'text' => "ขอบคุณที่ใช้บริการนะครับ 😍😍😍" ."\n" . "Thanks for Use Cal "  .  "\n" . "click [H] for menu 😄😄😄"];
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
				
			if ( ereg_replace('[[:space:]]+', '', trim($text)) == "วิธีการผสมปุ๋ย")
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
			if ( ereg_replace('[[:space:]]+', '', trim($textSplited[0])) == "วิธีการผสมปุ๋ย")
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
			if ($text == "วิธีการผสมปุ๋ย")
			{
				$rs = pg_query($dbconn, $sqlgetlastrecord) or die("Cannot execute query: $query\n");
				$templink = "";
				while ($row = pg_fetch_row($rs))
				{
					$templink = $row[1];
				}
				$messages = ['type' => 'image', 'originalContentUrl' => $templink, 'previewImageUrl' => $templink];
			}
			if ($text == "map")
			{
				$messages = ['type' => 'location','title'=> 'my location','address'=> 'เคลิ้ม',
				'latitude'=> 8.652311,'longitude'=> 99.918031];
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
