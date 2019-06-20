<?php
namespace app\home\model;
use \think\Model;
use app\home\model\Base;
use app\home\model\Classify as ClassifyModel;

class Blog extends Base
{
    public $table = 'blog';

    public function formatBlog($blogLists){
        $classify_obj = new ClassifyModel;
        $classify_lists = $classify_obj->getListsAll();
        $classify_res   = get_key_value($classify_lists,'id');

        foreach ($blogLists as $key => $value) {
            $result[] = [
                'id'            => $value['id'],
                'title'         => $value['title'],
                'classify_id'   => $value['classify_id'],
                'classify_name' => $classify_res[$value['classify_id']]['name'],
            ];
        }
        return $result;
    }

}
