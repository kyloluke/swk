<template>
<div id="C033-01" class="container-fluid p-3 h-100 w-100" style="">
      <!-- <div id="C033-01" class="container-fluid h-100" style="min-height:600pt"> -->
            <!-- Nav pills -->
            <div id="C107-01-10" class="message-group row" v-if="validateMessage !== null">
                <div class="col-sm-2"></div>
                <div class="error-message text-center col-sm-8">
                    <div>{{ validateMessage }}</div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <div v-if="!session_data.is_production" class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="resetPasswordAll()">パスワード一斉初期化</button>
                    <VueJsonToCsv :json-data="csvData" :csv-title="csvFileName" :labels="labelBuff">
                        <button id="doDownload" style="display: none;" v-on:click="download()"></button>
                    </VueJsonToCsv>
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="resetPasswordUser()">パスワード初期化</button>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">社員コード<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="employeeCodeInput" class="form-control" placeholder="社員コード">
                </div>
                <div class="col-sm-1 text-center" style="width:100pt;">
                    <label class="modal-form-label">パスワード<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="employeePassword" class="form-control" placeholder="パスワードを指定">
                </div>
            </div>
            <div v-if="!session_data.is_production" class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="createAttendanceTable()">勤務テーブル作成</button>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">対象年月</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <div class="text-center" style="color:#000000;display:inline-block">
                        <vuejsDatepicker :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" class="dp-form-control" v-model="targetYearMonth"></vuejsDatepicker>
                    </div>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <select class="form-control" v-model="selectedCloseDateID">
                        <option value=1>月末締め</option>
                        <option value=2>15日締め</option>
                    </select>
                </div>
            </div>
            <div v-if="!session_data.is_production" class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="createAttendanceTableByEmployeeCode()">勤務テーブル作成　（社員コード指定）</button>
                </div>
                <div class="col-sm-1 text-center" style="width:100pt;">
                    <label class="modal-form-label">社員コード<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="catByEIDEmployeeCode" class="form-control" placeholder="社員コード">
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">対象年月</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <div class="text-center" style="color:#000000;display:inline-block">
                        <vuejsDatepicker :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" class="dp-form-control" v-model="catByEIDtargetYearMonth"></vuejsDatepicker>
                    </div>
                </div>
            </div>

            <div v-if="isLoading" style="text-align: center;">
                <button style="font-size:11pt;width:130pt" class="btn btn-danger" v-on:click="cancelCreateAttendanceTable">キャンセル</button>
            </div>
            <div class="loader" v-if="isLoading">Loading...</div>
        </div>
</template>
<script>
import vuejsDatepicker from 'vuejs-datepicker'
import VueJsonToCsv from 'vue-json-to-csv'

