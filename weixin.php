<?php
  header("Content-Type: application/json; charset=utf-8");
  header("Access-Control-Allow-Headers: Authorization, DNT, User-Agent, Keep-Alive, Origin, X-Requested-With, Content-Type, Accept, x-clientid");
  header("Access-Control-Allow-Methods: PUT, POST, GET, DELETE, OPTIONS");
  header("Access-Control-Allow-Origin: *");
  $ch = curl_init();
  $appid = "wx827225356b689e24";
  $redirect_uri = "https://qq.jd.com/";
  $redirect_uri = urlencode(iconv("gb2312", "UTF-8", $redirect_uri));
  curl_setopt($ch, CURLOPT_URL, "https://open.weixin.qq.com/connect/qrconnect?appid=".$appid."&scope=snsapi_login&redirect_uri=".$redirect_uri."&state=&login_type=jssdk&self_redirect=default");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3522.0 Safari/537.36'));
  $output = curl_exec($ch);
  curl_close($ch);
  echo json_encode($output);
