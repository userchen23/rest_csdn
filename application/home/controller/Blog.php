<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Blog as BlogModel;
use app\home\model\Banner as BannerModel;
use app\home\model\Classify as ClassifyModel;
use app\home\model\Token as TokenModel;
class Blog extends Controller
{
    //
    public function getBlogBySort(){
        $classify_id = input('post.classify_id');
        if(empty($classify_id)){
            sendJson(1,'参数错误',[]);
        }
        $blog_obj = new BlogModel;
        $sort_blog = $blog_obj->selectInfo('classify_id',$classify_id);
        if(empty($sort_blog)){
            sendJson(2,'暂无数据',[]);
        }
        
        
        $classify = new ClassifyModel;
        $classifyRes = $classify->getInfoClassify($format['classify_id']);
        $format['classify_name'] = $classifyRes['name'];

        sendJson(0,'ok',$format);
    }

    // *RestFul api: RestFulBlog  对于单条博客进行管理
    // 
    // *get     : 博客首页，     参数：无
    // *get     : 博客详情，     参数：id
    // *post    : 保存博客，     参数：token,title,content,classify_id
    // *put     : 更新博客内容，  参数：blog_id,token,title,content,classify_id
    // *delete  : 删除博客，     参数：blog_id,token
    // *
    // *
    private static $server_type = [
        'get','post','put','delete',
    ];

    public static function RestFulBlog(){

        $method = strtolower($_SERVER['REQUEST_METHOD']);
        //将空判断放在getData可以节省一次判断，但会多调用一次方法,
        //考虑到用户使用时打开首页的频率较高，将首页判断放在这里
        //
        if (empty($_REQUEST) && $method == 'get') {
            return self::index();
        }
        if (in_array($method, self::$server_type)) {
            //调用请求方式对应的方法
            $data_name = $method . 'Data';
            return self::$data_name($_REQUEST);

        }
        return false;        

    }
    // 首页
    private static function index(){

        $blog_obj       = new BlogModel;
        $blog_lists     = $blog_obj->getLists();
        $format_blog    = $blog_obj->formatBlog($blog_lists);

        $banner_obj     = new BannerModel;
        $banner_lists   = $banner_obj->selectInfo('status',1,0,4);
        $format_banner  = $banner_obj->formatBanner($banner_lists);
        $data = [
            'blog'      => $format_blog,
            'banner'    => $format_banner,
        ];

        sendJson(0,'ok',$data);
    }

    private static function getData($request_data){

        $id = !empty($request_data['id'])?$request_data['id']:'';
        if(empty($id)){
            sendJson(1,'参数错误');
        }
        $blog_obj = new BlogModel;
        $blog_info = $blog_obj->getInfo('id',$id);
        if(empty($blog_info)){
            sendJson(2,'博客不存在或已被删除');
        }
        $classify_id    = $blog_info['classify_id'];
        $classify_obj   = new ClassifyModel;
        $classify_info  = $classify_obj->getInfo('id',$classify_id);
        $blog_info['classify_name'] = $classify_info['name'];
        sendJson(0,'ok',$blog_info);

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
        $title          = !empty($post_data['title'])?$post_data['title']:'';
        $classify_id    = !empty($post_data['classify_id'])?$post_data['classify_id']:0;
        $content        = !empty($post_data['content'])?$post_data['content']:'';

        $time =time();
        $tmp_data = [
            'user_id'       => $user_id,
            'title'         => $title,
            'classify_id'   => $classify_id,
            'content'       => $content,
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
