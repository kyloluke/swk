<template>
    <div v-if="is_Target">
    <button v-on:click="reset_keyword" style="float: right;margin-right:5pt;margin-bottom:5pt;">検索リセット</button>
        <table class="table-white" style="margin-top:20pt;font-size:10pt;">
            <thead>
                <tr>
                    <th>選択</th>
                    <th @click="on_click_filter_employee_code" title="クリックして絞り込み" style="cursor: zoom-in">
                        <input v-show="is_filter_employee_code" v-model="employee_code_keyword" @blur="out_forcus_filter_employee_code" ref="filter_employee_code" style="width:110px;"  placeholder="社員番号"/>
                        <span v-show="!is_filter_employee_code" style="width:100px;"><i class="fas fa-search"></i>社員番号</span>
                    </th>
                    <th @click="on_click_filter_name" title="クリックして絞り込み" style="cursor: zoom-in">
                        <input v-show="is_filter_name" v-model="employee_name_keyword" @blur="out_forcus_filter_name" ref="filter_name" style="width:150px;"  placeholder="名前"/>
                        <span v-show="!is_filter_name" style="width:100px;"><i class="fas fa-search"></i>名前</span>
                    </th>
                    <th @click="on_click_filter_post" title="クリックして絞り込み" style="cursor: zoom-in">
                        <input v-show="is_filter_post" v-model="post_keyword" @blur="out_forcus_filter_post" ref="filter_post" style="width:70px;"  placeholder="役職"/>
                        <span v-show="!is_filter_post" style="width:100px;"><i class="fas fa-search"></i>役職</span>
                    </th>
                    <th @click="on_click_filter_office_dept" title="クリックして絞り込み" style="cursor: zoom-in">
                        <input v-show="is_filter_office_dept" v-model="office_dept_keyword" @blur="out_forcus_filter_office_dept" ref="filter_office_dept" style="width:110px;"  placeholder="所属"/>
                        <span v-show="!is_filter_office_dept" style="width:100px;"><i class="fas fa-search"></i>所属</span>
                    </th>
                    <th>未申請</th>
                    <th v-if="target_type === 1">未承認</th>
                    <th v-if="!session_data.is_production">勤怠警告</th>
                    <th><select v-model="selected_close_state_thismonth">
                        <option value=0 selected>締め</option>
                        <option v-for="option in closeStateList" :key="option.close_state_id" v-bind:value="option.close_state_name">{{ option.close_state_name }}</option>
                    </select></th>
                    <th>時間外時間</th>
                    <th>控除時間</th>
                    <th>法定外休日勤務時間</th>
                    <th>法定休日勤務時間</th>
                    <th>深夜時間</th>
                    <th>欠勤控除対象時間</th>
                    <th>育児等控除時間</th>
                </tr>
            </thead>
            <tbody v-if="filteredInputAgentListInfoList.length">
                <tr v-for="item in filteredInputAgentListInfoList" :key="item.employee_code">
                    <td :class="$style[item.list_background_class]"><button class="btn btn-primary w-100" v-on:click="selectTarget(item.employee_id,item.employee_code,item.employee_name,item.post,item.office_dept,item.employee_close_date_id)">選択</button></td>
                    <td :class="$style[item.list_background_class]">{{item.employee_code}}</td>
                    <td :class="$style[item.list_background_class]">{{item.employee_name}}</td>
                    <td :class="$style[item.list_background_class]">{{item.post}}</td>
                    <td :class="$style[item.list_background_class]">{{item.office_dept}}</td>
                    <td :class="$style[item.list_background_class]" :style="isCountExists(item.unapplied_cnt)" v-text="unAppliedCntText(item.unapplied_cnt)"></td>
                    <td v-if="target_type === 1" :class="$style[item.list_background_class]" :style="isCountExists(item.unapproved_cnt)" v-text="unApprovedCntText(item.unapproved_cnt)"></td>
                    <td v-if="!session_data.is_production" :class="$style[item.list_background_class]">{{item.violation_warning}}</td>
                    <td :class="$style[item.list_background_class]">{{item.close_state_thismonth}}</td>
                    <td :class="$style[item.list_background_class]">{{item.overtime_working_time}}</td>
                    <td :class="$style[item.list_background_class]">{{item.deduction_time}}</td>
                    <td :class="$style[item.list_background_class]">{{item.over_time_non_statutory_holiday_work_time}}</td>
                    <td :class="$style[item.list_background_class]">{{item.over_time_statutory_holiday_work_time}}</td>
                    <td :class="$style[item.list_background_class]">{{item.midnight_time}}</td>
                    <td :class="$style[item.list_background_class]">{{item.absent_deduction_target_time}}</td>
                    <td :class="$style[item.list_background_class]">{{item.chlid_deduction_time}}</td>
                </tr>
                <tr>
                    <td v-if="target_type !== 1 && !session_data.is_production" colspan="8">計</td>
                    <td v-else-if="target_type == 1 && !session_data.is_production" colspan="9">計</td>
                    <td v-else-if="target_type != 1 && session_data.is_production" colspan="7">計</td>
                    <td v-else colspan="8">計</td>
                    <td>{{serialToHoursStr(totalList.total_overtime_working_time)}}</td>
                    <td>{{serialToHoursStr(totalList.total_deduction_time)}}</td>
                    <td>{{serialToHoursStr(totalList.total_over_time_non_statutory_holiday_work_time)}}</td>
                    <td>{{serialToHoursStr(totalList.total_over_time_statutory_holiday_work_time)}}</td>
                    <td>{{serialToHoursStr(totalList.total_midnight_time)}}</td>
                    <td>{{serialToHoursStr(totalList.total_absent_deduction_target_time)}}</td>
                    <td>{{serialToHoursStr(totalList.total_chlid_deduction_time)}}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <td colspan="16">検索結果なし</td>
            </tbody>
        </table>
    </div>
    <div v-else class="text-center" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">対象者は設定されていません</div>
