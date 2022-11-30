<template>
    <div id="C028-01" class="container-fluid p-3 h-100 w-100">
        <loading :active.sync="isLoading" :can-cancel="false" :on-cancel="onCancel" :is-full-page="fullPage"></loading> 
        <div id="C028-01-01" class="container-fluid p-4 h-100 w-100 shadow-sm board">
            <div class="row">
                <div class="px-2">
                    <div id="C028-01-01-01" class="text-left">
                        全社締め処理
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="p-2 col-12 text-left">
                    <div id="C028-01-01-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div id="C028-01-01-02-001" style="color:#000000;margin-top:5pt;display:inline-block">
                                締め年月
                            </div>
                            <div id="C028-01-01-02-002" class="text-center" style="color:#000000;display:inline-block">
                                <select class="form-control" v-model="targetYearMonth" v-on:change="onChangeCloseClass">
                                    <option v-for="option in closeYearMonthOptions" :key="option.value" v-bind:value="option.value">
                                        {{option.text}}
                                    </option>
                                </select>
                            </div>
                            <div id="C028-01-01-02-003" style="color:#000000;margin-top:5pt;margin-left:10pt;display:inline-block">
                                締め区分
                            </div>
                            <div id="C028-01-01-02-004" class="text-center" style="color:#000000;display:inline-block">
                                <select class="form-control" v-model="closeDateId" v-on:change="onChangeCloseClass">
                                    <option value="1">月末締め</option>
                                    <option value="2">15日締め</option>
                                </select>
                            </div>
                            <button id="C028-01-01-02-009" class="btn btn-danger float-right ml-5" v-on:click="openModal('m112_common_message', '', modalOptionForced)" v-bind:disabled="!isEnableForcedClose">強制締め実行</button>
                            <button id="C028-01-01-02-008" class="btn float-right ml-5" :class="changeButtonTypeClass" v-on:click="onClickClose(false)" :disabled="!isEnableClose">{{closeButtonName}}</button>
                            <button id="C028-01-01-02-007" class="btn btn-primary float-right" v-on:click="onClickCheck">事業所締め状態確認</button>
                            <div style="margin-left:200pt">
                                <div id="C028-01-01-02-005" class="card-text" style="font-size:8pt;display:inline-block">対象期間：</div>
                                <div id="C028-01-01-02-006" class="card-text" style="font-size:8pt;display:inline-block">{{targetDateTermStart}}～{{targetDateTermEnd}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="isSelectedTarget">
                <div class="row">
                    <div class="p-2 col-12 text-center">
                        <div id="C028-01-01-03" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-body" v-if="office_closing_status.length">
                                <div id="C028-01-01-03-001" class="card-title text-left">締め状況一覧</div>
                                <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                                    <thead>
                                        <tr>
                                            <th id="C028-01-01-03-002">事業所番号</th>
                                            <th id="C028-01-01-03-003">事業所名</th>
                                            <th id="C028-01-01-03-004">締め状態</th>
                                            <th id="C028-01-01-03-005">締め解除</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in office_closing_status" v-bind:key="item.office_id">
                                            <td>{{item.office_id}}</td>
                                            <td>{{item.office_name}}</td>
                                            <td>{{item.closing_status_class}}</td>
                                            <td>
                                                <button v-if="item.isEnableCloseCancel" id="C028-01-01-03-007" class="btn btn-danger" v-on:click="onClickCloseCancel(item)">締め解除</button>
                                                <button v-if="!item.isEnableCloseCancel" id="C028-01-01-03-007" class="btn btn-secondary" disabled>締め解除</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="!office_closing_status.length" class="text-center" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">対象の事業所がありません</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    components: {
        "loading":Loading
    },
    data() {
        return {
            isSelectedTarget: false,
            closeDateId: 1, //月末締め
            officeId: 0, //総務画面からは全事業所対象のため0
            office_closing_status: [],
            isEnableClose: false,
            targetYearMonth: 0,
            modalOptionError: {
                message: '',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            this.isEnableForcedClose = true;
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOptionSuccess: {
                message: '',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOptionForced: {
                message: '強制締めを実施しますか？',
                buttons:[{
                        exec : ()=>{
                            this.btn="はい"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            this.onClickClose(true);
                        },
                        caption : "はい",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-primary"
                    },
                    {
                        exec : ()=>{
                            this.btn="いいえ"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "いいえ",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-danger"
                    }],
            },
            isEnableCloseCancel: [],
            isEnableForcedClose: false,
            is_forced_close: false,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
        };
    },
    mounted(){
        //初期選択
        this.targetYearMonth = this.lastYearMonth;
        //対象期間取得
        this.getCompanyClosingStatus();
    },
    methods:{
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false; //　要実装　一覧を閉じる？
        },
        exitInput()
        {
            this.initialize();
            this.isSelectedTarget = false;
        },
        onClickCheck(){
            this.isSelectedTarget = true;
            this.getOfficeClosingStatus();
            this.getCompanyClosingStatus();
        },
        getCompanyClosingStatus(){
            axios.get('getCompanyClosingStatus', {
                //年月を6桁で送信
                params:{
                    'targetDate' : this.targetYearMonth,
                    'closeDateId' : this.closeDateId,
                    'companyId' : this.session_data.company_id,
                }
            }).then(response => {
                //全社締め済み、取得失敗の場合は締めボタン押せない
                this.isEnableClose = response.data.result && response.data.values.closing_status_class <= 4;
            })
        },
       getOfficeClosingStatus(){
            axios.get('getOfficeClosingStatus', {
                //年月を6桁で送信
                params:{
                    'targetDate' : this.targetYearMonth,
                    'closeDateId' : this.closeDateId,
                    'officeId' : this.officeId,
                }
            }).then(response => {
                this.office_closing_status = [];
                if(response.data.result)
                {
                    for(let i = 0; i < response.data.values.length; i++)
                    {
                        this.isEnableCloseCancel = [];
                        if((response.data.values[i].closing_status_class == 4) && (this.isEnableClose)){
                            this.isEnableCloseCancel[i] = true;
                        }
                        this.office_closing_status.push({
                            'office_id': response.data.values[i].office_id,
                            'office_name': response.data.values[i].office_name,
                            'closing_status_class': this.session_data.master_data.close_state[response.data.values[i].closing_status_class-1].close_state_name,
                            'isEnableCloseCancel': this.isEnableCloseCancel[i],
                        });
                    }
                }
                else
                {
                    //取得失敗
                }
            })
        },
        onChangeCloseClass(){
            this.isSelectedTarget = false;
            this.getCompanyClosingStatus();
        },
        onClickClose(is_forced_close){
            this.isLoading = true;
            axios.get('updateCompanyClosingStatus', {
                //年月を6桁で送信
                params:{
                    'targetDate' : this.targetYearMonth,
                    'closeDateId' : this.closeDateId,
                    'companyId' : this.session_data.company_id,
                    'is_forced_close' : is_forced_close,
                }
            }).then(response => {
                if(response.data.result)
                {
                    //全社締め済みの場合は締めボタン、事業所締め解除ボタン押せない
                    this.isEnableClose = false;
                    for(let i = 0; i < this.office_closing_status.length; i++){
                        this.office_closing_status[i].isEnableCloseCancel = false;
                        this.office_closing_status[i].closing_status_class = this.session_data.master_data.close_state[response.data.values.closing_status_class-1].close_state_name;
                    }
                    this.isEnableForcedClose = false;
                    this.modalOptionSuccess.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOptionSuccess);
                }
                else
                {
                    //取得失敗
                    this.isEnableClose = true;
                    this.modalOptionError.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOptionError);
                }
                //ローディング画面隠す
                this.isLoading = false;
            })
        },
        onClickCloseCancel(item){
            this.isLoading = true;
            axios.get('updateOfficeClosingStatus', {
                //年月を6桁で送信
                params:{
                    'targetDate' : this.targetYearMonth,
                    'closeDateId' : this.closeDateId,
                    'officeId' : item.office_id,
                    'companyId' : this.session_data.company_id,
                    'is_forced_close' : false,
                    'toState' : 1,  //初期状態
                }
            }).then(response => {
                if(response.data.result)
                {
                    item.isEnableCloseCancel = false;
                    this.getOfficeClosingStatus();
                }
                else
                {
                    //取得失敗
                }
                //ローディング画面隠す
                this.isLoading = false;
            })
        },
        onCancel() {
            //Loading画面のキャンセル
        },
    },
    watch: {
    },
    computed:{
        changeButtonTypeClass(){
            return this.isEnableClose ? "btn-danger" : "btn-secondary";
        },
        closeButtonName(){
            return this.isEnableClose ? "全社締め実行" : "全社締め済み";
        },
        thisYearMonth(){
            return this.serialToDateStr(this.todaySerial(), "YYYYMM");
        },
        lastYearMonth(){
            return this.calcYearMonth(this.serialToDateStr(this.todaySerial(), "YYYYMM"), -1);
        },
        thisYearMonthStr(){
            return this.serialToDateStr(this.todaySerial(), "YYYY年MM月");
        },
        lastYearMonthStr(){
            return this.calcYearMonth(this.serialToDateStr(this.todaySerial(), "YYYYMM"), -1, "YYYY年MM月");
        },
        closeYearMonthOptions(){
            return [
                {value: this.thisYearMonth, text: this.thisYearMonthStr},
                {value: this.lastYearMonth, text: this.lastYearMonthStr},
            ];
        },
        targetDateTermStart(){
            return this.targetYearMonth == 0 ? 0 : this.serialToDateStr(this.getCloseDateTerm(this.targetYearMonth, this.closeDateId)?.target_term_start, "YYYY/MM/DD");
        },
        targetDateTermEnd(){
            return this.targetYearMonth == 0 ? 0 : this.serialToDateStr(this.getCloseDateTerm(this.targetYearMonth, this.closeDateId)?.target_term_end, "YYYY/MM/DD");
        }
    },
}
</script>
