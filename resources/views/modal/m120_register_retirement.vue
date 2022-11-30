<template>
    <div class="modal-content" id="C120-01">
        <div class="modal-body">
            <div id="C120-01-01" class="d-flex justify-content-center pb-5">退職する日付を入力して下さい</div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C120-01-02" class="col-sm-3 col-form-label">対象者コード</label>
                    <div id="C120-01-03" class="col-sm-4 col-form-label">{{this.op1.targetEmployeeCode}}</div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C120-01-04" class="col-sm-3 col-form-label">対象者名</label>
                    <div id="C120-01-05" class="col-sm-4 col-form-label">{{this.op1.targetEmployeeName}}</div>
                <div class="col-sm-4"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C120-01-06" class="col-sm-3 col-form-label">退職年月日</label>
                    <input id="C120-01-07" class="col-sm-4 form-control" type="date" v-model="retirementDay"/>
                <div class="col-sm-4"></div>
            </div>
      　    <div class="message-group row ml-1 mr-1 pt-3">
                <div id="C120-01-08" class="error-message text-center col-sm-12">
                    <div>{{errorMessage}}</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C120-01-09" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-on:click="registClick">登録</button>
                <button id="C120-01-10" type="button" class="btn btn-danger w-35" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            errorMessage: '',
            retirementDay: null,
            modalOption_m112: {
                message: '登録すると修正、取り消しできませんが登録してよろしいでしょうか。',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            this.setRetirementEmployee();
                        },
                        caption : "はい",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-primary"
                    },
                    {
                        exec : ()=>{
                            this.btn="キャンセル";
                        },
                        caption : "キャンセル",
                        btnclass : "btn-danger"
                    }],
            },
            modalOption_end_m112: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            this.op1.callback_select(this.employee_list);
                        },
                        caption : "はい",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-primary"
                    }],
            },
        }
    },
    methods: {
        registClick() {
            if(this.validate()){
                this.openModal("m112_common_message", "", this.modalOption_m112);
            }
        },
        setRetirementEmployee() {
            axios.get('retirement_employee', {
            params:{
                'targetEmployeeID' : this.op1.targetEmployeeID,
                'retirementDay' : this.dateStrToSerial(this.retirementDay),
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOption_end_m112.message = "退職者設定が完了しました。";
                    this.openModal("m112_common_message", "", this.modalOption_end_m112);
                }
                else
                {
                    this.modalOption_end_m112.message = response.data.message;
                    this.openModal("m112_common_message", "", this.modalOption_end_m112);
                }
            });
        },
        //バリデーション
        validate(){
            //未入力チェック
            if(this.retirementDay === null || this.retirementDay === "")
            {
                this.errorMessage = "退職年月日を指定して下さい";
                return false;
            }
            //正当性チェック
            if(!this.checkDateStr(this.retirementDay))
            {
                this.errorMessage = "正しい日付で申請してください。";
                return false;
            }
            //指定日が入社日より前の場合
            if(this.op1.joinedCompanyDate > this.dateStrToSerial(this.retirementDay))
            {
                this.errorMessage = "退職年月日は入社日以降を指定してください";
                return false;
            }
            //問題なし
            return true;
        },
    },
    mounted(){
        //コールバック用変数
        this.employee_list = {
            'employee_id': this.op1.targetEmployeeID,
            'employee_code': this.op1.targetEmployeeCode,
            'employee_name': this.op1.targetEmployeeName,
            'employee_post': this.op1.targetEmployeePost,
            'employee_office': this.op1.targetEmployeeOffice,
            'employee_dept': this.op1.targetEmployeeDept,
        }
        //退職日初期値
        this.retirementDay = this.serialToDateStr(this.todaySerial(), 'YYYY-MM-DD');
    },
    computed:{
    }
};
</script>