export default {
   components: {
        VueJsonToCsv,
        vuejsDatepicker,
    },
    props: {
        session_data: Object, //Sessionから取得した社員情報・マスタ情報
    },
    data() {
        return {
            isLoading: false,
            employeeIdInput: '',
            employeeCodeInput: '',
            employeePassword: '',
            targetYearMonth: new Date(),
            selectedCloseDateID: '',
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //M107モーダルを閉じる
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            csvData: [],
            recieved: [],
            labelBuff: {
                employee_id: {title: 'employee_id'},
                employee_code: {title: 'employee_code'},
                password: {title: 'password'}
            },
            csvFileName: "password.csv",
            datePickerFormat: 'yyyy/MM',
            datePickerLanguage:{
                language: 'Japanese', 
                months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                monthsAbbr: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                days: ['日', '月', '火', '水', '木', '金', '土'],
                rtl: false, 
                ymd: false,
                yearSuffix: '年'
            },

            defaultDate: new Date(),
            jobStateID: 0,
            catByEIDEmployeeCode: '',
            catByEIDtargetYearMonth: new Date(),
            aggregateEmployeeId: '',
            aggregateTargetYearMonth: new Date(),
            validateMessage: '',
            isContinue: false,
        };
    },
    methods: {
        initialize() {
            this.validateMessage = null;
        },
        resetPasswordAll() {
            this.isLoading = true;
            this.validateMessage = null;
            
            axios.post('/reset_password_all', {
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOption.message = 'パスワードの初期化開始しました';
                    this.openModal("m112_common_message", "", this.modalOption);
                    this.jobStateID = response.data.values.job_state_id;
                    this.startCheckJobState(response.data.values.job_state_id, 1, 1);
                }
                else
                {
                    this.modalOption.message = 'パスワードの初期化失敗しました';
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
                console.log(error.response.data);
            });
        },

        resetPasswordUser() {
            //入力値のバリデーション
            this.validateMessage = null;
            if(!this.employeeCodeInput){
                this.validateMessage = '社員コードを入力してください';
            }

            //未入力の場合は自動初期化してもらう
            if(this.employeePassword.length > 0) {
                if((this.employeePassword.length > 12) || (this.employeePassword.length < 4)){
                    //E107-01-005
                    this.validateMessage = 'パスワードは4～12文字で入力してください';
                }else if(!this.employeePassword.match(/^[A-Za-z0-9]*$/)){
                    //E107-01-006
                    this.validateMessage = 'パスワードは半角英数のみ使用可能です';
                }
            }
            
            //送信ボタン本来の動作をキャンセル     
            if(this.validateMessage !== null){
                return;
            }

            axios.post('/reset_password_user', {
                employeeCode: this.employeeCodeInput,
                password: this.employeePassword
            })
            .then(response => {
                if(response.data.result)
                {
                    //モーダルを開く
                    let displayMessage = '社員コード: ' + response.data.values.message[0].employee_code + ' パスワード [' +  response.data.values.message[0].password + ']で登録しました。';
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                } 
                else 
                {
                    //モーダルを開く
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
            });
        },

        download(){
            //結果をcsvでダウンロード
            //データ
            this.recieved.forEach((data) => {
                this.csvData.push({
                    employee_id: data.employee_id,
                    employee_code: data.employee_code,
                    password: data.password,
                });
            });
            //データ
            //this.csvBuff = this.recieved;
        },
        //勤務表作成実行
        createAttendanceTable() {
            this.validateMessage = null;
            if(!this.selectedCloseDateID) {
                this.validateMessage = '締め期間を選択してください';
            }

            if(this.validateMessage !== null) {
                return;
            }

            this.isLoading = true;
            axios.post('/create_attendance_table', {
                closeDateID: this.selectedCloseDateID,
                targetYearMonth: this.serialToDateStr(this.dateStrToSerial(this.targetYearMonth), "YYYYMM"),
            }).then(response => {
                if(response.data.result)
                {
                    let displayMessage = '処理を開始しました'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                    const jobStateID = response.data.values.job_state_id;
                    this.jobStateID = jobStateID;
                    this.startCheckJobState(jobStateID);
                }
                else
                {
                    alert("エラー：" + JSON.stringify(response.data.values));
                    this.isLoading = false;
                }
            })
            .catch(error => {
                //何らかのエラー
                console.log(error.response.data);
                this.isLoading = false;
                this.jobStateID = 0;
            });
        },
        createAttendanceTableByEmployeeCode(){
            this.validateMessage = null;
            if(!this.catByEIDEmployeeCode) {
                //送信ボタン本来の動作をキャンセル     
                this.validateMessage = '社員コードを入力してください';
            }

             if(this.validateMessage !== null){
                return;
            }

            this.isLoading = true;
            axios.post('/create_attendance_table_by_code', {
                targetYearMonth: this.serialToDateStr(this.dateStrToSerial(this.catByEIDtargetYearMonth), "YYYYMM"),
                employeeCode: this.catByEIDEmployeeCode,
            }).then(response => {
                if(response.data.result)
                {
                    axios.get('/update_violation_warning_id', {
                        params:{
                            attendanceYearMonth: this.serialToDateStr(this.dateStrToSerial(this.catByEIDtargetYearMonth), "YYYYMM"),
                            employeeID: response.data.values.employee_id,
                        }
                    }).then(response =>{
                        this.modalOption.message = "処理が終了しました";
                        this.openModal("m112_common_message", "", this.modalOption);
                    }).catch(error => {
                        //何らかのエラー
                        console.log(error.response.data);
                        this.isLoading = false;
                        this.jobStateID = 0;
                    });
                }
                else
                {
                    this.modalOption.message = response.data.values.val;
                    this.openModal("m112_common_message", "", this.modalOption);
    
                }
                this.isLoading = false;
            })
            .catch(error => {
                //何らかのエラー
                console.log(error.response.data);
                this.isLoading = false;
                this.jobStateID = 0;
            });
        },
        //ジョブの状態確認開始（タイムアウトないので注意）
        //@para1 job_state_id
        //@para2 状態確認できた後、ダウンロード実行させる場合は「１」を渡してください
        //@para3 ダウンロードの種類を示す
        async startCheckJobState(jobStateID, csvDownload, type){
            this.isContinue = true;
            let message = "";
            csvDownload = csvDownload !== undefined ? csvDownload : 0;
            type = type !== undefined ? type : 0
            while(this.isContinue)
            {
                await this.sleep(3000);
                let data = await this.getJobState(jobStateID, csvDownload, type);
                if(!data.result) {
                    console.log(data.values.message)
                    this.isContinue = false;
                    return;
                }
                switch(data.values.state)
                {
                    case 0:
                    case 1:
                        //継続中
                        break;
                    case 2:
                        //終了
                        this.isContinue = false;
                        message = "処理が終了しました";
                        //受信バッファへ保持
                        if(csvDownload == 1) {
                            // 現在はパスワード一斉初期化機能のみです
                            this.recieved  = data.values.data;
                            $('#doDownload').click();
                        }
                        
                        break;
                    case 9:
                        //異常終了
                        this.isContinue = false;
                        message = "処理が異常終了しました";
                        break;
                }
            }
            if(message != '') {
                this.modalOption.message = message;
                this.openModal("m112_common_message", "", this.modalOption);
            }
          
            this.isLoading = false;
            this.jobStateID = 0;
        },
        //定周期で呼ばれる、ジョブの状態確認
        async getJobState(jobStateID, csvDownload, type)
        {
            return new Promise((resolve, reject) =>{
                axios.get('/getJobState',{
                    params:{
                        'jobStateID' : jobStateID,
                        'csvDownload' : csvDownload,
                        'type': type
                    }
                }).then(response => {
                    resolve(response.data);
                }).catch(err => {
                    reject(err);
                });
            });
        },
        //キャンセル実行
        cancelCreateAttendanceTable(){
            axios.post('/cancelJob', {
                jobStateID : this.jobStateID,
            }).then(response => {
                // ステータスリクエストのループをストップする
                this.isContinue = false;
                if(response.data.result)
                {
                    let displayMessage = '処理を中断しました'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                else
                {
                    let displayMessage = '処理の中断に失敗しました'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
            });
        },
    },
    computed:{

    },
    watch: {

    },
    mounted() {
        this.employeeIdInput = '',
        this.employeeCodeInput = '',
        this.employeePassword = ''
    }
}
</script>

<style>
    .ml-5, .mx-5 {
        margin-left: 0 !important;
    }

    .mt-5, .my-5 {
        margin-top: 1.4rem !important;
    }

    #C033-01 {
        padding-left: 0px !important;
    }
</style>
