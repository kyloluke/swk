<template>
    <div id="C013-01" class="container-fluid p-3 h-100 w-100">
        <div id="C013-01-01" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="col-12">
                    <button id="C013-01-01-01" class="btn btn-primary" style="font-size:15pt;width:150pt" :employee_id="employeeID" v-on:click="openDailyReportModal()">日報登録</button>
                </div>
            </div>
        </div>
        <div id="C013-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="col-12">
                    <button id="C013-01-02-01" class="btn btn-primary" style="font-size:15pt;width:150pt" v-on:click="onClickChangeTerm()" v-html="buttonCaptionSelectTerm"></button>
                    <div id="C013-01-02-02" class="d-inline-block" style="color:#000000;font-size:15pt; margin-left: 20px; vertical-align: middle;" v-html="targetTerm"></div>
                </div>
            </div>
        </div>
        <div id="C013-01-03" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="col-12">
                    <button id="C013-01-03-01" class="btn btn-primary" style="font-size:15pt;width:150pt" v-bind:disabled="!isSelectedTerm" v-on:click="showList()">一覧表示</button>
                    <button class="btn btn-primary" v-on:click="downloadReport('#C013-01-03-02')" v-bind:disabled="!isSelectedTerm">CSV出力 <i class="fas fa-download fa-2x"></i></button>
                    <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                        <button id="C013-01-03-02" style="display: none;" v-on:click="getReport('C013-01-03-02')"></button>
                    </VueJsonToCsv>
                    <div id="C013-01-03-03" class="d-inline-block" style="color:#000000;font-size:15pt; margin-left: 20px; vertical-align: middle;">一覧表示もしくはCSV出力を選択してください</div>
                    <button id="C013-01-03-04" class="btn btn-primary float-right" style="font-size:15pt;width:150pt" v-bind:disabled="!isSelectedTerm" v-on:click="clearList">表示クリア</button>
                </div>
            </div>
        </div>
        <div id="C013-01-04" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;" v-if="isViewList">
            <div class="row">
                <div class="col-12 p-2">
                    <div class="text-center" style="background-color:#ffffff;font-size:12pt;color:#000000;" v-html="targetTerm"></div>
                    <dailyreportlist_board :employee_id="employeeID" :startDateSerial="startDateSerial" :endDateSerial="endDateSerial" :isManager="false"></dailyreportlist_board>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import VueJsonToCsv from 'vue-json-to-csv'
