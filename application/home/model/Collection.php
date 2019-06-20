<?php
namespace app\home\model;
use \think\Model;
use app\home\model\Base;
use app\home\model\Classify as ClassifyModel;
use app\home\model\Blog as BlogModel;

class Collection extends Base
{
    public $table = 'collection';

    public function collectionLists($user_id){
        $collection_lists = $this->selectInfo('user_id',$user_id);
        if (empty($collection_lists)) {
            return [];
        }
        $data = [];
        foreach ($collection_lists as  $value) {
            $data[] = [
                'id'   => $value['id'],
                'collection_name'   => $value['collection_name'],
            ];
        }
        return $data;

    }
}
