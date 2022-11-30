<template>
    <div id="C105-03" class="card C105-card">
        <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            <h5 id="C105-03-01" class="mb-0">実績変更・事由登録
                <span style="position: absolute; right: 30px;"><i class="fas fa-chevron-down fa-2x" data-fa-transform="up-3"></i></span>
            </h5>
        </div>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion1">
            <div class="card-body">
                <div class="container-C105-1">
                    <div class="row">
                        <div id="C105-03-02" class="col-sm-4 C105-tblidx C105-tblidx-border-bottom" style="padding-top:8px">実績</div>
                        <div id="C105-03-03" class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b C105-tblcon-border-top form-group">
                            <select class="form-control form-control-sm" v-model="selected_work_achievement_id" v-bind:disabled="select_disabled_change" @change="reset_input_times">
                                <option v-for="work_achievement_elm in work_achievement" v-bind:value="work_achievement_elm.work_achievement_id" v-bind:key="work_achievement_elm.detail_no">
                                    {{work_achievement_elm.work_achievement_name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div id="C105-03-04" class="col-sm-4 C105-tblidx C105-tblidx-border-bottom" style="padding-top:8px">勤務帯</div>
                        <div id="C105-03-05" class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b form-group input-sm"> 
                            <select class="form-control form-control-sm" v-model="selected_work_zone_id" v-bind:disabled="select_disabled_change" @change="reset_input_times">
                                <option v-for="work_zone_elm in work_zone" v-bind:value="work_zone_elm.work_zone_id" v-bind:key="work_zone_elm.detail_no">
                                    {{work_zone_elm.work_zone_name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row" v-show="is_visible_input_apply">
                        <div class="col-sm-4 C105-tblidx C105-tblidx-border-bottom" style="padding-top:20px">申請時間</div>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-4 C105-tblidx C105-tblidx-border-left C105-tblidx-border-bottom" style="padding-top:5px">始業</div>
                                <div class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b form-group">
                                    <inputTypeTimeModel v-model="input_start_time" :isEnableInput="true" :setFocus="setFocusStartTime"></inputTypeTimeModel>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 C105-tblidx C105-tblidx-border-left C105-tblidx-border-bottom" style="padding-top:5px">終業</div>
                                <div class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b form-group">
                                    <inputTypeTimeModel v-model="input_end_time" :isEnableInput="true"></inputTypeTimeModel>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-show="is_visible_input_apply">
                        <div class="col-sm-4 C105-tblidx" style="padding-top:20px">休憩時間</div>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-4 C105-tblidx C105-tblidx-border-left C105-tblidx-border-bottom" style="padding-top:5px">通常</div>
                                <div class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b form-group">
                                    <inputTypeTimeModel v-model="input_break_time" :isEnableInput="true"></inputTypeTimeModel>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 C105-tblidx C105-tblidx-border-left" style="padding-top:5px">深夜</div>
                                <div class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b form-group">
                                    <inputTypeTimeModel v-model="input_midnight_break_time" :isEnableInput="true"></inputTypeTimeModel>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="selected_work_achievement_id === WORK_ACHIEVEMENT_ID_TRANSFER_HOLIDAY">
                    <div class="container-C105-1">
                        <div class="row">
                            <div id="C105-03-06" class="col-sm-4 C105-tblidx" style="padding-top:8px">振替休日予定日</div>
                            <div id="C105-03-07" class="col-sm-8 C105-tblcon C105-tblcon-border-rend-b C105-tblcon-border-top form-group">
                                <input type="date" class="form-control form-control-sm" v-model="substitute_holiday_date" min="1901-01-01"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-C105-1">
                    <div class="row">
                        <div id="C105-03-08" class="col-sm-12 C105-tblidx">連絡事項・事由・乖離理由等</div>
                    </div>
                    <div class="row">
                        <div id="C105-03-09" class="col-sm-12 C105-tblcon C105-tblcon-border-rend-b form-group">
                            <input id="C105-03-09-01" type="text" class="form-control form-control-sm" v-model="input_information" v-bind:disabled="isInformationEtc"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vuejsDatepicker from 'vuejs-datepicker'
export default {
    name: "attendanceDetailAchievement",
    components: {
        vuejsDatepicker
    },
    props: {
        attendance_information: Object,
        is_manager: Boolean,
        is_bunch: Boolean,
    },
    data() {
        return {
            selected_work_achievement_id: 0,
            selected_work_zone_id: 0,
            input_information: "",
            substitute_holiday_date: "",
            WORK_ACHIEVEMENT_ID_TRANSFER_HOLIDAY: 7,
            selected_work_holiday_id: 0,
            input_start_time: 0,
            input_end_time: 0,
            input_break_time: 0,
            input_midnight_break_time: 0,
            setFocusStartTime: false,
        };
    },
    methods:{
        //手入力部分をリセット
        reset_input_times: function(){
            const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == this.selected_work_zone_id);
            if(work_zone)
            {
                const work_zone_time = this.getMasterData().work_zone_time.find((elm) => elm.work_zone_id == this.selected_work_zone_id && elm.time_type_class == 1)
                this.input_start_time = work_zone_time.start_time;
                this.input_end_time = work_zone_time.end_time;
                this.input_break_time = work_zone.break_time;
                this.input_midnight_break_time = work_zone.midnight_break_time;
            }
            else
            {
                this.input_start_time = null;
                this.input_end_time = null;
                this.input_midnight_break_time = null;
                this.input_break_time = null;
            }
            //再計算
            this.reflect_input_times();
        },
        //入力時間反映
        reflect_input_times: function(){
            //申請時間は手入力の場合はinputから、そうでない場合は所定時間から判定
            const work_achievement = this.getMasterData().work_achievement.find((elm) => elm.work_achievement_id == this.selected_work_achievement_id);
            const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == this.selected_work_zone_id); 
            const work_zone_time = this.getMasterData().work_zone_time.find((elm) => elm.work_zone_id == this.selected_work_zone_id && elm.time_type_class == 1);
            let start_time = this.is_visible_input_apply ? this.input_start_time : this.attendance_information.work_time_start;
            let end_time = this.is_visible_input_apply ? this.input_end_time : this.attendance_information.work_time_end;
            const break_time = this.is_visible_input_apply ? this.input_break_time : work_zone?.break_time;
            const midnight_break_time = this.is_visible_input_apply ? this.input_midnight_break_time : work_zone?.midnight_break_time;
            //全時間のバッファ（休憩含む）
            if(end_time < start_time)
            {
                end_time += 24 * 60;
            }
            //差分が24時間越えは当日扱いに戻す
            else if(start_time + 24 * 60 <= end_time)
            {
                end_time -= 24 * 60;
            }
            let sum_buff = end_time - start_time;
            //実働時間 NaN対応
            if(isNaN(sum_buff))
            {
                sum_buff = 0;
            }
            //早朝時間
            let early_time = 0;
            if(start_time < 5 * 60)
            {
                if(end_time <= 5 * 60)
                {
                    //全部早朝
                    early_time = sum_buff;
                }
                else
                {
                    //開始～5時までが早朝
                    early_time = 5 * 60 - start_time;
                }
                sum_buff -= early_time;
            }
            //深夜実働
            let midnight_time = 0;
            if(22 * 60 < end_time && end_time <= 29 * 60)
            {
                if(22 * 60 <= start_time)
                {
                    //全部深夜
                    midnight_time = sum_buff;
                }
                else
                {
                    midnight_time = end_time - 22 * 60;
                }
                sum_buff -= midnight_time;
            }
            else if(29 * 60 < end_time)
            {
                if(22 * 60 <= start_time)
                {
                    midnight_time = 29 * 60 - start_time;
                }
                else
                {
                    midnight_time = 29 * 60 - 22 * 60;
                }
                sum_buff -= midnight_time;
            }
            //早朝・深夜時間（休憩時間除く）
            let out_time_sum = early_time + midnight_time - midnight_break_time;
            //早朝・深夜時間（休憩時間除く）NaN対応
            if(isNaN(out_time_sum))
            {
                out_time_sum = 0;
            }
            //実働時間（休憩時間除く）
            let actual_time_sum = sum_buff - break_time;
            //実働時間（休憩時間除く）NaN対応
            if(isNaN(actual_time_sum))
            {
                actual_time_sum = 0;
            }
            if(out_time_sum < 0)
            {
                //マイナスはNG
                out_time_sum = 0;
            }
            if(actual_time_sum < 0)
            {
                //マイナスはNG
                actual_time_sum = 0;
            }
            //ToDo 時間外の基準時間が固定なので、DBからの取得に変更
            const legal_time = 7 * 60 + 45;
            //法定外の基準
            const nonsat_time = 8 * 60;
            //法定内時間外
            let out_time_sum_legal = 0;
            if(legal_time < out_time_sum + actual_time_sum)
            {
                out_time_sum_legal = nonsat_time < out_time_sum + actual_time_sum ? nonsat_time - legal_time : out_time_sum + actual_time_sum - legal_time;
            }
            //法定外時間外
            let out_time_sum_nonsat = 0 < out_time_sum + actual_time_sum - nonsat_time ? out_time_sum + actual_time_sum - nonsat_time : 0;
            //休日出勤の場合は時間外なし
            if(this.selected_work_achievement_id == 4 || this.selected_work_achievement_id == 5 || this.selected_work_achievement_id == 6)
            {
                out_time_sum_legal = 0;
                out_time_sum_nonsat = 0;
            }

            //休日実働・休日深夜実働・休日休憩時間
            let holiday_time = 0;
            let holiday_out_time = 0;
            let holiday_break_time = 0;
            let holiday_midnight_break_time = 0;
            let break_time_buff = break_time == null ? 0 : break_time;
            let midnight_break_time_buff = midnight_break_time == null ? 0 : midnight_break_time;
            if(this.selected_work_achievement_id == 4 || this.selected_work_achievement_id == 5)
            {
                //時間を入れ替え
                holiday_time = actual_time_sum;
                holiday_out_time = out_time_sum;
                holiday_break_time = break_time == null ? 0 : break_time;
                holiday_midnight_break_time = midnight_break_time == null ? 0 : midnight_break_time;
                actual_time_sum = 0;
                out_time_sum = 0;
                break_time_buff = 0;
                midnight_break_time_buff = 0;
            }
            //休日出張(移動のみ)の場合は始業終業時間以外の時間は0
            else if(this.selected_work_achievement_id == 6)
            {
                holiday_time = 0;
                holiday_out_time = 0;
                holiday_break_time = 0;
                holiday_midnight_break_time = 0;
                actual_time_sum = 0;
                out_time_sum = 0;
                break_time_buff = 0;
                midnight_break_time_buff = 0;
            }
            //データ登録出来ない実績の場合は登録時間全て0
            if(work_achievement && work_achievement.is_not_register == 1)
            {
                start_time = 0;
                end_time = 0;
                actual_time_sum = 0;
                holiday_time = 0;
                out_time_sum = 0;
                holiday_out_time = 0;
                out_time_sum_legal = 0;
                out_time_sum_nonsat = 0;
                break_time_buff = 0;
                midnight_break_time_buff = 0;
                holiday_break_time = 0;
                holiday_midnight_break_time = 0;
            }
            //値をすべてemit
            this.$emit("setParams", {work_time_start: start_time});
            this.$emit("setParams", {work_time_end: end_time});
            this.$emit("setParams", {actual_work_time: actual_time_sum});
            this.$emit("setParams", {holiday_work_time: holiday_time});
            this.$emit("setParams", {midnight_time: out_time_sum});
            this.$emit("setParams", {holiday_midnight_work_time: holiday_out_time});
            this.$emit("setParams", {statutory_working_time: out_time_sum_legal});
            this.$emit("setParams", {non_statutory_working_time: out_time_sum_nonsat});
            this.$emit("setParams", {break_time: break_time_buff});
            this.$emit("setParams", {midnight_break_time: midnight_break_time_buff});
            this.$emit("setParams", {holiday_work_break_time: holiday_break_time});
            this.$emit("setParams", {holiday_midnight_work_break_time: holiday_midnight_break_time});
        },
    },
    computed:{
        select_disabled_change(){
            return (this.attendance_information.approval_state_id == 2 && this.is_manager)
                || (this.attendance_information.approval_state_id == 3 && this.is_manager)
                || (this.attendance_information.approval_state_id == 4 && this.is_manager)
                || (this.attendance_information.work_achievement_id === 9);
        },
        //申請日（申請ない場合は空欄）
        approval_request_date(){
            return this.attendance_information.approval_request_date === 0 ? "" : this.serialToDateStr(this.attendance_information.approval_request_date, "YYYY/MM/DD(A)");
        },
        //警告
        violation_warning_name(){
            return this.attendance_information.violation_warning ? this.attendance_information.violation_warning.violation_warning_name : "";
        },
        //申請状態
        approval_state(){
            return this.attendance_information.approval_state ? this.attendance_information.approval_state.approval_state_name : "";
        },
        //勤務実績
        work_achievement(){
            let ret_array = [];
            //未選択
            ret_array.push({
                "work_achievement_id": 0,
                "work_achievement_name": "",
                "work_achievement_display_class": 1,
                "detail_no": 0,
            });
            //実績表示区分
            let target_work_achievement_display_classes = [];
            target_work_achievement_display_classes.push(1);
            switch(this.selected_work_holiday_id)
            {
                case 1:
                    target_work_achievement_display_classes.push(2);
                    break;
                default:
                    target_work_achievement_display_classes.push(3);
                    if(!this.is_bunch){
                        target_work_achievement_display_classes.push(4);
                    }
                    break;
            }
            for(let i = 0; i < this.getMasterData().work_achievement.length; i++)
            {
                //削除済み除外
                if(this.getMasterData().work_achievement[i].is_delete !== 0)
                {
                    continue;
                }
                //ターゲットの実績表示区分に含まれていたら追加
                if(0 <= target_work_achievement_display_classes.indexOf(this.getMasterData().work_achievement[i].work_achievement_display_class))
                {
                    ret_array.push(this.getMasterData().work_achievement[i])
                }
            }
            return ret_array;
        },
        //勤務帯
        work_zone(){
            let ret_array = [];
            //未選択
            ret_array.push({
                "work_zone_id": 0,
                "work_zone_name": "",
                "detail_no": 0,
            });
            if(this.attendance_information.employee)
            {
                const work_zone_array = this.getMasterData().work_zone;
                for(let i = 0; i < work_zone_array.length; i++)
                {
                    //事業所共通,自事業所
                    if(work_zone_array[i].office_id === 0 || work_zone_array[i].office_id === this.attendance_information.employee.office_id)
                    {
                        ret_array.push(work_zone_array[i]);
                    }
                }
            }
            return ret_array;
        },
        //時刻入力の表示
        is_visible_input_apply(){
            //勤務帯が時給の場合は、常に表示
            let work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id === this.selected_work_zone_id);
            if(work_zone?.work_zone_aggrigation_class == 2)
            {
                return true;
            }
            //時給でない場合は実績で判断（休日出勤、振替なしの場合は表示）
            return this.selected_work_achievement_id == 4 || this.selected_work_achievement_id == 5 || this.selected_work_achievement_id == 6;
        },
        //リアクティブプロパティ
        work_achievement_id(){
            return this.attendance_information.work_achievement_id;
        },
        work_zone_id(){
            return this.attendance_information.work_zone_id;
        },
        //連絡事項・事由・乖離理由等（勤怠管理者 申請中、承認済、差戻しは入力不可とする）
        isInformationEtc() {
            return (this.attendance_information.approval_state_id == 2 && this.is_manager)
                || (this.attendance_information.approval_state_id == 3 && this.is_manager)
                || (this.attendance_information.approval_state_id == 4 && this.is_manager);
        }
    },
    watch: {
        attendance_information: {
            immediate: true,
            handler(value) {
                this.selected_work_achievement_id = value.work_achievement_id;
                this.selected_work_zone_id = value.work_zone_id;
                this.input_information = value.information;
                this.selected_work_holiday_id = value.work_holiday_id;
                if(value.substitute_information)
                {
                    this.substitute_holiday_date = this.serialToDateStr(value.substitute_information.substitute_holiday_date, "YYYY-MM-DD");
                }
                this.input_start_time = value.work_time_start;
                this.input_end_time = value.work_time_end;
                //申請が初期状態ならばwork_zone_timeから取得
                if(value.approval_state_id == 1)
                {
                    const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == value.work_zone_id);
                    if(work_zone)
                    {
                        this.input_break_time = work_zone.break_time;
                        this.input_midnight_break_time = work_zone.midnight_break_time;
                    }
                    else
                    {
                        this.input_break_time = 0;
                        this.input_midnight_break_time = 0;
                    }
                }
                else
                {
                    //休日か、休日でない方かのどちらかから入れる（どちらかはゼロなので大きい方入れる）
                    this.input_break_time = value.break_time < value.holiday_work_break_time ? value.holiday_work_break_time : value.break_time;
                    this.input_midnight_break_time = value.midnight_break_time < value.holiday_midnight_work_break_time ? value.holiday_midnight_work_break_time : value.midnight_break_time;
                }
                this.reflect_input_times();
            }
        },
        work_achievement_id: {
            handler(value){
                this.selected_work_achievement_id = value;
            }
        },
        selected_work_achievement_id:{
            handler(value){
                const work_achievement = this.getMasterData().work_achievement.find((elm) => elm.work_achievement_id == value);
                if(work_achievement?.is_not_register == 1)
                {
                    this.selected_work_zone_id = 0;
                }
                //実績を変更
                this.$emit("setParams", {work_achievement: work_achievement});
                this.$emit("setParams", {work_achievement_id: value});
            }
        },
        work_zone_id:{
            handler(value){
                this.selected_work_zone_id = value;
            }
        },
        selected_work_zone_id: {
            handler(value){
                const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == value);
                const work_zone_time = this.getMasterData().work_zone_time.find((elm) => elm.work_zone_id == value && elm.time_type_class == 1)
                this.$emit("setParams", {work_zone: work_zone});
                this.$emit("setParams", {work_zone_id: value});
                this.$emit("setParams", {work_zone_time_start: work_zone_time? work_zone_time.start_time : 0});
                this.$emit("setParams", {work_zone_time_end: work_zone_time? work_zone_time.end_time : 0});
                this.$emit("setParams", {work_time_start: this.attendance_information.work_time_start});
                this.$emit("setParams", {work_time_end: this.attendance_information.work_time_end});
                //フォーカス移動
                this.setFocusStartTime = false;
                this.sleep(10).then(()=>{
                    this.setFocusStartTime = true;
                })
            }
        },
        input_information: {
            handler(value){
                if(value == null || value.length == 0)
                {
                    //未選択
                    this.$emit("setParams", {information: null});
                }
                else
                {
                    this.$emit("setParams", {information: value});
                }
            }
        },
        substitute_holiday_date: {
            handler(value){
                if(value == null || value.length == 0)
                {
                    //未選択
                    //nothing to do
                }
                else
                {
                    this.$emit("setParams", {substitute_holiday_date: this.dateStrToSerial(value)});
                }
            }
        },
        input_start_time: {
            handler(value){
                this.reflect_input_times();
            }
        },
        input_end_time: {
            handler(value){
                this.reflect_input_times();
            }
        },
        input_break_time: {
            handler(value){
                this.reflect_input_times();
            }
        },
        input_midnight_break_time: {
            handler(value){
                this.reflect_input_times();
            }
        },
    }
}
</script>