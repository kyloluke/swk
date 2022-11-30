<template>
    <div class="modal-content" id="C117-01">
        <div class="modal-body">
            <div id="C117-01-01" class="d-flex justify-content-left">設定された表示項目・検索条件を登録します</div>
            <div id="C117-01-02" class="d-flex justify-content-left">※対象期間は保存されません</div>
            <div id="C117-01-03" class="mb-3 d-flex justify-content-left">※注　登録済みの名前を指定すると設定が上書きされます</div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C117-01-04" class="col-sm-3 col-form-label">保存条件名</label>
                <input id="C117-01-05" type="text"  class="col-sm-7 form-control" v-model="saveName"/>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C117-01-06" class="col-sm-3 col-form-label">管理区分</label>
                <input id="C117-01-07" type="radio" class="col-sm-1 col-form-label mt-3" value=0 v-model="shareClass"/>
                <div class="col-sm-2 mt-2">共通</div>
                <input id="C117-01-08" type="radio" class="col-sm-1 col-form-label mt-3" value=1 v-model="shareClass"/>
                <div class="col-sm-4 mt-2">個人</div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C117-01-09" type="button" class="btn btn-primary w-35" style="margin-right: 80px" @click="onClickRegister">登録</button>
                <button id="C117-01-10" type="button" class="btn btn-danger w-35" data-dismiss="modal">キャンセル</button>
            </div>
      　    <div class="message-group row ml-1 mr-1 mb-3">
                <div class="error-message text-center col-sm-12">
                    <div>{{errorMessage}}</div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            shareClass: this.op1.shareClass,
            saveName: this.op1.saveName,
            errorMessage: "",
        }
    },
    methods: {
        //登録ボタン
        async onClickRegister(){
            this.errorMessage = "";
            //空文字はダメ
            if(this.saveName.replace(/\s+/g, "").length == 0)
            {
                this.errorMessage = "保存名を入力してください";
                return;
            }
            //重複チェック
            let dupulicateResult = await this.checkDupulicate();
            //重複していたら上書きコーションして保存
            if(dupulicateResult.isDupulicate)
            {
                this.openModal("m112_common_message", "", {
                    message: '同名で保存されている条件が見つかりました。上書きしてよろしいですか？',
                    buttons:[
                        {
                            exec : ()=>{
                                this.execSave(dupulicateResult.saveID);
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",
                            btnclass : "btn-success"
                        },
                        {
                            exec : ()=>{
                            },
                            caption : "キャンセル",
                            btnclass : "btn-danger"
                        }
                        ],
                });
            }
            //重複していなかったら新規保存
            else
            {
                this.execSave(0);
            }
        },
        //重複チェック
        async checkDupulicate(){
            return new Promise((resolve, reject) =>{
                axios.get('checkGeneralSearchSaveName',{
                    params:{
                        'shareClass' : this.shareClass,
                        'saveName' : this.saveName,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        resolve({
                            isDupulicate: response.data.values.isDupulicate,
                            saveID: response.data.values.saveID,
                        });
                    }
                    else
                    {
                        reject(response.data.values.message);
                    }
                }).catch(error=>{
                });
            });
        },
        //保存実行
        async execSave(saveID){
            axios.post('saveGeneralSearchCondition',{
                'shareClass': this.shareClass,
                'unitType': this.op1.unitType,
                'saveName': this.saveName,
                'saveID': saveID,
                'targetList': this.op1.targetList,
            }).then(response => {
                if(response.data.result)
                {
                    this.openModal("m112_common_message", "", {
                        message: '保存しました',
                        buttons:[
                            {
                                exec : ()=>{
                                    this.op1.onSaveList(this.saveName, this.shareClass);
                                    $('body').removeClass('modal-open');
                                    this.cleanModal();
                                    $('.modal-backdrop').remove();
                                },
                                caption : "OK",
                                btnclass : "btn-success"
                            },
                        ]
                    });
                }
                else
                {
                    this.openModal("m112_common_message", "", {
                        message: '保存に失敗しました。' + response.data.values.message,
                        buttons:[
                            {
                                exec : ()=>{
                                    $('body').removeClass('modal-open');
                                    this.cleanModal();
                                    $('.modal-backdrop').remove();
                                },
                                caption : "OK",
                                btnclass : "btn-success"
                            },
                        ]
                    });
                    reject();
                }
            }).catch(error=>{
                this.openModal("m112_common_message", "", {
                    message: '予期しないエラーにより保存に失敗しました。',
                    buttons:[
                        {
                            exec : ()=>{
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",
                            btnclass : "btn-success"
                        },
                    ]
                });
            });
        }
    },
    computed: {

    }
}
</script>