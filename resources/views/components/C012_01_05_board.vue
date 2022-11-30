<template>
    <div>
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div id="C012-01-05-01" class="text-left">
                労働時間管理（{{yearMonthStartStr}}～{{yearMonthEndStr}}）
            </div>
            <div v-if="isExistData">
                <div class="row">
                    <div class="col-6 text-center">
                        <div id="C012-01-05-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-body">
                                <div class="card-title text-left">残業時間（直近6ヶ月）</div>
                                <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                                    <thead>
                                        <tr>
                                            <th>年月</th>
                                            <th>残業時間</th>
                                            <th>平均</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in labor_situation_info" :key="item.yearMonth">
                                            <td>{{item.yearMonth}}</td>
                                            <td>{{item.non_statutory_working_time}}</td>
                                            <td>{{item.average}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <card id="C012-01-05-03" title="残業時間（年間）" :time="totalTimeSum" :comment="lastTotalTimeSum"></card>
                    </div>
                    <div class="col-3 text-center">
                        <div class="row ">
                            <div class="col-12">
                                <card id="C012-01-05-04" title="特別条項適用（当月）" :text="specialProvisionsText"></card>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20pt;">
                            <div class="col-12">
                                <card id="C012-01-05-05" title="特別条項適用（年間）" :text="noSpecialProvisionsText" :number="usedSpecialProvisionsYear" :unit="lastSpecialProvisionsYear"></card>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!isExistData">
                <div class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
                データがありません
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: 'C012_01_05_board',
    components: {
        "loading":Loading
    },
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
    },
    data() {
        return {
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            employeeID: 0,
            yearMonth: 0,
            labor_situation_info: [],
            over_time_info: [],
            yearMonthList: [],
            yearMonthStart :0,
            yearMonthEnd: 0,
            dataCount: 0,
            totalTime: 0,
            maxTimeYear: 0,
            specialProvisions: 0,
            specialProvisionsYear: 0,
            SpecialProvisionsYearCount: 0,
            yearfirstMonth: 7,
        };
    },
    methods: {
        getWorkingTimeManagementMonthly: async function(){
            this.isLoading = true;
            this.labor_situation_info = [];
            this.yearMonthList = this.getYearMonthListFromMonth(this.yearMonth,this.yearfirstMonth);
            this.totalTime = 0;
            this.dataCount = 0;
            this.maxTimeYear = 0;
            this.specialProvisionsYear = 0;
            this.specialProvisions = 0;
            this.SpecialProvisionsYearCount = 0;
            let thirtysixAgreementApplyId = 0;
            let max_time_year_thirtysix_apply = 0;
            let totalTimeHalfYear = 0;
            let average = "－";
            for(let i = this.yearMonthList.length-1; i > -1; i--){
                if(i == 0){
                    this.yearMonthStart = this.yearMonthList[i];
                }
                if(i == this.yearMonthList.length - 1){
                    this.yearMonthEnd = this.yearMonthList[i];
                }
                let selected_labor_situation_info = await this.getAttendanceInformationMonthly(this.employeeID, this.yearMonthList[i]);
                if(selected_labor_situation_info.attendance_aggregate != null){
                    if(this.yearMonthList[i] <= this.yearMonth){
                        this.totalTime += selected_labor_situation_info.attendance_aggregate != null ? selected_labor_situation_info.attendance_aggregate.non_statutory_working_time + selected_labor_situation_info.holiday_work_time : 0;
                        this.specialProvisionsYear += selected_labor_situation_info.thirtysix_agreement_special_provisions_apply_class;
                    }
                    this.dataCount++;
                    if(this.yearMonthList[i] == this.yearMonth){
                        thirtysixAgreementApplyId = selected_labor_situation_info.thirtysix_agreement_apply_id;
                        this.maxTimeYear = selected_labor_situation_info.max_time_year_thirtysix_disapply;
                        max_time_year_thirtysix_apply = selected_labor_situation_info.max_time_year_thirtysix_apply;
                        this.specialProvisions = selected_labor_situation_info.attendance_aggregate.thirtysix_agreement_special_provisions_apply_class;
                        this.SpecialProvisionsYearCount = selected_labor_situation_info.thirtysix_agreement_special_provisions_max_count;
                    }
                }
                if(this.yearMonthList[i] >= this.calcYearMonth(this.yearMonth, -5) && this.yearMonthList[i] <= this.yearMonth){  
                    totalTimeHalfYear += selected_labor_situation_info.attendance_aggregate != null ? (selected_labor_situation_info.attendance_aggregate.non_statutory_working_time + selected_labor_situation_info.holiday_work_time) : 0;
                    if(this.labor_situation_info.length != 0){
                        average = totalTimeHalfYear == 0 ? "--:--" + "　　　" + String(this.labor_situation_info.length + 1) + "ヵ月" :this.serialToHoursStr(Math.ceil(totalTimeHalfYear/(this.labor_situation_info.length + 1))) + "　　　" + String(this.labor_situation_info.length + 1) + "ヵ月";
                    }
                    this.labor_situation_info.push({
                        'yearMonth' : this.yearMonthList[i],
                        'non_statutory_working_time' : selected_labor_situation_info.attendance_aggregate != null ? this.serialToHoursStr(selected_labor_situation_info.attendance_aggregate.non_statutory_working_time + selected_labor_situation_info.holiday_work_time) : "--:--",
                        'average' : average,
                    });
                }
            }
            if(this.totalTime > this.maxTimeYear){
                this.maxTimeYear = max_time_year_thirtysix_apply;
            }
            if(this.labor_situation_info.length < 6){
                let firstMonth = this.yearMonthList[0];
                for(let j = 6 - this.labor_situation_info.length; j > 0; j--){
                    firstMonth = this.calcYearMonth(firstMonth,-1);
                    let selected_labor_situation_info = await this.getAttendanceInformationMonthly(this.employeeID, firstMonth);
                    totalTimeHalfYear += selected_labor_situation_info.attendance_aggregate != null ? (selected_labor_situation_info.attendance_aggregate.non_statutory_working_time + selected_labor_situation_info.holiday_work_time) : 0;
                    if(this.labor_situation_info.length != 0){
                        average = totalTimeHalfYear == 0 ? "--:--" + "　　　" + String(this.labor_situation_info.length + 1) + "ヵ月" :this.serialToHoursStr(Math.ceil(totalTimeHalfYear/(this.labor_situation_info.length + 1))) + "　　　" + String(this.labor_situation_info.length + 1) + "ヵ月";
                    }
                    this.labor_situation_info.push({
                        'yearMonth' : firstMonth,
                        'non_statutory_working_time' : selected_labor_situation_info.attendance_aggregate != null ? this.serialToHoursStr(selected_labor_situation_info.attendance_aggregate.non_statutory_working_time + selected_labor_situation_info.holiday_work_time) : "--:--",
                        'average' : average,
                    });
                }
            }
            this.isLoading = false;
        },
        onCancel() {
            //Loading画面のキャンセル
        },
    },
    computed:{
        isExistData: function(){
            return !this.dataCount == 0;
        },
        yearMonthStartStr: function(){
            return this.calcYearMonth(this.yearMonthStart, 0, "YYYY年MM月");
        },
        yearMonthEndStr: function(){
            return this.calcYearMonth(this.yearMonthEnd, 0, "YYYY年MM月");
        },
        totalTimeSum: function(){
            return this.totalTime == 0 ? "00:00": this.serialToHoursStr(this.totalTime);
        },
        lastTotalTimeSum: function(){
            return '※年間上限まで' + (this.maxTimeYear - this.totalTime > 0 ? this.serialToHoursStr(this.maxTimeYear - this.totalTime) : "00:00");
        },
        specialProvisionsText: function(){
            return this.specialProvisions == 0 ? '適用なし': '適用あり';
        },
        noSpecialProvisionsText: function(){
            return this.SpecialProvisionsYearCount == 0 ? '特別条項の定めなし': '';
        },
        usedSpecialProvisionsYear: function(){
            return this.SpecialProvisionsYearCount == 0 ? '' : String(this.specialProvisionsYear);
        },
        lastSpecialProvisionsYear: function(){
            return this.SpecialProvisionsYearCount == 0 ? '' : ('回（残' + String(this.SpecialProvisionsYearCount - this.specialProvisionsYear > 0 ? this.SpecialProvisionsYearCount - this.specialProvisionsYear: 0) + '回）');
        },
    },
    watch: {
        employee_id: {
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
                        this.getWorkingTimeManagementMonthly();
                    }
                }
            }
        },
        year_month: {
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
                        this.getWorkingTimeManagementMonthly();
                    }
                }
            }
        }
    },
}
</script>