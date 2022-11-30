<template>
    <div class="modal-content" id="C121-01">
        <div class="modal-body">
            <div id="C121-01-01" class="mb-3 d-flex justify-content-center">インフォメーション登録</div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C121-01-02" class="col-sm-3 col-form-label">種別</label>
                <select id="C121-01-03" class="col-sm-7 form-control" v-model="targetInformationTypeID">
                    <option v-for="information_type in informationTypeList" :key="information_type.information_type_id" v-bind:value="information_type.information_type_id">{{ information_type.information_type_name }}</option>
                </select>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C121-01-04" class="col-sm-3 col-form-label">事業所</label>
                <select id="C121-01-05" class="col-sm-7 form-control" v-model="targetOfficeID">
                    <option value=0>全社</option>
                    <option v-for="office in officeList" :key="office.office_id" v-bind:value="office.office_id">{{ office.office_name }}</option>
                </select>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C121-01-08" class="col-sm-3 col-form-label">件名</label>
                <input id="C121-01-09" class="col-sm-7 form-control" type="text" v-model="information_subject_name" />
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C121-01-10" class="col-sm-3 col-form-label">内容</label>
                <textarea id="C121-01-11" style="resize:none" rows="8" class="col-sm-7 form-control" v-model="information_contants" />
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C121-01-12" class="col-sm-3 col-form-label">有効期間開始</label>
                <div class="form-group col-sm-7">
                    <input id="C121-01-13" type="date" class="form-control" v-model="valid_date_start_str" min="1899-12-30"/>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C121-01-14" class="col-sm-3 col-form-label">有効期間終了</label>
                <div class="form-group col-sm-7">
                    <input id="C121-01-15" type="date" class="form-control" v-model="valid_date_end_str" min="1899-12-30"/>
                </div>
                <div class="col-sm-1"></div>
            </div>
      　    <div class="message-group row ml-1 mr-1 pt-3">
                <div id="C121-01-16" class="error-message text-center col-sm-12">
                    <div v-for="(item, i) in errorMessageArray" :key="i">{{item}}</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C121-01-17" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-on:click="registClick">登録</button>
                <button id="C121-01-18" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['op1', 'id', 'itemOption'],
    data() {
        return {
            information:[],
            informationId: 0,
            targetInformationTypeID: 0,
            targetOfficeID: 0,
            information_subject_name: "",
            information_contants: "",
            valid_date_start_str: null,
            valid_date_end_str: null,
            errorMessage: null,
            errorMessageArray: [],
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn = "OK"; //ボタンが押された時の処理をここに記載
                            //M112モーダルを閉じる
                            this.op1.callback_regist();
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            isError: false,
        }
    },
    methods: {
        // 登録処理
        registClick() {
            if(this.validate()){
                axios.post('editInformation', {
                    params:{
                        'information_id': this.informationId,
                        'information_type_id': this.targetInformationTypeID,
                        'office_id': this.targetOfficeID,
                        'information_subject_name': this.information_subject_name,
                        'information_contants': this.information_contants,
                        'valid_date_start': this.dateStrToSerial(this.valid_date_start_str) === null ? 0 : this.dateStrToSerial(this.valid_date_start_str),
                        'valid_date_end': this.dateStrToSerial(this.valid_date_end_str) === null ? 2958465 : this.dateStrToSerial(this.valid_date_end_str),
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.modalOption.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOption);
                        //モーダルを閉じる
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#' + this.id).modal('hide');
                    }
                    else
                    {
                        this.modalOption.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOption);
                    }
                });
            }
        },
        //入力チェック
        validate(){
            this.isError = false;
            this.errorMessageArray = [];

            //種別
            if(this.targetInformationTypeID === '0' || this.targetInformationTypeID === 0)
            {
                this.errorMessageArray.push("種別を選択してください");
                this.isError = true;
            }
            //事務所
            if(this.targetOfficeID === null)
            {
                this.errorMessageArray.push("事務所を選択してください");
                this.isError = true;
            }
            //件名
            if(this.information_subject_name === '')
            {
                this.errorMessageArray.push("件名を入力してください");
                this.isError = true;
            }else if(this.information_subject_name.length > 100)
            {
                this.errorMessageArray.push("件名は100文字以下としてください");
                this.isError = true;
            }
            //内容
            if(this.information_contants === '')
            {
                this.errorMessageArray.push("内容を入力してください");
                this.isError = true;
            }
            //有効年月日
            if(this.dateStrToSerial(this.valid_date_start_str) !== null && this.dateStrToSerial(this.valid_date_end_str) !== null && (this.dateStrToSerial(this.valid_date_start_str) > this.dateStrToSerial(this.valid_date_end_str)))
            {
                this.errorMessageArray.push("有効期間終了は有効期間開始以降を指定してください");
                this.isError = true;
            }
            return !this.isError;
        },
        //P114-02 キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
    },
    mounted(){
        
        if(this.op1.information !== null)
        {
            this.information = this.op1.information;

            this.informationId = this.information.information_id;
            this.targetInformationTypeID = this.information.information_type_id;
            this.targetOfficeID = this.information.office_id;
            this.information_subject_name = this.information.information_subject_name;
            this.information_contants = this.information.information_contants;
            this.valid_date_start_str = this.serialToDateStr(this.information.valid_date_start, "YYYY-MM-DD");
            this.valid_date_end_str = this.serialToDateStr(this.information.valid_date_end, "YYYY-MM-DD");
        }
        else
        {
            //新規
            //nothing to do 
        }
    },
    computed:{
        informationTypeList: function(){
            return this.getMasterData().information_type;
        },
        officeList: function(){
            return this.getMasterData().office;
        },
    },
    watch: {

    },
};
</script>
