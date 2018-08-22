# 微信扫码登陆 - PHP版

微信开放平台扫码登陆解析处理工具😊，将frame数据处理为图片或Base64图片数据返回客户端进行扫码。

解决Chrome70中open.weixin.qq.com腾讯SSL证书不被信任的问题，解决Chrome68中frame跨域被拦截的问题。

`最大特点`：**扫码登录无跳转**🤠。

`demo`：[https://apio.xyz/weixin-login-php/](https://apio.xyz/weixin-login-php/)

## 须知
仅适用于`微信开放平台`-`网站应用`。

## 使用方法
第一步：根据Appid及授权回调域获取二维码图片和微信UUID；  
第二步：获取微信服务器返回的Code（[详见微信开放平台文档](https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316505&token=&lang=zh_CN)）。  

## 测试方法

直接请求`https://apio.xyz/weixin-login-php/weixin.php?appid=您的appid&redirect_uri=您在微信开放平台后台设置的授权回调域`，获取二维码和UUID。  

再次请求`https://apio.xyz/weixin-login-php/weixin.php?uuid=上一步得到的UUID`，获得登录结果的数据。  

您要是懒得部署一套，可以直接使用以上地址。

## 小提示

1. 使用此方法，无需经由服务器端跳转，可以直接获得code。如果是Electron环境，可以直接在主进程请求。  
2. 建议为该功能单独部署，可在您所有项目中使用同一个接口。
3. 理论上你可以模拟任何网站的二维码，但是没有私钥就算拿到code也没有用。  

## 其它语言版本

[Node.js](https://github.com/yi-ge/weixin-login)
