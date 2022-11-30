<template>
    <div class="modal-content">
        <div class="modal-body">
    　      <div class="mb-3 d-flex justify-content-center">変更する内容を入力して登録ボタンを押してください</div>
            <div class="d-flex justify-content-center">
                <div class="form-group" style="margin: 0px 20px; width: 100%;">
                    <div v-for="define in masterDefine" v-bind:key="define.id" class="row" style="height: 40px;">
                        <div class="col-4">{{displayName(define)}}</div>
                        <div class="col-8">
                            <input v-if="define.type == 'value'" type="text" v-model="masterDataList[define.column]">
                            <select v-if="define.type == 'class'" v-model="masterDataList[define.column]">
                                <option v-for="definedClass in define.classes" v-bind:key="definedClass.value" v-bind:value="definedClass.value">{{definedClass.displayName}}</option>
                            </select>
                            <input v-if="define.type == 'date'" type="date" v-model="masterDataList[define.column]" min="1899-12-30"/>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="errorMessage.length != 0" style="text-align: center; color: red; padding: 10px; background-color: #dddddd; margin: 10px 20px 0px 20px;">
                <div>{{errorMessage}}</div>
            </div>
            <div class="modal-footer d-flex justify-content-centerl">
                <button type="button" class="btn btn-primary w-35" style="margin-right: 40px" v-on:click="onClickRegist" v-bind:disabled="!isEnableRegist">登録</button>
                <button type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="onClickCancel">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            masterID: this.op1.masterID,
            masterData: this.op1.masterData,
            masterDefine: this.op1.masterDefine,
            masterDataList: {},
            errorMessage: "",
            isNew: this.op1.isNew,
        }
    },
    methods: {
        //登録処理
        onClickRegist() {
            this.changeListToMasterData();
            if(this.validate())
            {
                //登録処理
                axios.post("updateOrganizationMasterData", {
                    masterID: this.masterID,
                    masterData: this.masterData,
                    isNew: this.isNew,
                }).then((response) => {
                    if(response.data.result)
                    {
                        this.openModal("m112_common_message", "", {
                            message: 'マスタ情報を更新しました',
                            buttons:[{
                                    exec : ()=>{
                                        //マスタデータリロードコールバック実行
                                        this.op1.callbackReload();

                                        //モーダル閉じる
                                        $('body').removeClass('modal-open');
                                        this.cleanModal();
                                        $('.modal-backdrop').remove();
                                    },
                                    caption : "OK",
                                    btnclass : "btn-success"
                                },
                            ],
                        });
                    }
                    else
                    {
                        //エラーモーダル表示
                        this.openModal("m112_common_message", "", {
                            message: response.data.values.message,
                            buttons:[{
                                    exec : ()=>{
                                        //マスタデータリロードコールバック実行
                                        this.op1.callbackReload();
                                        //モーダル閉じる
                                        $('body').removeClass('modal-open');
                                        this.cleanModal();
                                        $('.modal-backdrop').remove();
                                    },
                                    caption : "OK",
                                    btnclass : "btn-success"
                                },
                            ],
                        });
                    }
                }).catch((error) => {
                    //エラーモーダル表示
                    this.openModal("m112_common_message", "", {
                        message: "予期しないエラーが発生しました。ページを読み込みなおしてください。",
                        buttons:[{
                                exec : ()=>{
                                    //モーダル閉じる
                                    $('body').removeClass('modal-open');
                                    this.cleanModal();
                                    $('.modal-backdrop').remove();
                                },
                                caption : "OK",
                                btnclass : "btn-success"
                            },
                        ],
                    });
                });
            }
        },
        //キャンセル処理
        onClickCancel() {            
            this.changeListToMasterData();
            this.masterDataList = {};
        },
        //入力されたデータの保存
        changeListToMasterData(){
            for(let i = 0; i < this.masterDefine.length; i++)
            {
                if(this.masterDefine[i].type == "date"){

                    if(this.masterDataList[this.masterDefine[i].column] === null || this.masterDataList[this.masterDefine[i].column] === ""){
                        if(this.masterDefine[i].column == "valid_date_start"){
                            this.masterData[this.masterDefine[i].column] = 0;
                        }else if(this.masterDefine[i].column == "valid_date_end"){
                            this.masterData[this.masterDefine[i].column] = 2958465;
                        }else{
                            this.masterData[this.masterDefine[i].column] = this.dateStrToSerial(this.masterDataList[this.masterDefine[i].column]);
                        }
                    }else{
                        this.masterData[this.masterDefine[i].column] = this.dateStrToSerial(this.masterDataList[this.masterDefine[i].column]);
                    }
                }else{
                    this.masterData[this.masterDefine[i].column] = this.masterDataList[this.masterDefine[i].column];
                }
            }
        },
        //バリデーション
        validate(){
            //requireが入力されているかチェック
            this.errorMessage = "";
            for(let i = 0; i < this.masterDefine.length; i++)
            {
                if(this.masterDefine[i].required && this.masterDefine[i].type == "class" && this.isNew &&
                !this.masterDefine[i].classes.find(elm => elm.value == this.masterData[this.masterDefine[i].column])){
                    this.errorMessage = "必須入力の項目に未選択のものがあります。"
                    return false;
                }else if(this.masterDefine[i].required && this.masterData[this.masterDefine[i].column].length == 0)
                {
                    this.errorMessage = "必須入力の項目に未入力のものがあります。"
                    return false;
                }
            }
            return true;
        }
    },
    mounted(){
        this.masterDataList = {};
        for(let i = 0; i < this.masterDefine.length; i++)
        {
            if(this.masterDefine[i].type == "date"){
                if(this.masterData[this.masterDefine[i].column] === 0 || this.masterData[this.masterDefine[i].column] === 2958465){
                    this.masterDataList[this.masterDefine[i].column] = null;
                }else{
                    this.masterDataList[this.masterDefine[i].column] = this.serialToDateStr(this.masterData[this.masterDefine[i].column], "YYYY-MM-DD");
                }
            }else{
                this.masterDataList[this.masterDefine[i].column] = this.masterData[this.masterDefine[i].column];
            }
        }
    },
    computed:{
        isEnableRegist(){
            return true;
        },
        displayName(){
            return function(define)
            {
                return define["displayName"] + (define["required"] ? " ※" : "");
            }
        }
    }
};
</script>
