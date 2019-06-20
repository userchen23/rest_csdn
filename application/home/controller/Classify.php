<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Blog as BlogModel;
use app\home\model\Classify as ClassifyModel;

class Classify extends Controller
{
    public function lists(){
        $classify_obj = new ClassifyModel;
        $result       = $classify_obj -> getListsAll();
        if (!$result) {
            $errorno = 1;
            $msg    = '失败';
            sendJson($errorno,$msg);
        }
        $data = [];
        foreach ($result as $key => $value) {
            $data[] =[
                'id'    => $value['id'],
                'name'  => $value['name']
            ];
        }

        sendJson(0,'ok',$data);
    }

}
