<?php
namespace app\home\controller;

 use \think\Controller;
 use \app\home\model\User as UserModel;
 use \app\home\model\Token as TokenModel;
 use \app\home\model\Blog as BlogModel;
 use \app\home\model\Classify as ClassifyModel;
 /**
  * 
  */
 class User extends Controller
 {

    public function doReg(){
        $errorno    = 0;
        $msg        = '成功';
        $data       = [];
        $post_data  = input('post.');

        if (empty($post_data)) {
            $errorno    = 1;
            $msg        = '未接收到数据';
            sendJson($errorno,$msg,$data);die();
        }

        if (empty($post_data['name'])||empty($post_data['phone'])||empty($post_data['password'])) {
            $errorno    = 2;
            $msg        = '缺少必要参数';
            sendJson($errorno,$msg,$data);die();
        }
        $phone = $post_data['phone'];
        $str_len    = strlen($post_data['phone']);

        if (!preg_match('/1[3458]\d{9}/', $phone)) {
            $errorno    = 3;
            $msg        = '手机号不符合规范';
            sendJson($errorno,$msg,$data);die(); 
        }
        // 
        // if ($str_len != 11) {
        //     $errorno    = 3;
        //     $msg        = '电话号不符合规范';
        //     sendJson($errorno,$msg,$data);die();           
        // }

        $user_obj   = new UserModel;
        $result     = $user_obj->getInfo('phone',$phone);

        if ($result) {
            $errorno    = 4;
            $msg        = '手机号已被使用';
            sendJson($errorno,$msg,$data);die(); 
        }

        $time       = time();

        $tmp_data = [
            'name'           => $post_data['name'],
            'phone'          => $phone,
            'password'       => $post_data['password'],
            'status'         => 1,
            'create_time'    => $time,
            'update_time'    => $time,
        ];
        $result     = $user_obj-> add($tmp_data);

        if (!$result) {
            $errorno    = 5;
            $msg        = '插入失败';
            sendJson($errorno,$msg);die();
        }

        sendJson();die();

    }  

    public function doLogin(){
        $users = [
            'phone' => input('post.phone'),
            'password' => input('post.password')
        ];
        //dump($users);die;
        if(empty($users['phone']) || empty($users['password'])){
            sendJson(3,'数据不能为空',[]);
        }
        $userModel = new UserModel;
        $info = $userModel -> getInfo('phone',$users['phone']);
        $tokenModel = new TokenModel;
        $tmp = [
            'id'    => $info['id'],
            'name'  => $info['name'],
            'phone' => $info['phone'],
            'password' => $info['password']
        ];
        //dump($data);die;
        if(empty($info['phone'])){
            sendJson(2,'用户不存在',[]);     }
        if($users['password'] == $info['password']){
            $token = $tokenModel->setUserInfo($tmp);
            $result = array('token'=>$token,'info'=>$tmp);
            sendJson(0,'登录成功',$result);
            
        }else{
            //sendJson($errorno,$msg,$data);
            sendJson(1,'密码错误',[]);
        }
    }

    public function blogList(){
        $errorno    = 0;
        $msg        = '成功';
        $data       = [];
        $post_data  = input('post.');

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

        $blog_obj   = new BlogModel;
        $result     = $blog_obj->selectInfo('user_id',$user_id);
        $classify_obj   = new ClassifyModel;
        $class_lists = $classify_obj-> changeListsAll();
        foreach ($result as $key => $value) {
            $data[] = [
                'blog_id'       => !empty($value['id'])?$value['id']:0,
                'classify_id'   => !empty($value['classify_id'])?$value['classify_id']:'',
                'class_name'    => !empty($class_lists[$value['classify_id']]['name'])?$class_lists[$value['classify_id']]['name']:'',
                'title'         => !empty($value['title'])?$value['title']:'',
                'content'       => !empty($value['content'])?$value['content']:'',
            ];
        }
        if (!$result) {
            $errorno    = 3;
            $msg        = '查询失败';
            sendJson($errorno,$msg);die();
        }

        sendJson($errorno,$msg,$data);die();         
    }
    //  * RestFulUserInfo 管理用户信息
    //  
    //  * get   : 获取用户信息详情    参数： token
    //  * post  : 
    //  * put   :
    //  * delete:

//     public function saveBlog(){
//         $errorno    = 0;
//         $msg        = '成功';
//         $data       = [];
//         $post_data  = input('post.');

//         if (empty($post_data)) {
//             $errorno    = 1;
//             $msg        = 'post为空';
//             sendJson($errorno,$msg,$data);die();
//         }
//         $token      = !empty($post_data['token'])?$post_data['token']:'';
//         $token_obj  = new TokenModel;
//         $result      = $token_obj->getUserInfo($token);

//         if (!$result) {
//             $errorno    = 2;
//             $msg        = 'token不存在或失效';
//             sendJson($errorno,$msg,$data);die();            
//         }
//         $user_id        = $result['value']['id'];

// //id,title,classify_id,content,status,create_time,update_time
//         $title          = !empty($post_data['title'])?$post_data['title']:'';
//         $classify_id    = !empty($post_data['classify_id'])?$post_data['classify_id']:0;
//         $content        = !empty($post_data['content'])?$post_data['content']:'';

//         $time =time();
//         $tmp_data = [
//             'user_id'       => $user_id,
//             'title'         => $title,
//             'classify_id'   => $classify_id,
//             'content'       => $content,
//             'status'        => 1,
//             'create_time'   => $time,
//             'update_time'   => $time,
//         ];        
        
//         $blog_obj   = new BlogModel;
//         $result     = $blog_obj-> add($tmp_data);

//         if (!$result) {
//             $errorno    = 5;
//             $msg        = '插入失败';
//             sendJson($errorno,$msg);die();
//         }

//         sendJson();die();     
//     }

	// public function log(){
	// 	return $this->fetch();
	// }

}