<?php
namespace app\home\model;
use think\Model;
use think\Db;
class Classify extends Base{
    public function getInfoClassify($classid){
        $lists = $this->where('id',$classid)->find();
        if($lists){
        	$lists = $lists->toArray();
        }else{
        	return [];
        }
        return $lists;
    }
}
