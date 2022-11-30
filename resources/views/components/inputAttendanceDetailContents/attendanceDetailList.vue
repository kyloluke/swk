<template>
    <div class="board-C105-1">
        <div class="container-C105-1">
            <div class="row">
                <div id="C105-02-01" class="col-sm-4 C105-tblidx">日付</div>
                <div id="C105-01-03" class="col-sm-4 C105-tblidx C105-tblidx-border-left">警告</div>
                <div id="C105-01-05" class="col-sm-4 C105-tblidx C105-tblidx-border-left">承認</div>
            </div>
            <div class="row">
                <div id="C105-02-02" class="col-sm-4 C105-tblcon C105-tblcon-border">{{attendance_date}}</div>
                <div id="C105-01-04" class="col-sm-4 C105-tblcon C105-tblcon-border">{{violation_warning_name}}</div>
                <div id="C105-01-06" class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b">{{approval_state}}</div>
            </div>
            <div class="row">
                <div id="C105-02-03" class="col-sm-4 C105-tblidx">実績</div>
                <div id="C105-02-05" class="col-sm-4 C105-tblidx C105-tblidx-border-left">不就業</div>
                <div id="C105-02-07" class="col-sm-4 C105-tblidx C105-tblidx-border-left">勤務帯</div>
            </div>
            <div class="row">
                <div id="C105-02-04" class="col-sm-4 C105-tblcon C105-tblcon-border">{{work_achievement}}</div>
                <div id="C105-02-06" class="col-sm-4 C105-tblcon C105-tblcon-border">{{unemployed_name}}</div>
                <div id="C105-02-08" class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b">{{work_zone}}</div>
            </div>
            <div class="row">
                <div id="C105-02-09" class="col-sm-4 C105-tblidx">所定
                    <div class="row">
                        <div id="C105-02-10" class="col-sm-6 C105-tblidx C105-tblidx-border-top">始業</div>
                        <div id="C105-02-12" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">終業</div>
                    </div>
                </div>
                <div id="C105-02-14" class="col-sm-4 C105-tblidx C105-tblidx-border-left">申請
                    <div class="row">
                        <div id="C105-02-15" class="col-sm-6 C105-tblidx C105-tblidx-border-top">始業</div>
                        <div id="C105-02-17" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">終業</div>
                    </div>
                </div>
                <div id="C105-02-19" class="col-sm-4 C105-tblidx C105-tblidx-border-left">打刻 <!--【要検討】右が1pxへこむ。なぜ　-->
                    <div class="row">
                        <div id="C105-02-20" class="col-sm-6 C105-tblidx C105-tblidx-border-top">始業</div>
                        <div id="C105-02-22" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">終業</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C105-02-11" class="col-sm-2 C105-tblcon C105-tblcon-border">{{work_zone_time_start}}</div>
                <div id="C105-02-13" class="col-sm-2 C105-tblcon C105-tblcon-border">{{work_zone_time_end}}</div>
                <div id="C105-02-16" class="col-sm-2 C105-tblcon C105-tblcon-border">{{work_time_start}}</div>
                <div id="C105-02-18" class="col-sm-2 C105-tblcon C105-tblcon-border">{{work_time_end}}</div>
                <div id="C105-02-21" class="col-sm-2 C105-tblcon C105-tblcon-border">{{web_punch_clock_time_start}}</div>
                <div id="C105-02-23" class="col-sm-2 C105-tblcon C105-tblcon-border-rend-b">{{web_punch_clock_time_end}}</div>
            </div>
            <div class="row">
                <div id="C105-02-24" class="col-sm-8 C105-tblidx">実働時間
                    <div class="row">
                        <div id="C105-02-25" class="col-sm-3 C105-tblidx C105-tblidx-border-top">通常</div>
                        <div id="C105-02-27" class="col-sm-3 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">深夜</div>
                        <div id="C105-02-29" class="col-sm-3 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">休日</div>
                        <div id="C105-02-31" class="col-sm-3 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">休日深夜</div>
                    </div>
                </div>
                <div id="C105-02-33" class="col-sm-4 C105-tblidx C105-tblidx-border-left">時間外時間 <!--【要検討】右が1pxへこむ。なぜ　-->
                    <div class="row">
                        <div id="C105-02-34" class="col-sm-6 C105-tblidx C105-tblidx-border-top">法定内</div>
                        <div id="C105-02-36" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">法定外</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C105-02-26" class="col-sm-2 C105-tblcon C105-tblcon-border">{{actual_work_time}}</div>
                <div id="C105-02-28" class="col-sm-2 C105-tblcon C105-tblcon-border">{{midnight_time}}</div>
                <div id="C105-02-30" class="col-sm-2 C105-tblcon C105-tblcon-border">{{holiday_work_time}}</div>
                <div id="C105-02-32" class="col-sm-2 C105-tblcon C105-tblcon-border">{{holiday_midnight_work_time}}</div>
                <div id="C105-02-35" class="col-sm-2 C105-tblcon C105-tblcon-border">{{statutory_working_time}}</div>
                <div id="C105-02-37" class="col-sm-2 C105-tblcon C105-tblcon-border-rend-b">{{non_statutory_working_time}}</div>
            </div>
            <div class="row">
                <div id="C105-02-38" class="col-sm-8 C105-tblidx">休憩時間
                    <div class="row">
                        <div id="C105-02-39" class="col-sm-3 C105-tblidx C105-tblidx-border-top">通常</div>
                        <div id="C105-02-41" class="col-sm-3 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">深夜</div>
                        <div id="C105-02-43" class="col-sm-3 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">休日</div>
                        <div id="C105-02-45" class="col-sm-3 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">休日深夜</div>
                    </div>
                </div>
                <div id="C105-02-47" class="col-sm-4 C105-tblidx C105-tblidx-border-left">控除対象時間 <!--【要検討】右が1pxへこむ。なぜ　-->
                    <div class="row">
                        <div id="C105-02-48" class="col-sm-6 C105-tblidx C105-tblidx-border-top">控除</div>
                        <div id="C105-02-50" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">欠勤</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C105-02-40" class="col-sm-2 C105-tblcon C105-tblcon-border">{{break_time}}</div>
                <div id="C105-02-42" class="col-sm-2 C105-tblcon C105-tblcon-border">{{midnight_break_time}}</div>
                <div id="C105-02-44" class="col-sm-2 C105-tblcon C105-tblcon-border">{{holiday_work_break_time}}</div>
                <div id="C105-02-46" class="col-sm-2 C105-tblcon C105-tblcon-border">{{holiday_midnight_work_break_time}}</div>
                <div id="C105-02-49" class="col-sm-2 C105-tblcon C105-tblcon-border">{{deduction_time}}</div>
                <div id="C105-02-51" class="col-sm-2 C105-tblcon C105-tblcon-border-rend-b">{{absent_time}}</div>
            </div>
            <div class="row">
                <div id="C105-02-52" class="col-sm-12 C105-tblidx">連絡事項・事由・乖離理由等</div>
            </div>
            <div class="row">
                <div id="C105-02-53" class="col-sm-12 C105-tblcon C105-tblcon-border-rend-b"><br v-if="!information || information.length == 0">
                    {{information}}
                </div>
            </div>
            <div class="row">
                <div id="C105-02-54" class="col-sm-12 C105-tblidx">差戻理由</div>
            </div>
            <div class="row">
                <div id="C105-02-55" class="col-sm-12 C105-tblcon C105-tblcon-border-rend-b form-group">
                    <input id="C105-02-55-01" type="text" class="form-control form-control-sm" v-model="remand_reason" v-bind:disabled="!isRemandReason"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "attendanceDetailList",
    props: {
        session_data: Object,
        attendance_information: Object,
        is_manager: Boolean,
        information_attendance_mode: Number,
    },
    data() {
        return {
            unemployed_name: "",
            remand_reason: "",
        };
    },
    methods:{
    },
    computed:{
        //警告
        violation_warning_name(){
            return this.attendance_information.violation_warning ? this.attendance_information.violation_warning.violation_warning_name : "";
        },
        //申請状態
        approval_state(){
            return this.attendance_information.approval_state ? this.attendance_information.approval_state.approval_state_name : "";
        },
        //日付
        attendance_date(){
            return this.serialToDateStr(this.attendance_information.attendance_date, "YYYY/MM/DD/(A)");
        },
        //実績
        work_achievement(){
            return this.attendance_information.work_achievement ? this.attendance_information.work_achievement.work_achievement_name : "";
        },
        //不就業情報ある場合、最初の一つを表示
        unemployed_information(){
            return this.attendance_information.unemployed_name ? this.attendance_information.unemployed_name : "";
        },
        //勤務帯
        work_zone(){
            return this.attendance_information.work_zone ? this.attendance_information.work_zone.work_zone_name : "";
        },
        //所定始業
        work_zone_time_start(){
            return this.serialToTimeStr(this.attendance_information.work_zone_time_start, false);
        },
        //所定終業
        work_zone_time_end(){
            return this.serialToTimeStr(this.attendance_information.work_zone_time_end, false);
        },
        //申請始業
        work_time_start(){
            return this.serialToTimeStr(this.attendance_information.work_time_start, false);
        },
        //申請終業
        work_time_end(){
            return this.serialToTimeStr(this.attendance_information.work_time_end, false);
        },
        //打刻始業
        web_punch_clock_time_start(){
            const web_punch_array = this.attendance_information.web_punch_clock_list;
            if(web_punch_array){
                const one_day_array =  web_punch_array.filter(item => item.punch_clock_date === this.attendance_information.attendance_date && 
                    item.clocking_in_out_id === 1 && item.input_class === 1);

                return one_day_array.length > 0? this.serialToTimeStr(this.attendance_information.web_punch_clock_time_start, false)+"※" : 
                    this.serialToTimeStr(this.attendance_information.web_punch_clock_time_start, false);
            }else{
                return this.serialToTimeStr(this.attendance_information.web_punch_clock_time_start, false);
            }
        },
        //打刻終業
        web_punch_clock_time_end(){
            const web_punch_array = this.attendance_information.web_punch_clock_list;
            if(web_punch_array){
                const one_day_array =  web_punch_array.filter(item => item.punch_clock_date === this.attendance_information.attendance_date && 
                    item.clocking_in_out_id === 2 && item.input_class === 1);

                return one_day_array.length > 0? this.serialToTimeStr(this.attendance_information.web_punch_clock_time_end, false)+"※" : 
                this.serialToTimeStr(this.attendance_information.web_punch_clock_time_end, false);
            }else{
                return this.serialToTimeStr(this.attendance_information.web_punch_clock_time_end, false);
            }
        },
        //実働通常
        actual_work_time(){
            //登録された日が休日計上対象の場合は、実働はゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class == 3)
            {
                return this.serialToHoursStr(0);
            }
            //実績から登録された実働
            let actual_work_time = this.attendance_information.actual_work_time;
            //不就業からの時間を差し引く
            actual_work_time -= this.attendance_information.exclude_actual_work_time ?? 0;
            //ゼロを下回ったら空文字で表示（マイナス値をそのまま送ればOK）
            return this.serialToHoursStr(actual_work_time);
        },
        //実働深夜
        midnight_time(){
            //登録された日が休日計上対象の場合は、実働はゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class == 3)
            {
                return this.serialToHoursStr(0);
            }
            let midnight_time = this.attendance_information.midnight_time;
            //不就業からの時間を差し引く
            midnight_time -= this.attendance_information.exclude_midnight_actual_work_time ?? 0;
            //残業時間を追加
            midnight_time += this.attendance_information.additional_midnight_time ?? 0;
            //ゼロを下回ったら空文字で表示（マイナス値をそのまま送ればOK）
            return this.serialToHoursStr(midnight_time);
        },
        //実働休日
        holiday_work_time(){
            //登録された日が休日計上対象の以外場合は、ゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class != 3)
            {
                return this.serialToHoursStr(0);
            }
            let holiday_work_time = this.attendance_information.holiday_work_time;
            //不就業からの時間を差し引く
            holiday_work_time -= this.attendance_information.exclude_actual_work_time ?? 0;
            //残業時間を追加
            holiday_work_time += this.attendance_information.additional_actual_work_time ?? 0;
            //ゼロを下回ったら空文字で表示（マイナス値をそのまま送ればOK）
            return this.serialToHoursStr(holiday_work_time);
        },
        //実働休日深夜
        holiday_midnight_work_time(){
            //登録された日が休日計上対象の以外場合は、ゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class != 3)
            {
                return this.serialToHoursStr(0);
            }
            let holiday_midnight_work_time = this.attendance_information.holiday_midnight_work_time;
            //不就業からの時間を差し引く
            holiday_midnight_work_time -= this.attendance_information.exclude_midnight_actual_work_time;
            //残業時間を追加
            holiday_midnight_work_time += this.attendance_information.additional_midnight_time ?? 0;
            //ゼロを下回ったら空文字で表示（マイナス値をそのまま送ればOK）
            return this.serialToHoursStr(holiday_midnight_work_time);
        },
        //時間外法定内
        statutory_working_time(){
            //登録された日が休日計上対象の場合は、時間外時間はゼロ扱い
            if(this.attendance_information.work_achievement?.work_achievement_display_class == 3)
            {
                return this.serialToHoursStr(0);
            }
            let work_time_sum = this.attendance_information.actual_work_time;
            work_time_sum += this.attendance_information.midnight_time;
            work_time_sum -= this.attendance_information.exclude_actual_work_time ?? 0;
            work_time_sum -= this.attendance_information.exclude_midnight_actual_work_time ?? 0;
            work_time_sum += this.attendance_information.additional_actual_work_time ?? 0;
            work_time_sum += this.attendance_information.additional_midnight_time ?? 0;
            work_time_sum += this.attendance_information.additional_overtime_in_unemployed ?? 0;

            let overtime_base_time = this.attendance_information?.employee?.overtime_base_time ?? 0;
            if(work_time_sum <= overtime_base_time)
            {
                return this.serialToHoursStr(0);
            }
            else if(work_time_sum <= 8 * 60)
            {
                return this.serialToHoursStr(work_time_sum - overtime_base_time);
            }
            else
            {
                return this.serialToHoursStr(8 * 60 - overtime_base_time);
            }
        },
        //時間外法定外
        non_statutory_working_time(){
            //登録された日が休日計上対象の場合は、時間外時間はゼロ扱い
            if(this.attendance_information.work_achievement?.work_achievement_display_class == 3)
            {
                return this.serialToHoursStr(0);
            }
            let work_time_sum = this.attendance_information.actual_work_time;
            work_time_sum += this.attendance_information.midnight_time;
            work_time_sum -= this.attendance_information.exclude_actual_work_time ?? 0;
            work_time_sum -= this.attendance_information.exclude_midnight_actual_work_time ?? 0;
            work_time_sum += this.attendance_information.additional_midnight_time ?? 0;
            work_time_sum += this.attendance_information.additional_overtime_in_unemployed ?? 0;

            if(work_time_sum <= 8 * 60)
            {
                return this.serialToHoursStr(0);
            }

            return this.serialToHoursStr(work_time_sum - 8 * 60);
        },
        //休憩通常
        break_time(){
            //登録された日が休日計上対象の場合は、実働はゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class == 3)
            {
                return this.serialToHoursStr(0);
            }
            let break_time = this.attendance_information.break_time;
            //不就業からの時間を差し引く
            break_time -= this.attendance_information.exclude_rest_time ?? 0;
            //残業時間を追加
            break_time += this.attendance_information.additional_break_time ?? 0;
            //ゼロを下回ったらゼロと表示
            if(break_time < 0)
            {
                break_time = 0;
            }
            return this.serialToHoursStr(break_time);
        },
        //休憩深夜
        midnight_break_time(){
            //登録された日が休日計上対象の場合は、実働はゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class == 3)
            {
                return this.serialToHoursStr(0);
            }
            let midnight_break_time = this.attendance_information.midnight_break_time;
            //不就業からの時間を差し引く
            midnight_break_time -= this.attendance_information.exclude_midnight_rest_time ?? 0;
            //残業時間を追加
            midnight_break_time += this.attendance_information.additional_midnight_break_time ?? 0;
            //ゼロを下回ったらゼロと表示
            if(midnight_break_time < 0)
            {
                midnight_break_time = 0;
            }
            return this.serialToHoursStr(midnight_break_time);
        },
        //休憩休日
        holiday_work_break_time(){
            //登録された日が休日計上対象の以外場合は、ゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class != 3)
            {
                return this.serialToHoursStr(0);
            }
            let holiday_work_break_time = this.attendance_information.holiday_work_break_time;
            //不就業からの時間を差し引く
            holiday_work_break_time -= this.attendance_information.exclude_rest_time;
            //残業時間を追加
            holiday_work_break_time += this.attendance_information.additional_break_time ?? 0;
            //ゼロを下回ったらゼロと表示
            if(holiday_work_break_time < 0)
            {
                holiday_work_break_time = 0;
            }
            return this.serialToHoursStr(holiday_work_break_time);
        },
        //休憩休日深夜
        holiday_midnight_work_break_time(){
            //登録された日が休日計上対象の以外場合は、ゼロとする
            if(this.attendance_information.work_achievement?.work_achievement_display_class != 3)
            {
                return this.serialToHoursStr(0);
            }
            let holiday_midnight_work_break_time = this.attendance_information.holiday_midnight_work_break_time;
            //不就業からの時間を差し引く
            holiday_midnight_work_break_time -= this.attendance_information.exclude_midnight_rest_time ?? 0;
            //残業時間を追加
            holiday_midnight_work_break_time += this.attendance_information.additional_midnight_break_time ?? 0;
            //ゼロを下回ったらゼロと表示
            if(holiday_midnight_work_break_time < 0)
            {
                holiday_midnight_work_break_time = 0;
            }
            return this.serialToHoursStr(holiday_midnight_work_break_time);
        },
        //控除
        deduction_time(){
            return this.serialToHoursStr(this.attendance_information.deduction_time);
        },
        //欠勤
        absent_time(){
            return this.serialToHoursStr(this.attendance_information.absent_time);
        },
        //連絡事由・乖離事由等
        information(){
            return (this.attendance_information.substitute_holiday_reason ? this.attendance_information.substitute_holiday_reason : "") + ((this.attendance_information.substitute_holiday_reason && this.attendance_information.information) ? " ／ " : "") +
            (this.attendance_information.information ? this.attendance_information.information : "");
        },
        isRemandReason(){
            return (this.attendance_information.approval_state_id == 2 && this.is_manager)      // 勤怠管理者 申請中 編集可能
                || (this.attendance_information.approval_state_id == 4 && this.is_manager);     // 勤怠管理者 差戻し 編集可能
        }
    },
    watch: {
        attendance_information: {
            handler(value){
                this.unemployed_name = value.unemployed_name;
                this.remand_reason = value.remand_reason ? value.remand_reason : "";
            }
        },
        remand_reason:{
            handler(value){
                this.$emit("setParams", {remand_reason: value});
            }
        }
    }
}
</script>