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

    /**
     * 按顺序获取 (合并为一维数组)
     * 1.等级
     * 2.排序
     *
     * @param $id
     * @param $where
     * @return array
     */
    static function getChildList($id, $where){
        $where['parent_id'] = $id;
        $where['is_delete'] = '0';
        $data = M('commonly_group')->field('id,listorder,title,is_display,parent_id,lv')->where($where)->order('`listorder` DESC')->select() ?: [];
        $offset = 0;
        foreach($data as $key => $val){
            $child = self::getChildList($val['id'], $where);
            $offset++;
            if(count($child) > 0){
                array_splice($data, $offset, 0, $child);
                $offset += count($child);
            }
        }
        return $data;
    }

    /**
     * 获取下级分类
     */
    static function getCateList($type,$id,$current_id){
        $where = ['type' => $type, 'parent_id' => $id,'is_delete'=>'0'];
        $data = M('commonly_group')->field('id,title,lv')->where($where)->order('`listorder` DESC')->select() ?: [];
        if(!$id) array_unshift($data, ['id' => '0', 'title' => '顶级分类', 'lv' => 1]);
        foreach($data as &$v){
            if($v['id']){
                $has_child = M('commonly_group')->where(['parent_id' => $v['id'],'is_delete'=>'0'])->count();
                if($current_id == $id && $current_id){
                    $has_child = 0;
                }
            } else {
                $has_child = 0;
            }

            $v = [
                'value' => $v['id'],
                'label' => $v['title'],
                'leaf' => $has_child > 0 ? 0 : 1,
                'level' => $v['lv'],
                'disabled' => ( $current_id == $id || $current_id == $v['id'] ) && $current_id || $v['lv'] >= 3
            ];
        }
        return self::createReturn(true, $data);
    }

    /**
     * @param $id
     * @param $need_self
     * @return array
     */
    static function getPid($id, $need_self = false){
        if($need_self) $return = [$id]; else $return = [];
        $pid = M('commonly_group')->where(['id' => $id,'is_delete'=>'0'])->getField('parent_id');
        if($pid){
            array_unshift($return, $pid);
            $pid = M('commonly_group')->where(['id' => $pid,'is_delete'=>'0'])->getField('parent_id');
            if($pid) array_unshift($return, $pid);
        }else{
            array_unshift($return, '0');
        }
        return $return;
    }

}