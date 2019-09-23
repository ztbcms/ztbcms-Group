<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2019/9/16
 * Time: 16:00
 */
namespace Group\Controller;

use Common\Controller\AdminBase;
use Group\Service\GroupService;
use Group\Model\CommonlyGroupModel;

/**
 * 分类管理
 * Class GroupController
 * @package Aliyunvideo\Controller
 */
class GroupController extends AdminBase
{
    /**
     * 分类列表
     */
    public function groupList(){
        if(IS_AJAX){
            $where = [];
            $page = I('page','1','trim');
            $limit = I('limit','20','trim');
            $type = I('type','','trim');
            if($type) $where['type'] = $type;
            $title = I('title','','trim');
            if($title) $where['title'] = ['like',['%'.$title.'%']];
            $is_display = I('is_display','','trim');
            if(is_numeric($is_display)) $where['is_display'] = $is_display;
            $where['is_delete'] = '0';
            $where['parent_id'] = '0';
            $res = GroupService::getGroupList($where,'listorder desc,id desc',$page,$limit);
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /**
     * 分类详情
     */
    public function groupDetails(){
        if(IS_AJAX){
            $id = I('id','','trim');
            $res = GroupService::getGroupDetails($id);
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }

    /**
     * 添加或者编辑分类
     */
    public function addEditGroup(){
        $data = I('post.');
        $res = GroupService::addEditGroup($data);
        $this->ajaxReturn($res);
    }

    /**
     * 编辑分类资料
     */
    public function updatinGroup(){
        $id = I('id','','trim'); //id
        $field = I('field','','trim'); //字段
        $value = I('value','','trim'); //值
        $res = GroupService::updatinGroup($id,$field,$value);
        $this->ajaxReturn($res);
    }

    /**
     * 三级分类列表
     */
    public function groupList3(){
        if(IS_AJAX){
            $type = I('type');
            $where = ['type' => $type];
            $title = I('title','','trim');
            if($title) $where['title'] = ['like',['%'.$title.'%']];
            $is_display = I('is_display','','trim');
            if(is_numeric($is_display)) $where['is_display'] = $is_display;
            $res = GroupService::getChildList(0, $where);
            $this->ajaxReturn(self::createReturn(true, $res, '获取成功'));
        } else {
            $this->display();
        }
    }

    /**
     * 分类详情
     */
    public function groupDetails3(){
        if(IS_AJAX){
            $id = I('id','','trim');
            $res = GroupService::getGroupDetails($id);
            if($res['status']) $res['data']['commonlyGroupRes']['parent_id'] = GroupService::getPid($id);
            $this->ajaxReturn($res);
        } else {
            $this->display();
        }
    }



    /**
     * 获取下级分类
     */
    public function getCateList(){
        $type = I('type');
        $id = I('id');
        $current_id = I('current_id');
        $res = GroupService::getCateList($type,$id,$current_id);
        $this->ajaxReturn($res);
    }

    /**
     * 添加/编辑三级分类
     */
    public function addEditCate(){
        $data = I('post.');
        $data['parent_id'] = end($data['parent_id']);
        $commonlyGroupTable = new CommonlyGroupModel();
        $lv = $commonlyGroupTable->where(['id' => $data['parent_id']])->getField('lv');
        $data['lv'] = $lv+1;
        $res = GroupService::addEditGroup($data);
        $this->ajaxReturn($res);
    }

}