</template>

<script>
export default {
    name: "inputAgentList_board",
    props:{
        isTarget: Boolean,
        target_type: Number, //承認対象者：1　代理対象者：2
        session_data: Object,
        inputAgentListInfoList: Array,
    },
    data() {
        return {
            AgentListInfoList: [],
            targetEmployeeID: 0,
            selected_employee_message: '',
            yearMonthDay: 0,
            employee_code_keyword: '',
            employee_name_keyword: '',
            post_keyword: '',
            office_dept_keyword: '',
            is_filter_employee_code: false,
            is_filter_name: false,
            is_filter_post: false,
            is_filter_office_dept: false,
            is_Target: false,
            totalList: {
                total_overtime_working_time: 0,
                total_deduction_time: 0,
                total_over_time_non_statutory_holiday_work_time: 0,
                total_over_time_statutory_holiday_work_time: 0,
                total_midnight_time: 0,
                total_absent_deduction_target_time: 0,
                total_chlid_deduction_time: 0,
            },
            selected_close_state_thismonth: 0,
            closeStateList: [],
        };
    },
    mounted(){
        this.closeStateList = this.getMasterData().close_state;
    },
    methods:{
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
        },
        selectTarget(employee_id,employee_code,employee_name,post,office_dept,close_date_id){
            if(post == null){
                post = "";
            }
            let data = {
                targetEmployeeID: employee_id,
                targetEmployeeCloseDateId: close_date_id,
                selected_employee_message: "選択中：　" + employee_code + "　　　" + employee_name + "　　　" + post + "　　　" + office_dept,
            };
            this.$emit('selectTarget',data);
        },
        reset_keyword: function () {
            this.employee_code_keyword = '';
            this.employee_name_keyword = '';
            this.post_keyword = '';
            this.office_dept_keyword = '';
            this.is_filter_employee_code = false;
            this.is_filter_name = false;
            this.is_filter_post = false;
            this.is_filter_office_dept = false;
        },
        //inputからフォーカス外れた時
        out_forcus_filter_employee_code: function(){
            //空ならば社員番号を再表示
            if(!this.employee_code_keyword || this.employee_code_keyword.length == 0)
            {
                this.is_filter_employee_code = false;
            }
        },
        out_forcus_filter_name: function(){
            //空ならば名前を再表示
            if(!this.employee_name_keyword || this.employee_name_keyword.length == 0)
            {
                this.is_filter_name = false;
            }
        },
        out_forcus_filter_post: function(){
            //空ならば役職を再表示
            if(!this.post_keyword || this.post_keyword.length == 0)
            {
                this.is_filter_post = false;
            }
        },
        out_forcus_filter_office_dept: function(){
            //空ならば所属を再表示
            if(!this.office_dept_keyword || this.office_dept_keyword.length == 0)
            {
                this.is_filter_office_dept = false;
            }
        },
        //社員番号部分をクリックされたとき
        on_click_filter_employee_code: function(){
            //「社員番号」を非表示にして、フィルターinputを表示
            this.is_filter_employee_code = true;
            //inputへフォーカス
            this.$nextTick(function () {this.$refs.filter_employee_code.focus();});
        },
        //名前部分をクリックされたとき
        on_click_filter_name: function(){
            //「名前」を非表示にして、フィルターinputを表示
            this.is_filter_name = true;
            //inputへフォーカス
            this.$nextTick(function () {this.$refs.filter_name.focus();});
        },
        //役職部分をクリックされたとき
        on_click_filter_post: function(){
            //「役職」を非表示にして、フィルターinputを表示
            this.is_filter_post = true;
            //inputへフォーカス
            this.$nextTick(function () {this.$refs.filter_post.focus();});
        },
        //社員番号部分をクリックされたとき
        on_click_filter_office_dept: function(){
            //「社員番号」を非表示にして、フィルターinputを表示
            this.is_filter_office_dept = true;
            //inputへフォーカス
            this.$nextTick(function () {this.$refs.filter_office_dept.focus();});
        }
    },
    computed: {
        filteredInputAgentListInfoList: function() {
            var filteredInputAgentListInfoList = [];
            this.totalList.total_overtime_working_time = 0;
            this.totalList.total_deduction_time = 0;
            this.totalList.total_over_time_non_statutory_holiday_work_time = 0;
            this.totalList.total_over_time_statutory_holiday_work_time = 0;
            this.totalList.total_midnight_time = 0;
            this.totalList.total_absent_deduction_target_time = 0;
            this.totalList.total_chlid_deduction_time = 0;

            for(var i in this.AgentListInfoList) {
                var item = this.AgentListInfoList[i];
                if(String(item.employee_code).indexOf(this.employee_code_keyword) !== -1 &&
                String(item.employee_name).indexOf(this.employee_name_keyword) !== -1 &&
                String(item.post).indexOf(this.post_keyword) !== -1 &&
                String(item.office_dept).indexOf(this.office_dept_keyword) !== -1 &&
                (this.selected_close_state_thismonth == 0 || this.selected_close_state_thismonth == item.close_state_thismonth)) {
                    filteredInputAgentListInfoList.push({
                        'list_background_class': item.list_background_class,
                        'employee_id': item.employee_id,
                        'employee_code': item.employee_code,
                        'employee_name': item.employee_name,
                        'employee_close_date_id': item.employee_close_date_id,
                        'post': item.post,
                        'office_dept': item.office_dept,
                        'unapplied_cnt': item.unapplied_cnt,
                        'unapproved_cnt': item.unapproved_cnt,
                        'violation_warning': item.violation_warning,
                        'close_state_thismonth': item.close_state_thismonth,
                        'close_state_lastmonth': item.close_state_lastmonth,
                        'overtime_working_time': this.serialToHoursStr(item.overtime_working_time), 
                        'midnight_time': this.serialToHoursStr(item.midnight_time), 
                        'deduction_time': this.serialToHoursStr(item.deduction_time), 
                        'over_time_non_statutory_holiday_work_time': this.serialToHoursStr(item.over_time_non_statutory_holiday_work_time), 
                        'over_time_statutory_holiday_work_time': this.serialToHoursStr(item.over_time_statutory_holiday_work_time), 
                        'absent_deduction_target_time': this.serialToHoursStr(item.absent_deduction_target_time), 
                        'chlid_deduction_time': this.serialToHoursStr(item.chlid_deduction_time), 
                    });

                    this.totalList.total_overtime_working_time += item.overtime_working_time;
                    this.totalList.total_deduction_time += item.deduction_time;
                    this.totalList.total_over_time_non_statutory_holiday_work_time += item.over_time_non_statutory_holiday_work_time;
                    this.totalList.total_over_time_statutory_holiday_work_time += item.over_time_statutory_holiday_work_time;
                    this.totalList.total_midnight_time += item.midnight_time;
                    this.totalList.total_absent_deduction_target_time += item.absent_deduction_target_time;
                    this.totalList.total_chlid_deduction_time += item.chlid_deduction_time;
                }
            }

            return filteredInputAgentListInfoList;
        },
        unAppliedCntText: function(){
            return function(value){
                return (value <= 0 ? "無し" : value + "件");
            }
        },
        unApprovedCntText: function(){
            return function(value){
                return (value <= 0 ? "無し" : value + "件");
            }
        },
        isCountExists: function(){
            return function(value){
                return (value > 0 ? 'color :#FF0008 !important;' : '');
            }
        }
    },
    watch: {
        inputAgentListInfoList:{
            immediate: true,
            handler(value){
                if(value)
                {
                    this.AgentListInfoList = value;
                }
            }
        },
        isTarget:{
            immediate: true,
            handler(value){
                if(value)
                {
                    this.is_Target = value;
                }
            }
        },
    }
}

</script>
<style module> 
.list_background_red{
    background-color: #ff8888 !important;
}
.list_background_yellow{
    background-color: #fffaad !important;
}
.list_background_orange{
    background-color: #FF0008 !important;
}
.list_background_white{
    background-color: #ffffff !important;
}

</style>
