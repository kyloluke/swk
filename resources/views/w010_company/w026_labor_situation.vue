<template>
    <div id="C026-01" class="container-fluid p-3 h-100 w-100">
        <laborManagementSearch_board :employee_id="employeeID" :is_selected_single="isSelectedSingle" :is_enable_select_office="isEnableSelectOffice" :office_id="officeId" @selectedList="selectedList"></laborManagementSearch_board>
        <div v-if="isSelectedList">
            <laborManagementList_board :list_id="listId" :target_data_info="targetDataInfo" :is_selected_target="isSelectedTarget" @selectTarget="selectTarget"></laborManagementList_board>
        </div>
        <div v-if="isSelectedTarget">
            <div id="C026-01-03" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
                <div class="row">
                    <div class="px-2">
                        <div id="C026-01-03-01" class="text-left">
                            対象者選択
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="px-3 col-1 text-left"></div>
                    <div class="px-3 col-9 text-left">
                        <div id="C026-01-03-04" style="font-size:15pt;color:black;line-height: 46px;">{{TextSelectTarget}}</div>
                    </div>
                    <div class="px-3 col-2 text-center">
                        <button id="C026-01-03-05" style="font-size:11pt;width:100pt" class="btn btn-primary" v-bind:disabled="!isSelectedTarget" v-on:click="exitInput()">対象者を閉じる</button>
                    </div>
                </div>
            </div>
            <div v-if="data_message != ''">
                <div id="C017-01-04" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
                    <div class="row">
                        <div class="px-3 col-12 text-center">
                            <div style="font-size:15pt;color:black;line-height: 46px;" v-html="data_message"></div>
                        </div>
                    </div>
                </div>
            </div>
            <laborSituation_board :employee_id="selectedEmployeeID" :year_month="selectedDate" @messageChange="dataMessage" :session_data="session_data"></laborSituation_board>
        </div>
    </div>
</template>
<script>
import vuejsDatepicker from 'vuejs-datepicker'
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    components: {
        vuejsDatepicker
    },
    data() {
        return {
            isSelectedTarget: false,
            isSelectedList: false,
            yearMonth: 0, //ここでの値保持＆子へ渡す用
            selectedEmployee: [],
            SelectedEmployeeIdList: [],
            selectedDate: 0,
            selectedEmployeeID: 0,
            listId: 1,
            isSelectedSingle: 2,
            data_message: '',
            is_searchable: false,
            isEnableSelectOffice: true,
            officeId: 0,
        };
    },
    methods:{
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false;
            this.isSelectedList = false;
        },
        selectTarget(data){
            this.isSelectedTarget = true;
            this.selectedEmployeeID = data.targetEmployeeID;
            this.TextSelectTarget = data.selected_employee_message;
        },
        selectedList(data){
            this.isSelectedList = true;
            this.targetDataInfo = data.target_data_info;
            this.selectedDate = data.yearMonth;
            this.listId = data.listId;
        },
        dataMessage(data_message) {
            this.data_message = data_message;
        },

        exitInput()
        {
            this.isSelectedTarget = false;
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
                }
            }
        },
    }
}
</script>