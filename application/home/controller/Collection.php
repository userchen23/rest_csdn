<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Blog as BlogModel;
use app\home\model\Classify as ClassifyModel;
use app\home\model\Token as TokenModel;
use app\home\model\Collection as CollectionModel;
class Collection extends Controller
{

    // *RestFul api: collectionManage/pc
    // 
    // *get     : 收藏夹详情，    参数：collection_id
    // *post    : 收藏夹列表，    参数：token
    // *post    : 增加收藏夹，    参数：token,collection_name
    // *put     : 更新收藏夹，    参数：collection_id,collection_name
    // *delete  : 删除收藏夹，    参数：collection_id
    // *
    // *
    private static $server_type = [
        'get','post','put','delete',
    ];

    public static function collectionManage(){

        $method = strtolower($_SERVER['REQUEST_METHOD']);
        //将空判断放在getData可以节省一次判断，但会多调用一次方法,
        //考虑到用户使用时打开首页的频率较高，将首页判断放在这里
        //
        if (empty($_REQUEST['collection_name']) && $method == 'post') {
            return self::colleLists($_REQUEST);
        }
        if (in_array($method, self::$server_type)) {
            //调用请求方式对应的方法
            $data_name = $method . 'Data';
            return self::$data_name($_REQUEST);

        }
        sendJson(1,'请求失败');die();        

    }
    // 收藏首页
    private static function colleLists($request_data){
        $errorno    = 0;
        $msg        = '成功';
        $data       = [];
        $post_data  = $request_data;
        if (empty($post_data)) {
            $errorno    = 1;
            $msg        = 'post内容为空';
            sendJson($errorno,$msg);die();
        }
        $token      = !empty($post_data['token'])?$post_data['token']:'';
        $token_obj  = new TokenModel;
        $result      = $token_obj->getUserInfo($token);

        if (!$result) {
            $errorno    = 2;
            $msg        = 'token不存在或失效';
            sendJson($errorno,$msg,$data);die();
        }
        $user_id        = $result['value']['id'];

        $collection_obj = new CollectionModel;
        $collection_lists = $collection_obj->collectionLists($user_id);
        if (empty($collection_lists)) {
            $errorno    = 3;
            $msg        = '暂无收藏';
            sendJson($errorno,$msg);
        }
        sendJson($errorno,$msg,$collection_lists);
    }

    private static function getData($request_data){

        $id = !empty($request_data['collection_id'])?$request_data['collection_id']:'';
        if(empty($id)){
            sendJson(1,'参数错误',[]);
        }
        $collection_obj = new CollectionModel;
        $collection_info = $collection_obj->getInfo('id',$id);
        if(empty($collection_info)){
            sendJson(2,'暂无数据',[]);
        }
        
        sendJson(0,'ok',$soleBlog);

    }

    private static function postData($request_data){

        $errorno    = 0;
        $msg        = '成功';
        $data       = [];
        $post_data  = $request_data;
        if (empty($post_data)) {
            $errorno    = 1;
            $msg        = 'post为空';
            sendJson($errorno,$msg,$data);die();
        }
        if (empty($post_data['token'])) {
            $errorno    = 2;
            $msg        = 'token为空';
            sendJson($errorno,$msg);
        }
        if (empty($post_data['collection_name'])) {
            $errorno    = 3;
            $msg        = '收藏夹名字不能为空';
            sendJson($errorno,$msg);
        }

        $token      = $post_data['token'];
        $token_obj  = new TokenModel;
        $result     = $token_obj->getUserInfo($token);

        if (!$result) {
            $errorno    = 2;
            $msg        = 'token不存在或失效';
            sendJson($errorno,$msg,$data);die();            
        }
        $user_id        = $result['value']['id'];

        $time =time();
        $tmp_data = [
            'user_id'       => $user_id,
            'collection_name'       => $collection_name,
            'status'        => 1,
            'create_time'   => $time,
            'update_time'   => $time,
        ];        
        
        $blog_obj   = new BlogModel;
        $result     = $blog_obj-> add($tmp_data);

        if (!$result) {
            $errorno    = 5;
            $msg        = '插入失败';
            sendJson($errorno,$msg);die();
        }

        sendJson();die();   

    }

    private static function putData($request_data){

        $errorno    = 0;
        $msg        = '成功';
        $data       = [];
        $post_data  = $request_data;
        if (empty($post_data)) {
            $errorno    = 1;
            $msg        = 'post为空';
            sendJson($errorno,$msg,$data);die();
        }
        $token      = !empty($post_data['token'])?$post_data['token']:'';
        $token_obj  = new TokenModel;
        $result      = $token_obj->getUserInfo($token);

        if (!$result) {
            $errorno    = 2;
            $msg        = 'token不存在或失效';
            sendJson($errorno,$msg,$data);die();            
        }
        $user_id        = $result['value']['id'];

//id,title,classify_id,content,status,create_time,update_time
        $update_data = [];
        if (!empty($title)) {
            $update_data['title'] = $post_data['title'];
        }
        if (!empty($classify_id)) {
            $update_data['classify_id'] = $post_data['classify_id'];
        }
        if (!empty($content)) {
            $update_data['content'] = $post_data['content'];
        }
        $time =time();
        $update_data['update_time'] = $time;    
        
        $blog_obj   = new BlogModel;
        $result     = $blog_obj-> doupdate($update_data);

        if (!$result) {
            $errorno    = 5;
            $msg        = '更新失败';
            sendJson($errorno,$msg);die();
        }

        sendJson();die();           

    }

    private static function deleteData(){

        $errorno    = 0;
        $msg        = '成功';
        $data       = [];
        $delete_data  = $request_data;
        if (empty($delete_data)) {
            $errorno    = 1;
            $msg        = 'post为空';
            sendJson($errorno,$msg,$data);die();
        }
        $token      = !empty($delete_data['token'])?$delete_data['token']:'';
        $token_obj  = new TokenModel;
        $result      = $token_obj->getUserInfo($token);

        if (!$result) {
            $errorno    = 2;
            $msg        = 'token不存在或失效';
            sendJson($errorno,$msg,$data);die();            
        }
        $user_id        = $result['value']['id'];

        $blog_obj   = new BlogModel;
        $result     = $blog_obj-> dodelete('id',$delete_data['blog_id']);

        if (!$result) {
            $errorno    = 5;
            $msg        = '删除失败';
            sendJson($errorno,$msg);die();
        }

        sendJson();die();         
    }

}
