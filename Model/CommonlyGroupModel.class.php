<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2019/9/6
 * Time: 11:56
 */

namespace Group\Model;

use Common\Model\RelationModel;

class CommonlyGroupModel extends RelationModel
{

    protected $tableName = 'commonly_group';

    const DEFAULT_TYPE = 'default';  //默认分类


    /**
     * @param $accesskey_id
     * @param $accesskey_secret
     * @param $video_valid_time
     * @return array
     */
    static function CheckData($data){
        if(!$data['parent_id']) $data['parent_id'] = '0';
        if(!$data['title']) return createReturn(false,null,'分类名称不能为空');
        if(!$data['type'])  return createReturn(false,null,'我们不建议分类类型为空');
        $condition['parent_id'] = $data['parent_id'];
        $condition['title'] = $data['title'];
        $condition['updatetime'] = time();
        $condition['type'] = $data['type'];
        $condition['is_display'] = $data['is_display'];
        if($data['listorder']) $condition['listorder'] = $data['listorder'];
        return createReturn(true,$condition,'校验成功');
    }

}