<template>
    <div>
        <div id="workingStatusHeader" class="container-fluid p-3 h-100 w-100 shadow-sm board">
            <loading :active.sync="isLoading"
                :can-cancel="true"
                :on-cancel="onCancel"
                :is-full-page="fullPage"></loading> 
            <div style="margin-top: 20px;">
                <workingStatus_board :employee_id="employeeID" :year_month="yearMonth" :background_type="backgroundType" :session_data="session_data" :response_data="input_attendance_info" :is_manage="isManager" :updateInputAttendanceBoard="getInputAttendanceList"></workingStatus_board>
            </div>
        </div>
        <div class="container-fluid h-100 w-100 shadow-sm board" style="margin-top:20pt; padding-bottom: 1rem; padding-top: 0.1rem;">
            <div class="container-fluid h-100 w-100" style="margin-top: 10pt">
                <button class="btn btn-primary" style="font-size:15pt; width:100pt; text-align: left; padding-left: 22px;" v-on:click="onClickPrev()">◀　前月</button>
                <span class="text-left" style="color: #000000; font-size: 24px; margin-left: 60px; margin-right: 60px; vertical-align: middle;" v-html="targetYearMonth"></span>
                <button class="btn btn-primary" style="font-size: 15pt; width: 100pt; text-align: right; padding-right: 22px;" v-on:click="onClickNext()">翌月　▶</button>
            </div>
            <div style="margin-top: 15px;">
                <informationattendancelist_board :employee_id="employeeID" :year_month="yearMonth" :is_manager="isManager" :background_type="backgroundType" :session_data="session_data" :is_selected_target="is_selected_target" :updateInputAttendanceBoard="getInputAttendanceList" :input_attendance="input_attendance_info" :information_attendance_mode="informationAttendanceMode"></informationattendancelist_board>
            </div>
            <div style="margin-top: 20px;">
                <aggrigateAttendancelist_board :employee_id="employeeID" :year_month="yearMonth" :background_type="backgroundType" :response_data="input_attendance_info" :is_prev_closed="is_prev_closed"></aggrigateAttendancelist_board>
            </div>
        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "inputAttendance_board",
    components: {
        "loading":Loading
    },
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
        is_manager: Boolean,
        background_type: Number,
        is_selected_target: Boolean,
        information_attendance_mode: Number,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
            isManager: false,
            backgroundType: 1,  //背景色
            input_attendance_info: {},
            input_attendance_info_prev: {},//次月情報
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            is_prev_closed: false,
            informationAttendanceMode: 0,  //画面の状態
        };
    },
    methods: {
        getInputAttendanceList: async function(isForceUpdate)
        {
            return new Promise((resolve, reject) =>{
                //ローディング画面表示
                this.isLoading = true;
                const isNeedForceUpdate = isForceUpdate || !this.input_attendance_info;
                this.getAttendanceInformationMonthly(this.employeeID, this.yearMonth, isNeedForceUpdate).then(value =>{
                    this.input_attendance_info = value;
                    //無理やりネスト（直したい）
                    this.getAttendanceInformationMonthly(this.employeeID, this.calcYearMonth(this.yearMonth, -1, "YYYYMM"), isNeedForceUpdate).then(value =>{
                        this.input_attendance_info_prev = value;
                        //ローディング画面隠す
                        this.isLoading = false;
                        resolve();
                    });
                }).catch(error =>{
                    alert(error);
                    //ローディング画面隠す
                    this.isLoading = false;
                    reject(error);
                });
            });
        },
        onCancel() {
            //Loading画面のキャンセル
        },
        onClickPrev() {
            this.yearMonth = this.calcYearMonth(this.yearMonth, -1);
        },
        onClickNext() {
            this.yearMonth = this.calcYearMonth(this.yearMonth, 1);
        },
    }, 
    computed:{
        targetYearMonth: function(){
            return this.yearMonthNumberToText(this.yearMonth);       
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
                    this.employeeID = Number(value);
                    //yearMonthが初期値の時は処理しない（初回2度読み防止）
                    if(this.yearMonth){
                        this.getInputAttendanceList(false);
                    }
                }
            }
        },
        year_month: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.yearMonth = value;
                    //employeeIDが初期値の時は処理しない（初回2度読み防止）
                    if(this.employeeID){
                        this.getInputAttendanceList(false);
                    }
                }
            }
        },
        background_type: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(value <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.backgroundType = Number(value);

                    //employeeID、yearMonthが初期値の時は処理しない（初回2度読み防止）
                    if(this.employeeID && this.yearMonth){
                        this.getInputAttendanceList(false);
                    }
                }
            }
        },
        is_manager: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isManager = value;
            }
        },
        input_attendance_info_prev:{
            handler(value){
                if(value != null)
                {
                    //前月が全社締めされているかどうか
                    this.is_prev_closed = value.attendance_aggregate?.close_state_id == 5;
                }
            }
        },
        information_attendance_mode: {
            immediate: true,
            handler(value) {
                this.informationAttendanceMode = Number(value);
                    //画面の更新
                    if(this.employeeID && this.yearMonth){
                        this.getInputAttendanceList(false);
                    }
            }
        }
    }
}

</script>