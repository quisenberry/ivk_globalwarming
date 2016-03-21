<?php
$link_remoteurl = 'https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=1ac4ce04-4cab-4981-a688-bd597cb09273&plant=9a1db92b-8888-4879-96b1-4f1cc3c46faf&splang=en-US';
$html = getHTML($link_remoteurl);
if($html){
    $link1 = getlink('ctl00$ContentPlaceHolder1$PublicPagePlaceholder$PageUserControl$ctl00$UserControl1$_diagram', 'src="', $html);
    $link2 = getlink('ctl00$ContentPlaceHolder1$PublicPagePlaceholder$PageUserControl$ctl00$UserControl2$_diagram', 'src="', $html);
    $link3 = getlink('<span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_UserControl0_LabelCO2Value" class="base-label"','>',$html,'</span>');
    file_put_contents(dirname(__FILE__).'/temp/co2.txt', $link3);
    getImage('https://www.sunnyportal.com'.$link1, 'image1.png');
    getImage('https://www.sunnyportal.com'.$link2, 'image2.png');
}

function getlink($needle1, $needle2, $html, $needle3 = '"'){
    $needle1_pos = strpos($html, $needle1);
    $needle2_pos = strpos($html, $needle2, $needle1_pos+strlen($needle1));
    $needle3_pos = strpos($html, $needle3, $needle2_pos+strlen($needle2));
    return substr($html, $needle2_pos+strlen($needle2),($needle3_pos-$needle2_pos-strlen($needle2)));
}

function getHTML($url){
    $cookies = dirname(__FILE__).'/cookie.txt';
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.4.2564.88 Safari/537.36", // who am i
        CURLOPT_REFERER        => 'https://www.sunnyportal.com/',     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIEJAR => $cookies,
        CURLOPT_COOKIEFILE => $cookies,
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    if($err){
        return false;
    }else{
        return $content;
    }
}


function getImage($url, $image){
    $cookies = dirname(__FILE__).'/cookie.txt';
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.4.2564.88 Safari/537.36", // who am i
        CURLOPT_REFERER        => 'https://www.sunnyportal.com/',     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIEJAR => $cookies,
        CURLOPT_COOKIEFILE => $cookies,
        CURLOPT_BINARYTRANSFER => true,
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $raw=curl_exec($ch);
    curl_close ($ch);
    $saveto = dirname(__FILE__).'/temp/'.$image;
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <title>IVK Global Warming</title>
        <!-- Styles -->
        <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="/css/styles.css" type="text/css">
        <!-- JS -->
        <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <style>
            #content{
                position: relative;
            }
            
            #image1{
                position: absolute;
                top :400px;
                right: 30px;
            }
            
            #image2{
                position: absolute;
                top :700px;
                left: 35px;
            }
            
            #image3{
                position: absolute;
                top :562px;
                left: 102px;
            }
        </style>
    </head>
    <body class="page1">
        <div id="content" class="content">
            <img  id="content-image" src="/images/page5/bg.png" alt="" usemap="#map">
            <div id="image3"><strong>CO2 Avoided: <?php echo $link3 ?></strong></div>
            <img id="image1" src="/temp/image1.png">
            <img id="image2" src="/temp/image2.png">
            <map name="map" id="map">
                <area alt="" title="" href="index.html" shape="rect" coords="17,17,165,113">
                <area alt="" title="" href="/page6.html" shape="rect" coords="547,19,757,110" />
            </map>
        </div>
    </body>
</html>