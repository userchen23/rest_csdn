<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function sendJson($errorno=0,$msg='成功',$data=[]){
    $result = [
        'errorno'   => $errorno,
        'msg'       => $msg,
        'data'      => $data
    ];
    echo json_encode($result);die();
    
}

function lists_to_array($lists){
    $result = [];
    foreach ($lists as $key => $value) {
        if ($value) {
            $result[] = $value->toArray();
        }
    }
    return $result;
}
function get_key_value($arr,$field){
    $result = [];
    foreach ($arr as $key => $value) {
        $result[$value[$field]] = $value;

    }
    return $result;
}