<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2019/9/16
 * Time: 16:24
 */
namespace Group\Service;

use Group\Model\CommonlyGroupModel;

class GroupService extends BaseService
{

    /**
     * 获取分组列表
     * @param $where
     * @param $order
     * @param $page
     * @param $limit
     * @return array
     */
    static function getGroupList($where,$order,$page,$limit){
        $res = self::select('commonly_group',$where,$order,$page,$limit);
        $items = $res['data']['items'];
        foreach ($items as $k => $v){
            $items[$k]['inputtime_name'] = date('Y-m-d H:i',$v['inputtime']);
            $items[$k]['updatetime_name'] = date('Y-m-d H:i',$v['updatetime']);
        }
        $res['data']['items'] = $items;
        return $res;
    }

    /**
     * 获取分类详情成功
     * @param $id
     * @return array
     */
    static function getGroupDetails($id){
        $commonlyGroupTable = new CommonlyGroupModel();
        $commonlyGroupRes = $commonlyGroupTable->where(['id'=>$id])->find();
        $res['commonlyGroupRes'] = $commonlyGroupRes;
        return createReturn(true,$res,'获取成功');
    }

    /**
     * 添加或者编辑分类
     */
    static function addEditGroup($data){
        $commonlyGroupModel = new CommonlyGroupModel();
        $conditionRes = $commonlyGroupModel->CheckData($data);
        if(!$conditionRes['status']) return $conditionRes;
        $condition = $conditionRes['data'];
        if($data['id']){
            $res = $commonlyGroupModel->where(['id'=>$data['id']])->save($condition);
        } else {
            $condition['is_display'] = '1';
            $condition['is_delete'] = '0';
            $condition['inputtime'] = time();
            unset($condition['id']);
            $res = $commonlyGroupModel->add($condition);
        }
        return createReturn(true,$res,'操作成功');
    }

    /**
     * 修改资料
     * @param $id
     * @param $field
     * @param $value
     */
    static function updatinGroup($id,$field,$value){
        $commonlyGroupModel = new CommonlyGroupModel();
        $save[$field] = $value;
        $save['updatetime'] = time();
        $res = $commonlyGroupModel->where(['id'=>$id])->save($save);
        return createReturn(true,$res,'操作成功');
    }

}