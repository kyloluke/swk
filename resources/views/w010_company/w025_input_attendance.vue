
<template>
    <div id="C025-01" class="container-fluid p-3 h-100 w-100">

        <div id="C025-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C025-01-01-01" class="text-left">
                        対象者選択
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="px-3 col-2 text-left">
                    <button id="C025-01-01-02" style="font-size:11pt;width:100pt" class="btn btn-primary" v-bind:disabled="isSelectedTarget" v-on:click="selectTarget()">対象者検索</button>
                </div>
                <div class="px-3 col-8 text-left">
                    <div id="C025-01-01-04" style="font-size:15pt;color:black;" v-html="TextSelectTarget"></div>
                </div>
                <div class="px-3 col-2 text-center">
                    <button id="C025-01-01-05" style="font-size:11pt;width:100pt" class="btn btn-primary" v-bind:disabled="!isSelectedTarget" v-on:click="exitInput()">入力終了</button>
                </div>
            </div>
        </div>
        <div v-if="isSelectedTarget">
            <inputAttendance_board :employee_id="targetEmployeeID" :is_manager="false" :year_month="yearMonth" :background_type="backgroundType" :information_attendance_mode="0" :session_data="session_data"></inputAttendance_board>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持
            yearMonth: Number(this.serialToDateStr(this.todaySerial(), "YYYYMM")), //ここでの値保持
            targetEmployeeID: 0,
            isSelectedTarget: false,
            TextSelectTarget: "対象者を選択してください",
            modalOption: {
                select_period_type:  false, //true:複数選択、false:択一選択
                callback_select: (employee_id,employee_code,employee_name,post_name,dept_name)=>{this.callback_select(employee_id,employee_code,employee_name,post_name,dept_name);},
                callback_cancel: ()=>{this.callback_cancel();},
                isEnableSelectOffice: true,
                employeeID: 1, //ここでの値保持＆子へ渡す用
                closeDateId: 0,
                officeId: 0,
            },
            backgroundType: 3,  //背景色
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false;
            this.TextSelectTarget = "対象者を選択してください";
        },
        selectTarget()
        {
            //モーダルを開く
            this.openModal('m110_search_member','', this.modalOption);
        },
        //社員が選択されたときのコールバック
        callback_select(employee_list){
            this.targetEmployeeID = employee_list.employee_id;
            let close_date = this.getMasterData().close_date[employee_list.employee_close_date_id - 1].close_date;
            let display_switch_date = this.getMasterData().close_date[employee_list.employee_close_date_id - 1].display_switch_date;
            display_switch_date = display_switch_date == 0 ? close_date + 1 : display_switch_date;
            this.yearMonth = this.getDisplaySwitchDate(display_switch_date);
            this.TextSelectTarget = "選択中：　" + employee_list.employee_code +"　　"+ employee_list.employee_name +"　　"+ employee_list.employee_post +"　　"+ employee_list.employee_office+'／'+employee_list.employee_dept;
            this.isSelectedTarget = true;
        },
        //モーダルにてキャンセルされたときのコールバック
        callback_cancel(){
            
        },
        exitInput()
        {
            this.initialize();
        }
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
                    this.employeeID = value;
                }
            }
        }
    }
}
</script>