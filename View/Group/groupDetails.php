<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content">
                        <el-form ref="form" :model="form" label-width="80px">
                            <el-form-item label="名称">
                                <el-input v-model="form.title" size="small" style="width: 200px;"></el-input>
                            </el-form-item>
                            <el-form-item label="排序">
                                <el-input v-model="form.listorder" size="small" type="number" style="width: 200px;"></el-input>
                            </el-form-item>
                            <el-form-item label="是否显示">
                                <el-switch v-model="form.is_display" size="small" active-value="1" inactive-value="0"></el-switch>
                            </el-form-item>
                            <el-form-item>
                                <el-button size="small" type="primary" @click="onSubmit">提交</el-button>
                                <el-button size="small" type="danger" @click="onCancel" v-if="form.type != 'channel'">重置</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="16"><div class="grid-content "></div></el-col>
            </el-row>
        </el-card>
    </div>

    <script>
        $(document).ready(function () {
            window.__app = new Vue({
                el: '#app',
                data: {
                    id: '{:I("get.id")}',
                    form: {
                        pid: 0,
                        title: '',
                        is_display : '',
                        type: '{:I("get.type")}',
                        id: '{:I("get.id")}'
                    },
                    props: {
                        checkStrictly: true,
                        lazy: true
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    getDetails: function(){
                        var that = this;
                        var url = '{:U("Group/Group/groupDetails")}';
                        var data = {id: that.id};
                        that.httpGet(url, data, function(res){
                            if(res.status){
                                that.form = res.data.commonlyGroupRes;
                            }else{
                                layer.msg(res.msg, {time: 1000});
                            }
                        });
                    },
                    onSubmit: function(){
                        var that = this;
                        var url = '{:U("Group/Group/addEditGroup")}';
                        var data = that.form;
                        that.httpPost(url, data, function(res){
                            if(res.status){
                                layer.msg('提交成功', {time: 1000}, function(){
                                    parent.layer.closeAll();
                                });
                            }else{
                                layer.msg(res.msg, {time: 1000});
                            }
                        });
                    },
                    onCancel: function(){
                        var that = this;
                        Vue.set(that, 'form', {
                            type: '{:I("get.type")}',
                            id : "{$_GET['id']}"
                        });
                    }
                },
                mounted: function () {
                    if(this.id){
                        this.getDetails();
                    }
                }
            })
        })
    </script>
</block>
