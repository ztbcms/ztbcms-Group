<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>

            <div class="filter-container">
                <el-input size="small" v-model="listQuery.title" placeholder="分类名称" style="width: 200px;" class="filter-item">

                </el-input>

                <el-select v-model="listQuery.is_display" size="small"  placeholder="请选择类别">
                    <el-option label="全部" value=""></el-option>
                    <el-option label="显示" value="1"></el-option>
                    <el-option label="不显示" value="0"></el-option>
                </el-select>

                <el-button @click="doSearch" size="small" type="primary" icon="el-icon-search">
                    搜索
                </el-button>

                <el-button class="filter-item" style="margin-left: 10px;" size="small" type="primary" @click="getDetails()">
                    添加
                </el-button>
            </div>

            <el-table size="small"
                      :key="tableKey"
                      :data="list"
                      border
                      fit
                      highlight-current-row
                      style="width: 100%;"
            >
                <el-table-column label="分类名" align="left" width="">
                    <template slot-scope="{row}">
                        <div :style="'margin-left: '+(row.lv*50)+'px;'">
                            <span style="display: inline-block;width: 100px;float: left;margin-top: 5px;">{{ row.title }}</span>
                            <a style="float: left;" :href="row.cover_url" target="_blank">
                                <img v-if="row.cover_url && listQuery.is_cover == '1'" :src="row.cover_url" style="width: 30px;height: 30px;">
                            </a>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="是否显示" width="150px" align="center">
                    <template slot-scope="{row}">
                        <el-switch @change="updateShow(row.id, row.is_display)" v-model="row.is_display" size="small" active-value="1" inactive-value="0"></el-switch>
                    </template>
                </el-table-column>

                <el-table-column label="排序" width="150px" align="center">
                    <template slot-scope="{row}">
                        {{ row.listorder }}
                        <i @click="updateSort(row.id, row.listorder)" class="el-icon-edit update-sort"></i>
                    </template>
                </el-table-column>

                <el-table-column label="操作" width="300px" align="center" class-name="small-padding fixed-width">
                    <template slot-scope="{row}">
                        <el-button type="primary" size="mini" @click="getDetails(row.id)">
                            编辑
                        </el-button>
                        <el-button v-if="listQuery.type != 'channel'" type="danger" size="mini" @click="delGoods(row.id)">
                            删除
                        </el-button>
                    </template>
                </el-table-column>

            </el-table>

        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }
        .pagination-container {
            padding: 32px 16px;
        }

        .update-sort{
            font-size: 16px;
            color: #409EFF;
            cursor: pointer;
        }
    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    tableKey: 0,
                    list: [],
                    total: 0,
                    listQuery: {
                        page: 1,
                        limit: 20,
                        type: '{:I("get.type")}',
                        title: '',
                        is_display:'',
                        is_cover: '{:I("get.is_cover")}'
                    }
                },
                watch: {},
                filters: {
                    parseTime: function (time, format) {
                        return Ztbcms.formatTime(time, format)
                    },
                    statusFilter: function (status) {
                        var statusMap = {
                            published: 'success',
                            draft: 'info',
                            deleted: 'danger'
                        };
                        return statusMap[status]
                    }
                },
                methods: {
                    doSearch: function(){
                        var that = this;
                        that.listQuery.page = 1;
                        that.getList();
                    },
                    getList: function() {
                        var that = this;
                        var url = '{:U("Group/Group/groupList3")}';
                        var data = that.listQuery;
                        that.httpGet(url, data, function(res){
                            if(res.status){
                                that.list = res.data;
                            }
                        });
                    },
                    getDetails: function(id){
                        var that = this;
                        var url = '{:U("Group/Group/groupDetails3")}';
                        url += '&type=' + that.listQuery.type;
                        url += "&is_cover=" + "{$_GET['is_cover']}";
                        if(id) url += '&id='+id;
                        layer.open({
                            type: 2,
                            title: '编辑',
                            content: url,
                            area: ['60%', '70%'],
                            end: function(){
                                that.getList();
                            }
                        })
                    },
                    delGoods: function(id){
                        var that = this;
                        var url = '{:U("Group/Group/updatinGroup")}';
                        layer.confirm('您确定需要删除？', {
                            btn: ['确定','取消'] //按钮
                        }, function(){
                            var data = {id: id, field: 'is_delete', value: 1};
                            that.httpPost(url, data, function(res){
                                if(res.status){
                                    layer.msg('操作成功', {icon: 1});
                                    that.getList();
                                }
                            });
                        });
                    },
                    updateShow: function(id, value){
                        var that = this;
                        var url = '{:U("Group/Group/updatinGroup")}';
                        var data = {id: id, field: 'is_display', value: value};
                        that.httpPost(url, data, function(res){
                            if(res.status){
                                that.$message.success('修改成功');
                                that.getList();
                            }
                        });
                    },
                    updateSort: function(id, sort){
                        var that = this;
                        that.$prompt('请输入排序', {
                            confirmButtonText: '保存',
                            cancelButtonText: '取消',
                            inputValue: sort,
                            roundButton: true,
                            closeOnClickModal: false,
                            beforeClose: function(action, instance, done){
                                if(action == 'confirm'){
                                    var url = '{:U("Group/Group/updatinGroup")}';
                                    var data = {id: id, field: 'listorder', value: instance.inputValue};
                                    that.httpPost(url, data, function(res){
                                        if(res.status){
                                            that.$message.success('修改成功');
                                            that.getList();
                                            done();
                                        }
                                    });
                                }else{
                                    done();
                                }
                            }
                        }).then(function(e){}).catch(function(){});
                    }
                },
                mounted: function () {
                    this.getList();
                },

            })
        })
    </script>
</block>