export default {
    components: {
        VueJsonToCsv,
    },
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持＆子へ渡す用
            yearMonth: 0, //ここでの値保持＆子へ渡す用
            isSelectedTerm: false,
            isViewList: false,
            isList: false,
            startDateSerial: -1,
            endDateSerial: -1,
            modalOption: {
                select_period_type: 1,
                callback_select: (startdate, enddate)=>{this.callback_select(startdate, enddate);},
                callback_cancel: ()=>{this.callback_cancel();},
                selectedStartDateSerial: -1,
                selectedEndDateSerial: -1,
            },
            modalOption_m112: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            recievedData: [],
            csvBuff: [],
            csvFileName: '',
            labelBuff: {},
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.startDateSerial = -1;
            this.endDateSerial = -1;
            this.isSelectedTerm = false;
            this.isViewList = false;
        },
        //期間変更
        onClickChangeTerm()
        {
            //選択済みの日付がある場合、反映、ない場合、当日の日付
            this.modalOption.selectedStartDateSerial = 0 < this.startDateSerial ? this.startDateSerial : this.todaySerial();
            this.modalOption.selectedEndDateSerial = 0 < this.endDateSerial ? this.endDateSerial : this.todaySerial();

            //モーダルを開く
            this.openModal("m113_select_period", "", this.modalOption);
        },
        //表示クリア
        clearList()
        {
            this.initialize();
        },
        //リスト表示
        showList()
        {
            this.isViewList = true;
        },
        //日付が選択されたときのコールバック
        callback_select(startdate, enddate){
            this.startDateSerial = startdate;
            this.endDateSerial = enddate;
            this.isSelectedTerm = true;
        },
        //モーダルにてキャンセルされたときのコールバック
        callback_cancel(){
            
        },
        openDailyReportModal(){
            const option = {
                employee_id: this.employeeID,
                session_data: this.session_data,
                date: this.todaySerial(),
                }
            //cleanしてから開く
            this.cleanModal();
            this.openModal('m106_daily_report','modal-xl', option);
        },
        //ダウンロード処理
        downloadReport(buttonId){
            this.csvBuff= [];
            this.labelBuff= {};
            var downloadAxiosName = '';

            if(buttonId == '#C013-01-03-02'){
                this.downloadPeriod = this.serialToDateStr(this.startDateSerial,'YYYYMMDD') + "_to_" + this.serialToDateStr(this.endDateSerial,'YYYYMMDD');
                this.csvFileName = "日報_" + this.downloadPeriod;
                downloadAxiosName = '/dailyReportList';
            }else{
                //
            }

            axios.get(downloadAxiosName, {
                params:{
                    'firstday_serial' : this.startDateSerial,
                    'lastday_serial' : this.endDateSerial,
                    'employeeID' : this.employeeID,
                }
            }).then(response => {
                if(response.data.result)
                {
                    if(!$.isEmptyObject(response.data.t017_daily_report_info)){
                        //受信バッファへ保持
                        this.recievedData  = response.data.t017_daily_report_info;
                        $(buttonId).click();
                    }else{
                        this.modalOption_m112.message = "ダウンロードする日報はありません";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                }
                else
                {
                    this.modalOption_m112.message = "ダウンロード失敗";
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
            });  
        },
        getReport(buttonId){
            if(buttonId == 'C013-01-03-02'){
                this.labelBuff.year_month = {title: "対象年月"};
                this.labelBuff.date = {title: "対象日"};
                this.labelBuff.year_month_date = {title: "対象年月日"};
                this.labelBuff.work_no = {title: "明細NO"};
                this.labelBuff.work_time_start_hh = {title: "作業開始時刻(時)"};
                this.labelBuff.work_time_start_mm = {title: "作業開始時刻(分)"};
                this.labelBuff.work_time_end_hh = {title: "作業終了時刻(時)"};
                this.labelBuff.work_time_end_mm = {title: "作業終了時刻(分)"};
                this.labelBuff.work_time = {title: "作業時間"};
                this.labelBuff.work_item_name = {title: "テーマ"};
                this.labelBuff.work_content = {title: "作業内容"};
                //データ
                this.recievedData.forEach((data) => {
                    this.csvBuff.push({
                        year_month: this.serialToDateStr(data.work_date, 'YYYYMM'),
                        date: this.serialToDateStr(data.work_date, 'DD'),
                        year_month_date: this.serialToDateStr(data.work_date, 'YYYYMMDD'),
                        work_no: data.work_no,
                        work_time_start_hh: this.serialToTimeStr(data.work_time_start, false).slice(-5, -3),
                        work_time_start_mm: this.serialToTimeStr(data.work_time_start, false).slice(-2),
                        work_time_end_hh: this.serialToTimeStr(data.work_time_end, false).slice(-5, -3),
                        work_time_end_mm: this.serialToTimeStr(data.work_time_end, false).slice(-2),
                        work_time: Number(data.work_time_end)-Number(data.work_time_start),
                        work_item_name: data.work_item_name,
                        work_content: data.work_content,
                    });     
                });
            }
        },
    },
    mounted(){
    },
    computed:{
        buttonCaptionSelectTerm: function() {
            return this.isSelectedTerm ? "表示期間変更" : "表示期間指定"
        },
        targetTerm: function(){
            if(this.startDateSerial < 0 || this.endDateSerial < 0)
            {
                //デフォルト値を表示
                return "表示する期間を選択してください";
            }
            else
            {
                //シリアル値を日付に変換して表示
                return "表示期間：" + this.serialToDateStr(this.startDateSerial, "YYYY/MM/DD(A)") + " ～ " + this.serialToDateStr(this.endDateSerial, "YYYY/MM/DD(A)");
            }
        },
    },
    watch: {
        employee_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.employeeID = value;
                }
            }
        }
    }
}
</script>