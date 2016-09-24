<?php
/**
 * @FileName: ip_helper.php
 * @Author: Tekin
 * @QQ: 3316872019
 * @Email: tekintian@gmail.com
 * @Supported: http://dev.yunnan.ws/
 * @Date:   2016-09-24 11:54:11
 * @Last Modified by:   Tekin
 * @Last Modified time: 2016-09-24 19:30:13
 */

/**
 * 获取客户端IP
 * @return [type] 返回IP地址信息
 */
function getRealIp()
{
    static $realip;

    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

    if (strpos($realip, ',') === false) {
        $sUserIp = $realip;
    } else {
        $arrUserIp = explode(',', $realip);
        $sUserIp = $arrUserIp[0];
    }
    return $sUserIp;
}

/**
 * 获取IP对应的地址信息, 支持 taobao,
 * @param  string $apiProvider API服务商名称,目前支持 taobao, sina, pconline 三个API接口调用
 * @return [type]              [description]
 */
function getIpLocation($apiProvider = 'taobao')
{
    $ip = getRealIp();
    //使用CI里面的获取IP方法
    /* $CI =& get_instance();
    $ip = $CI->input->ip_address();*/

    $is_lip = preg_match('/(127\.|10\.|172\.16|192\.168|::1)/', $ip);
    if ($is_lip) {
        return '内网地址';
        exit();
    }

    switch ($apiProvider) {
        case 'taobao':
            $ipContent = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
            $ipArr = json_decode($ipContent, true); //将获取的IP数据转换为数组

            $county = $ipArr['data']['county'] ? $ipArr['data']['county'] : '';
            $isp = $ipArr['data']['isp'] ? '  ' . $ipArr['data']['isp'] : '';

            $location = $ipArr['data']['country'] . $ipArr['data']['region'] . $ipArr['data']['city'] . $county . $isp;
            $ipLocation = empty($location) ? '内网地址' : $location;
            return $ipLocation;
            break;
        case 'sina': //for array
            $ipContent = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip);
            $ipArr = json_decode($ipContent, true); //将获取的IP数据转换为数组

            if ($ipArr['ret'] == 1) {
                $isp = $ipArr['isp'] ? '  ' . $ipArr['isp'] : '';
                $location = $ipArr['country'] . ' ' . $ipArr['province'] . ' ' . $ipArr['city'] . $ipArr['district'] . $isp;
            } else {
                $ipLocation = '内网地址';
            }
            return $ipLocation;
            break;
        case 'pconline':
            $ipContent = file_get_contents('http://whois.pconline.com.cn/ipJson.jsp?ip=' . $ip);
            $ipContent = iconv('gbk', 'utf-8', $ipContent); //转换编码为UTF-8
            preg_match('/IPCallBack\((.*)\);\}/', $ipContent, $matches);

            $ipArr = json_decode($matches[1], true); //将获取的IP数据转换为数组
            $location = $ipArr['addr'];
            $ipLocation = empty($location) ? '内网地址' : $location;
            return $ipLocation;
            break;

        default:
            $ipContent = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
            $ipArr = json_decode($ipContent, true); //将获取的IP数据转换为数组

            $county = $ipArr['data']['county'] ? $ipArr['data']['county'] : '';
            $isp = $ipArr['data']['isp'] ? '  ' . $ipArr['data']['isp'] : '';

            $location = $ipArr['data']['country'] . $ipArr['data']['region'] . $ipArr['data']['city'] . $county . $isp;
            $ipLocation = empty($location) ? '内网地址' : $location;
            return $ipLocation;
            break;
            break;
    }

}
