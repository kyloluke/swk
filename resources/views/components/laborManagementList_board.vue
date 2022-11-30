<template>
    <div id="C017-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
        <div id="C017-01-02-01" class="card shadow h-100" style="background-color:#BCD2EE;">
            <div class="card-body">
                <div class="card-title text-left" v-html="listName"></div>
                <div v-if="listId === 1">
                        <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th>選択</th>
                                    <th>社員番号</th>
                                    <th>名前</th>
                                    <th>役職</th>
                                    <th>所属</th>
                                    <th>未申請</th>
                                    <th>未承認</th>
                                    <th>勤怠警告</th>
                                    <th>締め(当月)</th>
                                    <th><select v-model="selected_close_state_lastmonth">
                                        <option value=0 selected>締め(前月)</option>
                                        <option v-for="option in closeStateList" :key="option.close_state_id" v-bind:value="option.close_state_name">{{ option.close_state_name }}</option>
                                    </select></th>
                                </tr>
                            </thead>
                            <tbody v-if="targetDataInfoList.length">
                                <tr v-for="item in targetDataInfoList" v-bind:key="item.employee_id">
                                    <td><button class="btn btn-primary w-100" v-on:click="onClickSelect(item)" v-bind:disabled="isSelectedTarget">選択</button></td>
                                    <td>{{item.employee_code}}</td>
                                    <td>{{item.employee_name}}</td>
                                    <td>{{item.employee_post}}</td>
                                    <td>{{item.employee_office + "／" + item.employee_dept}}</td>
                                    <td>{{item.unapplied_cnt}}件</td>
                                    <td>{{item.unapproved_cnt}}件</td>
                                    <td>{{item.violation_warning}}</td>
                                    <td>{{item.close_state_thismonth}}</td>
                                    <td>{{item.close_state_lastmonth}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <td>対象者がいません</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tbody>
                        </table>
                </div>
                <div v-else-if="listId === 2">
                    <div v-if="targetDataInfo.length">
                        <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th>選択</th>
                                    <th>社員番号</th>
                                    <th>名前</th>
                                    <th>役職</th>
                                    <th>所属</th>
                                    <th>休日出勤日</th>
                                    <th>事由</th>
                                </tr>
                            </thead>
                            <tbody v-if="targetDataInfo.length">
                                <tr v-for="item in targetDataInfo" v-bind:key="item.index">
                                    <td><button class="btn btn-primary w-100" v-on:click="onClickSelect(item)" v-bind:disabled="isSelectedTarget">選択</button></td>
                                    <td>{{item.employee_code}}</td>
                                    <td>{{item.employee_name}}</td>
                                    <td>{{item.employee_post}}</td>
                                    <td>{{item.employee_office + "／" + item.employee_dept}}</td>
                                    <td>{{item.holiday_work_date}}</td>
                                    <td>{{item.holiday_work_reason}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!targetDataInfo.length" class="text-center" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">対象者がいません</div>
                </div>
                <div v-else-if="listId === 3">
                    <div v-if="targetDataInfo.length">
                        <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th rowspan="2">選択</th>
                                    <th rowspan="2">社員番号</th>
                                    <th rowspan="2">名前</th>
                                    <th rowspan="2">役職</th>
                                    <th rowspan="2">所属</th>
                                    <th colspan="6">時間外時間</th>
                                    <th rowspan="2">当月判定</th>
                                    <th rowspan="2">2～6か月平均判定</th>
                                    <th rowspan="2">年間判定</th>
                                    <th rowspan="2">４週４休判定</th>
                                </tr>
                                <tr>
                                    <th>{{this.calcYearMonth(yearMonth,0,'YYYY/MM')}}</th>
                                    <th>{{this.calcYearMonth(yearMonth,-1,'YYYY/MM')}}</th>
                                    <th>{{this.calcYearMonth(yearMonth,-2,'YYYY/MM')}}</th>
                                    <th>{{this.calcYearMonth(yearMonth,-3,'YYYY/MM')}}</th>
                                    <th>{{this.calcYearMonth(yearMonth,-4,'YYYY/MM')}}</th>
                                    <th>{{this.calcYearMonth(yearMonth,-5,'YYYY/MM')}}</th>
                                </tr>
                            </thead>
                            <tbody v-if="targetDataInfo.length">
                                <tr v-for="item in targetDataInfo" v-bind:key="item.index">
                                    <td><button class="btn btn-primary w-100" v-on:click="onClickSelect(item)" v-bind:disabled="isSelectedTarget">選択</button></td>
                                    <td>{{item.employee_code}}</td>
                                    <td>{{item.employee_name}}</td>
                                    <td>{{item.employee_post}}</td>
                                    <td>{{item.employee_office + "／" + item.employee_dept}}</td>
                                    <td>{{item.month1}}</td>
                                    <td>{{item.month2}}</td>
                                    <td>{{item.month3}}</td>
                                    <td>{{item.month4}}</td>
                                    <td>{{item.month5}}</td>
                                    <td>{{item.month6}}</td>
                                    <td :class="$style[item.target_month_background_class]">{{item.target_month_check}}</td>
                                    <td :class="$style[item.six_month_background_class]">{{item.six_month_check}}</td>
                                    <td :class="$style[item.target_year_background_class]">{{item.target_year_check}}</td>
                                    <td :class="$style[item.four_week_background_class]">{{item.four_week_check}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!targetDataInfo.length" class="text-center" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">対象者がいません</div>
                </div>
                <div v-else-if="listId === 4">
                    <div v-if="targetDataInfo.length">
                        <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th>選択</th>
                                    <th>社員番号</th>
                                    <th>名前</th>
                                    <th>役職</th>
                                    <th>所属</th>
                                    <th>当年度有給取得</th>
                                    <th>有休取得義務まで</th>
                                    <th>振替休日残</th>
                                </tr>
                            </thead>
                            <tbody v-if="targetDataInfo.length">
                                <tr v-for="item in targetDataInfo" v-bind:key="item.index">
                                    <td><button class="btn btn-primary w-100" v-on:click="onClickSelect(item)" v-bind:disabled="isSelectedTarget">選択</button></td>
                                    <td>{{item.employee_code}}</td>
                                    <td>{{item.employee_name}}</td>
                                    <td>{{item.employee_post}}</td>
                                    <td>{{item.employee_office + "／" + item.employee_dept}}</td>
                                    <td>{{item.acquired_paid_leave_days}}日</td>
                                    <td>{{item.obligatory_take_paid_leave_days}}</td>
                                    <td>{{item.unused_substitute_information_days}}日</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!targetDataInfo.length" class="text-center" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">対象者がいません</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "laborManagementList_board",
    props:{
        employee_id: Number,
        is_selected_target: Boolean,
        target_data_info: Array,
        list_id: Number,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
            closeDateId: 0,
            listId: 0,
            isSelectedSingle:0,
            isSelectedTarget:false,
            targetDataInfo: [],
            targetEmployeeID: 0,
            selected_employee_message: '',
            SelectedEmployeeIdList: [],
            yearMonthList: [],
            yearfirstMonth:7,
            listName: '',
            isCheckedThirtysixApply:true,
            isCheckedThirtysixApply:true,
            selected_close_state_lastmonth: 0,
            closeStateList: [],
        };
    },
    methods: {
        onClickSelect(item){
            let data = {
                targetEmployeeID: item.employee_id,
                selected_employee_message: "選択中：　" + item.employee_code + "　　　" + item.employee_name + "　　　" + item.employee_post + "　　　" + item.employee_office + "／" + item.employee_dept,
            };
            this.$emit('selectTarget',data);
        },
    }, 
    computed: {
        targetDataInfoList: function() {
            var targetDataInfoList = [];
            for(var i in this.targetDataInfo) {
                var item = this.targetDataInfo[i];
                if(this.selected_close_state_lastmonth == 0 || this.selected_close_state_lastmonth == item.close_state_lastmonth) {
                    targetDataInfoList.push(item);
                }
            }
            return targetDataInfoList;
        }
    },
    watch: {
        is_selected_target: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isSelectedTarget = value;
            }
        },
        target_data_info: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.targetDataInfo = [];
                this.targetDataInfo = value;
            }
        },
        list_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(value <= 0)
                {
                    //テスト用
                    this.listId = 1;
                }
                else
                {
                    this.listId = Number(value);
                }
            }
        },
    },
    mounted(){
        this.closeStateList = this.getMasterData().close_state;
    }
}

</script>
<style module> 

.background_orange{
    background-color: #F8CBAD !important;
}
.background_green{
    background-color: #d1ffde !important;
}
.background_yellow{
    background-color: #fffaad !important;
}
.background_red{
    background-color: #ff8888 !important;
}
.background_white{
    background-color: #ffffff !important;
}

</style>






