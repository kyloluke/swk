<template>
    <div class="card-body">
        <div class="container-C105-1">
            <div class="row">
                <div id="C105-04-02" class="col-sm-4 C105-tblidx" style="padding-top:4px;font-size: 18px">不就業時間合計</div>
                <div id="C105-04-03" class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b C105-tblcon-border-top" style="font-size: 18px">{{time_sum}}</div>
            </div>
        </div>
        <div class="container-C105-1">
            <attendanceDetailUnemployedPanel :attendance_information="attendance_information" :is_manager="is_manager" v-for="elm in unemployed_array" v-bind:key="elm.panel_index" :unemployed="elm" :panel_index="elm.panel_index" @setParams="setInputValues"></attendanceDetailUnemployedPanel>
        </div>
        <div id="C105-04-05" class="modal-footer d-flex justify-content-center" v-if="!isAddPanalDisplay">
            <button type="button" class="btn btn-primary w-100" v-on:click="onClickAddPanel" v-bind:disabled="button_disabled_change">＋　追加登録</button>
        </div>
    </div>
</template>

<script>
import attendanceDetailUnemployedPanel from '../../components/inputAttendanceDetailContents/attendanceDetailUnemployedPanel.vue';

export default {
    name: "attendanceUnemployed",
    components: {
        'attendanceDetailUnemployedPanel': attendanceDetailUnemployedPanel,
    },
    props: {
        attendance_information: Object,
        is_manager: Boolean,
    },
    data() {
        return {
            unemployed_array_holder: [],
            MAX_LENGTH_UNEMPLOYED : 3,
            time_sum_serial: 0,
        };
    },
    methods:{
        //値更新
        setInputValues: function(params){
            const panel_index = params.panel_index;
            const panel_values = params.values;
            for(let param_key in panel_values)
            {
                if(this.unemployed_array_holder[panel_index])
                {
                    this.unemployed_array_holder[panel_index][param_key] = panel_values[param_key];
                }
                else
                {
                    //登録できない不就業が登録されているとここを通る
                    //再申請や申請取り消し必要だけど、どうしよう？
                }
            }
            //不就業時間合計を更新
            this.calcUnemployedTime();
        },
        //追加をクリック
        onClickAddPanel: function(){
            if(this.unemployed_array_holder.length < this.MAX_LENGTH_UNEMPLOYED)
            {
                this.unemployed_array_holder.push({
                    'panel_index': this.unemployed_array_holder.length,
                    'unemployed_id': 0,
                    'unemployed_code': "",
                    'unemployed_name': "",
                    'start_time': 0,
                    'end_time': 0,
                    'request_reason': "",
                    'unemployed_take_unit_class': 0,
                    'remove': this.onClickRemovePanel,
                    'is_empty': this.checkEmpty,
                });
            }
        },
        onClickRemovePanel: function(panel_index){
            //要素削除
            if(0 < this.unemployed_array_holder.length)
            {
                //要素削除
                this.unemployed_array_holder.splice(panel_index, 1);
            }
        },
        calcUnemployedTime: function(){
            let is_time_unit_class_1 = false;
            let sum_absent = 0;
            let sum_unemployed_time = 0;
            let sum_unemployed_rest_time = 0;
            let sum_midnight_unemployed_time = 0;
            let sum_midnight_unemployed_rest_time = 0;
            let sum_overtime_additonal_time = 0;
            const work_zone_time = this.getMasterData().work_zone_time.find((elm) => elm.work_zone_id == this.attendance_information.work_zone_id && elm.time_type_class == 1);
            if(work_zone_time != null)
            {
                for(let key in this.unemployed_array_holder){
                    const unemployed = this.selected_unemployed(this.unemployed_array_holder[key].unemployed_id);
                    if(unemployed == null)
                    {
                        continue;
                    }
                    //勤務帯終了時刻が日付をまたいでいる場合、勤務帯開始時刻より前～勤務帯終了時刻超えない範囲ならば翌日扱い
                    if(this.unemployed_array_holder[key].start_time < work_zone_time.start_time && work_zone_time.end_time < this.unemployed_array_holder[key].start_time)
                    {
                        //開始時間は翌日
                        this.unemployed_array_holder[key].start_time += 24 * 60;
                    }
                    if(this.unemployed_array_holder[key].end_time < work_zone_time.start_time && work_zone_time.end_time < this.unemployed_array_holder[key].end_time)
                    {
                        //開始時間は翌日
                        this.unemployed_array_holder[key].end_time += 24 * 60;
                    }
                    //休暇取得区分 １：日数単位、２：半日数単位、３：時間単位
                    let unemployed_time = 0;
                    let midnight_unemployed_time = 0;
                    let rest_time_sum = 0;
                    let midnight_rest_time_sum = 0;
                    const work_zone_rest_time = this.getMasterData().work_zone_time.filter((elm) => elm.work_zone_id == this.attendance_information.work_zone_id && elm.time_type_class == 2);
                    if(work_zone_rest_time != null)
                    {
                        for(let i = 0; i < work_zone_rest_time.length; i++)
                        {
                            const rest_time_array = this.devideTimeArray(work_zone_rest_time[i]);
                            //内包している通常休憩時間
                            if(!isNaN(rest_time_array.normal_time.start_time))
                            {
                                rest_time_sum += this.getIncludeRestTime(rest_time_array.normal_time, this.unemployed_array_holder[key]);
                                if(!isNaN(rest_time_array.normal_time_nextday.start_time))
                                {
                                    rest_time_sum += this.getIncludeRestTime(rest_time_array.normal_time_nextday, this.unemployed_array_holder[key]);
                                }
                            }
                            //内包している深夜休憩時間
                            if(!isNaN(rest_time_array.midnight_time.start_time))
                            {
                                midnight_rest_time_sum += this.getIncludeRestTime(rest_time_array.midnight_time, this.unemployed_array_holder[key]);
                            }
                        }
                        const unemployed_time_array = this.devideTimeArray(this.unemployed_array_holder[key]);
                        if(!isNaN(unemployed_time_array.normal_time.start_time))
                        {
                            unemployed_time = unemployed_time_array.normal_time.end_time - unemployed_time_array.normal_time.start_time - rest_time_sum;
                            if(!isNaN(unemployed_time_array.normal_time_nextday.start_time))
                            {
                                unemployed_time += unemployed_time_array.normal_time_nextday.end_time - unemployed_time_array.normal_time_nextday.start_time;
                            }
                        }
                        if(!isNaN(unemployed_time_array.midnight_time.start_time))
                        {
                            midnight_unemployed_time = unemployed_time_array.midnight_time.end_time - unemployed_time_array.midnight_time.start_time - midnight_rest_time_sum;
                        }
                    }
                    if(unemployed_time < 0 || midnight_unemployed_time < 0)
                    {
                        continue;
                    }

                    //休暇管理区分 ０：初期状態、１：有給休暇、保存休暇、予備休暇
                    //有休対象　０：無給、１：有給
                    //出勤対象　０：欠勤、１：出勤
                    if(unemployed.work_day_target_class == 0)
                    {
                        sum_absent += (unemployed_time + midnight_unemployed_time);
                    }
                    //実時間保持
                    this.unemployed_array_holder[key]['unemployed_time'] = unemployed_time + midnight_unemployed_time;
                    sum_unemployed_time += unemployed_time;
                    sum_midnight_unemployed_time += midnight_unemployed_time;
                    sum_unemployed_rest_time += rest_time_sum;
                    sum_midnight_unemployed_rest_time += midnight_rest_time_sum;
                    if(this.unemployed_array_holder[key].unemployed_take_unit_class == 1)
                    {
                        is_time_unit_class_1 = true;
                    }
                    //時間外判定対象不就業時間
                    if(unemployed.paid_leave_target_class == 1 && true) //ToDo ここに、有休を時間外判定に計上するかのフラグで判定追加
                    {
                        sum_overtime_additonal_time += unemployed_time;
                        sum_overtime_additonal_time += midnight_unemployed_time;
                    }
                }
            }
            this.time_sum_serial = sum_unemployed_time + sum_midnight_unemployed_time;

            this.$emit("setParams", {absent_time: sum_absent});
            //unemployed_timeは合計時間
            this.$emit("setParams", {unemployed_time: sum_unemployed_time + sum_midnight_unemployed_time});
            //計算用に深夜時間も送る
            this.$emit("setParams", {unemployed_time_midnight: sum_midnight_unemployed_time});
            //差し引く時間
            this.$emit("setParams", {exclude_actual_work_time: sum_unemployed_time});
            this.$emit("setParams", {exclude_rest_time: sum_unemployed_rest_time});
            this.$emit("setParams", {exclude_midnight_actual_work_time: sum_midnight_unemployed_time});
            this.$emit("setParams", {exclude_midnight_rest_time: sum_midnight_unemployed_rest_time});
            //時間外時間計上用時間
            this.$emit("setParams", {additional_overtime_in_unemployed: sum_overtime_additonal_time});

            //最初の不就業名とIDを送る
            this.$emit("setParams", {unemployed_name: this.unemployed_array_holder[0]?.unemployed_name});
            this.$emit("setParams", {unemployed_id: this.unemployed_array_holder[0]?.unemployed_id});
            //配列全部送る
            this.$emit("setParams", {unemployed_array: this.unemployed_array_holder});

            //通常勤務日には実績の選択を解除
            if(this.attendance_information.work_holiday_id == 1)
            {
                this.$emit("setParams", {work_achievement: null});
                this.$emit("setParams", {work_achievement_id: 0});
            }
            //1日単位の不就業がある場合は申請時間をクリア
            if(is_time_unit_class_1)
            {
                this.$emit("setParams", {work_time_start: 0});
                this.$emit("setParams", {work_time_end: 0});
                this.$emit("setParams", {break_time: 0});
                this.$emit("setParams", {midnight_break_time: 0});
            }
        },
        //区分指定で勤務帯時間を取得
        getUnemployedTime: function(start_time, end_time){
            const work_zone_rest_time = this.getMasterData().work_zone_time.filter((elm) => elm.work_zone_id == this.attendance_information.work_zone_id && elm.time_type_class == 2);
            if(work_zone_rest_time == null)
            {
                return 0;
            }
            let rest_time_sum = 0;
            for(let i = 0; i < work_zone_rest_time.length; i++)
            {
                let rest_time = work_zone_rest_time[i]
                //休憩時間を引く
                if(start_time < rest_time.end_time && rest_time.start_time < end_time)
                {
                    //範囲内に休憩がある
                    if(rest_time.start_time < start_time)
                    {
                        rest_time_sum += rest_time.start_time - start_time;
                    }
                    else if(rest_time.end_time < end_time)
                    {
                        rest_time_sum += rest_time.end_time - rest_time.start_time;
                    }
                    else
                    {
                        rest_time_sum += end_time - rest_time.start_time;
                    }
                }
            }
            return end_time - start_time - rest_time_sum;
        },
        //通常休憩と深夜休憩に分割
        devideTimeArray: function(start_end_time)
        {
            let normal_time = [];
            let midnight_time = [];
            let normal_time_nextday = [];
            //通常時間と深夜時間に分ける  ※休憩時間が3つの時間帯に分割されることは無いものと仮定する。その場合は計算結果がおかしくなる。
            if(start_end_time.start_time < 5 * 60 && start_end_time.end_time <= 5 * 60)
            {
                //全て早朝時間
                midnight_time.start_time = start_end_time.start_time;
                midnight_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.start_time < 5 * 60 && 5 * 60 < start_end_time.end_time)
            {
                //前半は早朝、後半は通常
                midnight_time.start_time = start_end_time.start_time;
                midnight_time.end_time = 5 * 60;
                normal_time.start_time = 5 * 60;
                normal_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.end_time <= 22 * 60)
            {
                //すべて通常時間
                normal_time.start_time = start_end_time.start_time;
                normal_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.start_time < 22 * 60 && start_end_time.end_time <= (24 + 5) * 60)
            {
                //前半は通常、後半は深夜
                normal_time.start_time = start_end_time.start_time;
                normal_time.end_time = 22 * 60;
                midnight_time.start_time = 22 * 60;
                midnight_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.start_time < 22 * 60 && start_end_time.end_time > (24 + 5) * 60)
            {
                //深夜を跨いだ前半通常、後半通常
                normal_time.start_time = start_end_time.start_time;
                normal_time.end_time = 22 * 60;
                midnight_time.start_time = 22 * 60;
                midnight_time.end_time = (24 + 5) * 60;
                normal_time_nextday.start_time = (24 + 5) * 60;
                normal_time_nextday.end_time = start_end_time.end_time;
            }
            else if(22 * 60 <= start_end_time.start_time && start_end_time.end_time <= (24 + 5) * 60)
            {
                //全部深夜
                midnight_time.start_time = start_end_time.start_time;
                midnight_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.start_time < (24 + 5) * 60)
            {
                //前半は深夜、後半は通常
                midnight_time.start_time = start_end_time.start_time;
                midnight_time.end_time = (24 + 5) * 60;
                normal_time.start_time = (24 + 5) * 60;
                normal_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.end_time < (24 + 22) * 60)
            {
                //全部通常
                normal_time.start_time = start_end_time.start_time;
                normal_time.end_time = start_end_time.end_time;
            }
            else if(start_end_time.start_time < (24 + 22) * 60 && (24 + 22) * 60 <= start_end_time.end_time)
            {
                //前半通常、後半深夜
                normal_time.start_time = start_end_time.start_time;
                normal_time.end_time = (24 + 22) * 60;
                midnight_time.start_time = (24 + 22) * 60;
                midnight_time.end_time = start_end_time.end_time;
            }
            else
            {
                //全部深夜
                midnight_time.start_time = start_end_time.start_time;
                midnight_time.end_time = start_end_time.end_time;
            }
            return {normal_time: normal_time, midnight_time: midnight_time, normal_time_nextday: normal_time_nextday};
        },
        //不就業に内包されている休憩時間取得
        getIncludeRestTime(rest_time, target_time)
        {
            let rest_time_sum = 0;
            if(target_time.start_time < rest_time.end_time && rest_time.start_time < target_time.end_time)
            {
                if(rest_time.start_time < target_time.start_time  && target_time.end_time < rest_time.end_time)
                {
                    //対象範囲の全範囲が休憩時間
                    rest_time_sum = target_time.end_time - target_time.start_time;
                }
                else if(rest_time.start_time < target_time.start_time)
                {
                    //対象期間の前半のみ、一部が休憩時間
                    rest_time_sum = rest_time.end_time - target_time.start_time;
                }
                else if(target_time.end_time < rest_time.end_time)
                {
                    //対象時間の後半のみ、一部休憩が時間
                    rest_time_sum = target_time.end_time - rest_time.start_time;
                }
                else
                {
                    //休憩がすべて内包されている
                    rest_time_sum = rest_time.end_time - rest_time.start_time;
                }
            }
            return rest_time_sum;
        },
        //選択中の不就業
        selected_unemployed(unemployed_id){
            for(let key in this.unemployed_list)
            {
                if(this.unemployed_list[key].unemployed_id == unemployed_id)
                {
                    return this.unemployed_list[key];
                }
            }
        },
        checkEmpty(panel_index){
            const holder = this.unemployed_array_holder[panel_index];
            let is_input = false;
            is_input |= holder.unemployed_id != 0;
            is_input |= holder.unemployed_name.length != 0;
            is_input |= holder.start_time != 0;
            is_input |= holder.end_time != 0;
            is_input |= holder.request_reason.length != 0;
            return !is_input;
        },
    },
    computed:{
        isAddPanalDisplay(){
            return (this.attendance_information.approval_state_id == 2 && this.is_manager)      // 勤怠管理者 申請中 非表示
                ||  (this.attendance_information.approval_state_id == 3 && this.is_manager)     // 勤怠管理者 承認済 非表示
                ||  (this.attendance_information.approval_state_id == 4 && this.is_manager);    // 勤怠管理者 差戻し 非表示
        },
        button_disabled_change(){
            return this.MAX_LENGTH_UNEMPLOYED <= this.unemployed_array.length || this.attendance_information.work_achievement_id === 9;
        },
        //申請日（申請ない場合は空欄）
        unemployed_array(){
            if(this.unemployed_array_holder == null || this.unemployed_array_holder.length == 0)
            {
                this.unemployed_array_holder.push({
                    'panel_index': 0,
                    'unemployed_id': 0,
                    'unemployed_code': "",
                    'unemployed_name': "",
                    'start_time': 0,
                    'end_time': 0,
                    'request_reason': "",
                    'unemployed_take_unit_class': 0,
                    'remove': this.onClickRemovePanel,
                    'is_empty': this.checkEmpty,
                });
            }
            //index修正
            for(let i = 0; i < this.unemployed_array_holder.length; i++)
            {
                this.unemployed_array_holder[i].panel_index = i
            }
            return this.unemployed_array_holder;
        },
        //合計
        time_sum(){
            return this.serialToHoursStr(this.time_sum_serial);
        },
        unemployed_list(){
            let ret_array = [];
            //未選択
            ret_array.push({
                "unemployed_id": 0,
                "unemployed_name": "",
                "detail_no": 0, 
            });
            for(let i = 0; i < this.getMasterData().unemployed.length; i++)
            {
                //削除済み除外
                if(this.getMasterData().unemployed[i].is_delete === 0)
                {
                    ret_array.push(this.getMasterData().unemployed[i])
                }
            }
            return ret_array;
        },
        //所定時間
        scheduled_time(){
            return this.getMasterData().work_zone.find((elm) => elm.work_zone_id == this.attendance_information.work_zone_id)?.actual_work_time;
            //return this.getWorkZoneTime(1) - this.scheduled_rest_time;
        },
        //所定休憩時間
        scheduled_rest_time(){
            const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == this.attendance_information.work_zone_id);
            return work_zone?.break_time + work_zone?.midnight_break_time;
            //return this.getWorkZoneTime(2);
        },
    },
    watch: {
        attendance_information: {
            handler(value){
                // this.reflectList();
                this.unemployed_array_holder = value.unemployed_array;
                for(let key in this.unemployed_array_holder)
                {
                    //DB取得のオブジェクトの場合removeとis_emptyが無いので追加
                    if(!this.unemployed_array_holder[key].is_empty)
                    {
                        this.unemployed_array_holder[key].start_time = this.unemployed_array_holder[key].unemployed_time_start;
                        this.unemployed_array_holder[key].end_time = this.unemployed_array_holder[key].unemployed_time_end;
                        this.unemployed_array_holder[key].remove = this.onClickRemovePanel;
                        this.unemployed_array_holder[key].is_empty = this.checkEmpty;
                        this.unemployed_array_holder[key].unemployed_name = this.getMasterData().unemployed.find((elm) => elm.unemployed_id == this.unemployed_array_holder[key].unemployed_id)?.unemployed_name;
                    }
                }
            }
        },
    }
}
</script>