<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Authorization, DNT, User-Agent, Keep-Alive, Origin, X-Requested-With, Content-Type, Accept, x-clientid');
header('Access-Control-Allow-Methods: PUT, POST, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Origin: *');

if (!empty($_GET['uuid'])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://long.open.weixin.qq.com/connect/l/qrconnect?uuid=' . $_GET['uuid'] . (empty($_GET['last']) ? '' : '&last=' . $_GET['last']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3522.0 Safari/537.36'));
    $output = curl_exec($ch);
    curl_close($ch);
    $preg = '/window.wx_errcode=(.*?);w/i';
    $preg1 = "/window.wx_code='(.*?)';/i";
    preg_match_all($preg, $output, $res);
    $wxErrCode = $res[1][0];
    switch ($wxErrCode) {
        case 405:
            preg_match_all($preg1, $output, $res);
            $wxCode = $res[1][0];
            $r = array(
                'status' => 405,
                'msg' => '登陆成功',
                'result' => array(
                    'code' => $wxCode,
                ),
            );
            break;
        case 404:
            $r = array(
                'status' => 404,
                'msg' => array(
                    'title' => '登陆成功',
                    'content' => '请在微信中点击确认即可登录',
                ),
                'result' => array(
                    'wxErrCode' => $wxErrCode,
                ),
            );
            break;
        case 403:
            $r = array(
                'status' => 403,
                'msg' => array(
                    'title' => '您已取消此次登录',
                    'content' => '您可再次扫描登录，或关闭窗口',
                ),
                'result' => array(
                    'wxErrCode' => $wxErrCode,
                ),
            );
            break;
        case 402:
        case 500:
            $r = array(
                'status' => 500,
                'msg' => '需要重新获取uuid',
            );
            break;
        case 408:
            $r = array(
                'status' => 408,
                'msg' => '需要再次请求',
            );
            break;
    }
    echo json_encode($r);
} else {
    $ch = curl_init();
    $appid = empty($_GET['appid']) ? 'wx827225356b689e24' : $_GET['appid'];
    $redirect_uri = empty($_GET['redirect_uri']) ? 'https://qq.jd.com/' : $_GET['redirect_uri'];
    $redirect_uri = urlencode(iconv('gb2312', 'UTF-8', $redirect_uri));
    curl_setopt($ch, CURLOPT_URL, 'https://open.weixin.qq.com/connect/qrconnect?appid=' . $appid . '&scope=snsapi_login&redirect_uri=' . $redirect_uri . '&state=&login_type=jssdk&self_redirect=default');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3522.0 Safari/537.36'));
    $output = curl_exec($ch);
    curl_close($ch);
    $preg = '/src="\/connect\/qrcode\/(.*?)" \/>/i';
    preg_match_all($preg, $output, $res);
    $uuid = $res[1][0];
    if (isset($_GET['img'])) { // 不推荐 - 如果设置了img参数，则返回图片的下载地址，但此地址无法解决Chrome70不信任腾讯域名证书的问题
        $r = array('status' => 1, 'result' => array('wxUUID' => $uuid, 'imgURL' => 'https://open.weixin.qq.com/connect/qrcode/' . $uuid));
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://open.weixin.qq.com/connect/qrcode/' . $uuid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3522.0 Safari/537.36'));
        $output = curl_exec($ch);
        curl_close($ch);
        $r = array('status' => 1, 'result' => array('wxUUID' => $uuid, 'imgData' => 'data:image/jpeg;base64,' . base64_encode($output)));
    }
    echo json_encode($r);
}
