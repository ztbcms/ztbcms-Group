<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <style>
        .imgListItem {
            height: 120px;
            border: 1px dashed #d9d9d9;
            border-radius: 6px;
            display: inline-flex;
            margin-right: 10px;
            margin-bottom: 10px;
            position: relative;
            cursor: pointer;
            vertical-align: top;
        }
        .deleteMask {
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: 120px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 40px;
            opacity: 0;
        }
        .deleteMask:hover {
            opacity: 1;
        }
    </style>
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

                            <el-form-item v-if="is_cover == '1'" label="封面图">
                                <template v-if="form.cover_url != ''">
                                    <div class="imgListItem">
                                        <img :src="form.cover_url" style="width: 120px;height: 120px;">
                                        <div class="deleteMask" @click="uploadImg">
                                            <span style="line-height: 120px;font-size: 22px" class="el-icon-upload"></span>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="imgListItem">
                                        <div @click="uploadImg" style="width: 120px;height: 120px;text-align: center;">
                                            <span style="line-height: 120px;font-size: 22px" class="el-icon-plus"></span>
                                        </div>
                                    </div>
                                </template>
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
                    is_cover: '{:I("get.is_cover")}',
                    form: {
                        pid: 0,
                        title: '',
                        is_display : '',
                        type: '{:I("get.type")}',
                        id: '{:I("get.id")}',
                        cover_url:''
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
                    },
                    uploadImg: function(){
                        this.upload_flag = 'img';
                        layer.open({
                            type: 2,
                            title: '',
                            closeBtn: false,
                            content: '{:U("Upload/UploadCenter/imageUploadPanel")}&max_upload=1',
                            area: ['70%', '80%']
                        })
                    },
                    ZTBCMS_UPLOAD_FILE: function(event){
                        var that = this;
                        if(that.upload_flag == 'img'){
                            this._uploadImg(event)
                        }
                    },
                    _uploadImg: function(){
                        var that = this;
                        var files = event.detail.files;
                        that.form.cover_url = files[0].url;
                    }
                },
                mounted: function () {
                    if(this.id){
                        this.getDetails();
                    }
                    //图片
                    window.addEventListener('ZTBCMS_UPLOAD_FILE', this.ZTBCMS_UPLOAD_FILE.bind(this));
                }
            })
        })
    </script>
</block>
