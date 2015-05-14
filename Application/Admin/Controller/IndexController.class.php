<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends AdminController{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        $today = strtotime(date('Y-m-d', time())); //今天
        $count_day = 15; //查询最近n天
        $user_model = D('User');
        for($i = $count_day; $i--; $i >= 0){
            $day = $today - $i * 86400; //n天前日期
            $day_after = $today - ($i - 1) * 86400; //n-1天前日期
            $map['ctime'] = array(
                array('egt', $day),
                array('lt', $day_after)
            );
            $user_reg_date[] = date('m月d日', $day);
            $user_reg_count[] = (int)$user_model->where($map)->count();
        }
        $this->assign('count_day', $count_day);
        $this->assign('user_reg_date', json_encode($user_reg_date));
        $this->assign('user_reg_count', json_encode($user_reg_count));
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 回收站
     * @author jry <598821125@qq.com>
     */
    public function recycle($model = 3){
        if(!$model){
            $this->error('模型ID错误');
        }
        $map['status'] = array('eq', '-1');
        $current_model = D('CategoryModel')->getModelById($model);
        $volist = D($current_model['name'])->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->select();
        $page = new \Think\Page(D($current_model['name'])->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        $this->assign('page', $page->show());
        $this->assign('volist', $this->int_to_icon($volist));
        $this->assign('__MODEL_LIST__', D('CategoryModel')->getAllModel(array('id' => array('egt', '3'))));
        $this->assign('__CURRENT_MODEL_', $current_model);
        $this->meta_title = '回收站';
        $this->display();
    }

    /**
     * 完全删除指定文件目录
     * @author jry <598821125@qq.com>
     */
    public function rmdirr($dirname = RUNTIME_PATH){
        $file = new \Org\Util\File();
        $result = $file->del_dir($dirname);
        if($result){
            $this->success("缓存清理成功");
        }else{
            $this->error("缓存清理失败");
        }
    }

    /**
     * 编辑器上传
     * @author jry <598821125@qq.com>
     */
    public function upload(){
        exit(D('PublicUpload')->upload());
    }

    /**
     * KindEditor编辑器下载远程图片
     * @author jry <598821125@qq.com>
     */
    public function downremoteimg(){
        exit(D('PublicUpload')->downremoteimg());
    }

    /**
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager(){
        exit(D('PublicUpload')->fileManager());
    }
}