<template>
    <div class="card-body">
        <div class="container-C105-1">
            <div class="row">
                <div id="C105-05-02" class="col-sm-4 C105-tblidx C105-tblidx-border-bottom" style="padding-top:4px;font-size: 18px">時間外時間合計</div>
                <div id="C105-05-03" class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b C105-tblcon-border-top" style="font-size: 18px">{{time_sum}}</div> <!--【暫定】DBのデータ合計を表示するよう変更が必要-->
            </div>
            <div class="row">
                <div id="C105-05-04" class="col-sm-4 C105-tblidx C105-tblidx-border-bottom" style="padding-top:4px;font-size: 18px">（内深夜時間）</div>
                <div id="C105-05-05" class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b" style="font-size: 18px">{{time_midnight_sum}}</div> <!--【暫定】DBのデータ合計を表示するよう変更が必要-->
            </div>
            <div class="row">
                <div id="C105-02-20" class="col-sm-2 C105-tblidx">始業打刻</div>
                <div id="C105-02-21" class="col-sm-2 C105-tblcon C105-tblcon-border-bottom">{{web_punch_clock_time_start}}</div>
                <div id="C105-02-22" class="col-sm-2 C105-tblidx">終業打刻</div>
                <div id="C105-02-23" class="col-sm-2 C105-tblcon C105-tblcon-border-rend-b">{{web_punch_clock_time_end}}</div>
                <div v-if="last_come_back_punched_time_show" id="C105-02-24" class="col-sm-2 C105-tblidx">戻り打刻</div>
                <div v-if="last_come_back_punched_time_show" id="C105-02-25" class="col-sm-2 C105-tblcon C105-tblcon-border-rend-tb">{{last_come_back_punched_time}}</div>
            </div>
        </div>
        <div class="container-C105-1">
            <attendanceDetailOverTimePanel 
                :session_data="session_data"
                :attendance_information="attendance_information"
                :is_manager="is_manager"
                v-for="elm in over_time_class_array" v-bind:key="elm.panel_index" :over_time_class="elm" :panel_index="elm.panel_index" :over_time_rest_time_calc="elm.over_time_rest_time_calc" :over_time_midnight_rest_time_calc="elm.over_time_midnight_rest_time_calc"
                @setParams="setInputValues"
                >
            </attendanceDetailOverTimePanel>
        </div>
        <div id="C105-05-26" class="modal-footer d-flex justify-content-center" v-if="!isAddPanalDisplay">
            <button type="button" class="btn btn-primary w-100" v-on:click="onClickAddPanel" v-bind:disabled="button_disabled_change">＋　追加登録</button>
        </div>
    </div>
</template>

<script>
import attendanceDetailOverTimePanel from './attendanceDetailOverTimePanel.vue';

export default {
    name: "attendanceDetailOverTime",
    components: {
        'attendanceDetailOverTimePanel': attendanceDetailOverTimePanel,
    },
    props: {
        session_data: Object,
        attendance_information: Object,
        is_manager: Boolean,
    },
    data() {
        return {
            over_time_class_array_holder: [],
            MAX_LENGTH_OVER_TIME_CLASS : 4,
            time_sum_serial: 0,
            time_midnight_sum_serial: 0,
            time_sum_rest_serial: 0,
            time_midnight_sum_rest_serial: 0,
            time_deduction_serial:0,
            last_come_back_punched_time:'',
            last_come_back_punched_time_show: false
        };
    },
    methods:{
        //値更新
        setInputValues: function(params){
            const panel_index = params.panel_index;
            const panel_values = params.values;
            for(let param_key in panel_values)
            {
                this.over_time_class_array_holder[panel_index][param_key] = panel_values[param_key];
            }
            //時間外時間合計を更新
            this.calcOverTime();
        },
        //追加をクリック
        onClickAddPanel: function(){
            if(this.over_time_class_array_holder.length < this.MAX_LENGTH_OVER_TIME_CLASS)
            {
                this.over_time_class_array_holder.push({
                    'panel_index': 0,
                    'over_time_class_id': 0,
                    'over_time_start': 0,
                    'over_time_end': 0,
                    'over_time_rest_time': 0,
                    'over_time_rest_time_calc': 0, //calcは自動計算値
                    'over_time_midnight_rest_time': 0,
                    'over_time_midnight_rest_time_calc': 0, //calcは自動計算値
                    'is_auto_input_rest_time': true,
                    'deduction_reason_id': 0,
                    'deduction_time': 0,
                    'deduction_reason': "",
                    'over_time_reason': "",
                    'is_enable_select_start': true, //時間外区分とパネルの状態により変更したい　未実装
                    'is_enable_select_end': true, //時間外区分とパネルの状態により変更したい　未実装
                    'remove': this.onClickRemovePanel,
                    'is_empty': this.checkEmpty,
                    'is_new': true,
                });
            }
        },
        //パネル削除
        onClickRemovePanel: function(panel_index){
            //要素削除
            if(0 < this.over_time_class_array_holder.length)
            {
                //要素削除
                this.over_time_class_array_holder.splice(panel_index, 1);
            }
        },
        //時間外時間の計算（すべてのパネルを積算）
        calcOverTime: function(){
            //時間外時間合計
            let sum = 0;
            //内深夜時間
            let sum_midnight = 0;
            //内早朝時間
            let sum_early = 0;
            //休憩時間
            let sum_rest_time = 0;
            //深夜休憩時間
            let sum_rest_midnight = 0;
            //控除時間
            let sum_deduction = 0;
            
            for(let key in this.over_time_class_array_holder){
                let sum_buff = 0;
                let sum_midnight_buff = 0;
                let sum_early_buff = 0;
                let sum_rest_time_buff = 0;
                let sum_rest_midnight_buff = 0;

                if(this.over_time_class_array_holder[key].over_time_class_id == 0)
                {
                    //時間外区分の選択がない場合はスキップ
                    this.over_time_class_array_holder[key].over_time_rest_time_calc = 0;
                    this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = 0;
                    continue;
                }
                //時間外の場合は入力時間の翌日判定
                if(this.over_time_class_array_holder[key].over_time_class_id == 1)
                {
                    // 時間外開始時間<時間帯の終了時間、翌日扱いにします、
                    // 時間外開始時間<自働終了時間の場合は、バッグできました
                    if(this.over_time_class_array_holder[key].over_time_start < this.attendance_information.work_zone_time_end)
                    {
                        //翌日扱い
                        this.over_time_class_array_holder[key].over_time_start += 24 * 60;
                    }
                    else if(24 * 60 < this.over_time_class_array_holder[key].over_time_start - this.attendance_information.work_time_end)
                    {
                        //24時間以上の場合、当日扱いに戻す
                        this.over_time_class_array_holder[key].over_time_start -= 24 * 60;
                    }
                    if(this.over_time_class_array_holder[key].over_time_end < this.attendance_information.work_time_end)
                    {
                        //翌日扱い
                        this.over_time_class_array_holder[key].over_time_end += 24 * 60;
                    }
                    else if(24 * 60 < this.over_time_class_array_holder[key].over_time_end - this.attendance_information.work_time_end)
                    {
                        //24時間以上の場合、当日扱いに戻す
                        this.over_time_class_array_holder[key].over_time_end -= 24 * 60;
                    }
                }
                //休憩・深夜を含めた時間外時間
                sum_buff = this.over_time_class_array_holder[key].over_time_end - this.over_time_class_array_holder[key].over_time_start;
                //ここでゼロより小さくなれば、設定がおかしい
                if(sum_buff < 0)
                {
                    //設定値無視てしてスキップ
                    this.over_time_class_array_holder[key].over_time_rest_time_calc = 0;
                    this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = 0;
                    continue;
                }

                //現在の合計時間から、自動計算の休憩時間を算出
                let rest_time_need = this.calcRestTimeNeed(sum + sum_buff + this.scheduled_time);
                if(this.scheduled_rest_time + this.scheduled_rest_time_midnight < rest_time_need)
                {
                    let rest_time_need_remain = rest_time_need - (this.scheduled_rest_time  + this.scheduled_rest_time_midnight + sum_rest_time + sum_rest_midnight);
                    //自動計算の休憩時間が深夜かどうかを、時間外時間の開始時間から計算する
                    //開始時間が5時より前
                    let before_5 = this.over_time_class_array_holder[key].over_time_start - 5 * 60;
                    let before_22 = this.over_time_class_array_holder[key].over_time_start - 22 * 60;
                    let before_29 = this.over_time_class_array_holder[key].over_time_start - 29 * 60;
                    let before_46 = this.over_time_class_array_holder[key].over_time_start - 46 * 60;
                    //翌日の深夜22時以降は考慮しないのでbefore_48は存在なし
                    if(before_5 < 0)
                    {
                        if(rest_time_need_remain + before_5 < 0)
                        {
                            //すべて5時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = 0;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = rest_time_need_remain;
                        }
                        else
                        {
                            //一部が5時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = rest_time_need_remain + before_5;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = -1 * before_5;
                        }
                    }
                    else if(before_22 < 0)
                    {
                        if(rest_time_need_remain + before_22 < 0)
                        {
                            //すべて22時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = rest_time_need_remain;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = 0;
                        }
                        else
                        {
                            //一部が22時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = -1 * before_22;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = rest_time_need_remain + before_22;
                        }
                    }
                    else if(before_29 < 0)
                    {
                        if(rest_time_need_remain + before_29 < 0)
                        {
                            //すべて翌5時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = 0;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = rest_time_need_remain;
                        }
                        else
                        {
                            //一部が翌5時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = rest_time_need_remain + before_29;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = -1 * before_29;
                        }
                    }
                    else if(before_46 < 0)
                    {
                        if(rest_time_need_remain + before_46 < 0)
                        {
                            //すべて翌22時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = rest_time_need_remain;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = 0;
                        }
                        else
                        {
                            //一部が翌22時以前のパターン
                            this.over_time_class_array_holder[key].over_time_rest_time_calc = -1 * before_46;
                            this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = rest_time_need_remain + before_46;
                        }
                    }
                    else
                    {
                        //すべて翌22時以降のパターン
                        this.over_time_class_array_holder[key].over_time_rest_time_calc = 0;
                        this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = rest_time_need_remain;
                    }
                }
                else
                {
                    //自動計算上の休憩時間は不要
                    this.over_time_class_array_holder[key].over_time_rest_time_calc = 0;
                    this.over_time_class_array_holder[key].over_time_midnight_rest_time_calc = 0;   
                }

                //Panelで指定された休憩時間を取得
                sum_rest_time_buff = this.over_time_class_array_holder[key].over_time_rest_time;
                sum_rest_midnight_buff = this.over_time_class_array_holder[key].over_time_midnight_rest_time;
                //ここ以降は休憩時間の計算はナシ。すべて、入力された値を使用して実働求める

                //時間外-必要な休憩-控除時間が0以下の場合、スキップ    //ToDoここで何らかのリセット必要かどうか検証
                // if(sum_buff - sum_rest_time_buff - sum_rest_midnight_buff -this.over_time_class_array_holder[key].deduction_time < 0)
                // {
                //     continue;
                // }
                //早朝時間外
                if(this.over_time_class_array_holder[key].over_time_start < 5 * 60)
                {
                    //終了時間が５時より早い場合、終了-開始
                    if(this.over_time_class_array_holder[key].over_time_end < 5 * 60)
                    {
                        sum_early_buff = this.over_time_class_array_holder[key].over_time_end - this.over_time_class_array_holder[key].over_time_start;
                    }
                    //それ以外は5時からの差分
                    else
                    {
                        sum_early_buff = 5 * 60 - this.over_time_class_array_holder[key].over_time_start;
                    }
                }
                //深夜時間外
                if(22 * 60 < this.over_time_class_array_holder[key].over_time_end && this.over_time_class_array_holder[key].over_time_end <= (24 + 5) * 60)
                {
                    //開始時間が22時より前ならば、深夜時間外は22時以降の時間
                    if(this.over_time_class_array_holder[key].over_time_start < 22 * 60)
                    {
                        sum_midnight_buff = this.over_time_class_array_holder[key].over_time_end - 22 * 60;
                    }
                    //それ以外は、終了-開始
                    else
                    {
                        sum_midnight_buff = this.over_time_class_array_holder[key].over_time_end - this.over_time_class_array_holder[key].over_time_start;
                    }
                }
                //翌日早朝時間より後が終了時間の時
                else if((24 + 5) * 60 < this.over_time_class_array_holder[key].over_time_end)
                {
                    //開始時間が22時より前ならば、深夜時間外は22時～翌朝5時までの時間-深夜休憩
                    if(this.over_time_class_array_holder[key].over_time_start <= 22 * 60)
                    {
                        sum_midnight_buff = 7 * 60;
                    }
                    //開始時間が翌朝5時より前ならば、開始時間～翌朝５時-深夜休憩
                    else if(this.over_time_class_array_holder[key].over_time_start <= (24 + 5) * 60)
                    {
                        sum_midnight_buff = (24 + 5) * 60 - this.over_time_class_array_holder[key].over_time_start;
                    }
                }
                
                //すべての値を足し込み
                sum += sum_buff;
                sum_midnight += sum_midnight_buff;
                sum_early += sum_early_buff;
                sum_rest_time +=  sum_rest_time_buff;
                sum_rest_midnight += sum_rest_midnight_buff;
                
                //控除時間を通常残業から差し引き
                if(0 < this.over_time_class_array_holder[key].deduction_time)
                {
                    sum_deduction += this.over_time_class_array_holder[key].deduction_time;
                    sum -= this.over_time_class_array_holder[key].deduction_time;
                    if(this.over_time_class_array_holder[key].deduction_time < (sum_midnight + sum_early))
                    {
                        //深夜時間で足りる場合
                        if(this.over_time_class_array_holder[key].deduction_time < sum_midnight){
                            sum_midnight -= this.over_time_class_array_holder[key].deduction_time;
                        }else{
                            sum_early -= this.over_time_class_array_holder[key].deduction_time - sum_midnight;
                            sum_midnight = 0;
                        }
                    }
                    else
                    {
                        sum_midnight = 0;
                        sum_early = 0;
                    }
                }
            }
            this.time_sum_serial = sum - (sum_rest_time + sum_rest_midnight);
            this.time_midnight_sum_serial = sum_midnight + sum_early - sum_rest_midnight;
            this.time_sum_rest_serial = sum_rest_time;
            this.time_midnight_sum_rest_serial = sum_rest_midnight;
            this.time_deduction_serial = sum_deduction;
            
            this.$emit("setParams", {additional_actual_work_time: this.time_sum_serial - this.time_midnight_sum_serial});
            this.$emit("setParams", {additional_midnight_time: this.time_midnight_sum_serial});
            this.$emit("setParams", {additional_break_time: this.time_sum_rest_serial});// ToDoここ多分引いちゃダメ
            this.$emit("setParams", {additional_midnight_break_time: this.time_midnight_sum_rest_serial});// ToDoここ多分引いちゃダメ
            this.$emit("setParams", {deduction_time: this.time_deduction_serial});
            //ToDo 控除時間深夜も追加する
            //配列全部送る
            this.$emit("setParams", {over_time_class_array: this.over_time_class_array_holder});
        },
        //必要な休憩時間
        calcRestTimeNeed(time_sum){
            if(8 * 60 <= time_sum)
            {
                return 60;
            }
            if(6 * 60 <= time_sum)
            {
                return 45;
            }
            //休憩時間不要
            return 0;
        },
        checkEmpty(panel_index){
            const holder = this.over_time_class_array_holder[panel_index];
            let is_input = false;
            is_input |= holder.over_time_class_id != 0;
            is_input |= holder.over_time_start != 0;
            is_input |= holder.over_time_end != 0;
            is_input |= holder.over_time_rest_time != 0;
            is_input |= holder.over_time_midnight_rest_time != 0;
            is_input |= holder.deduction_reason_id != 0;
            is_input |= holder.deduction_time != 0;
            is_input |= holder.deduction_reason.length != 0;
            return !is_input;
        }
    },
    computed:{
        isAddPanalDisplay(){
            return (this.attendance_information.approval_state_id == 2 && this.is_manager)      // 勤怠管理者 申請中 非表示
                ||  (this.attendance_information.approval_state_id == 3 && this.is_manager)     // 勤怠管理者 承認済 非表示
                ||  (this.attendance_information.approval_state_id == 4 && this.is_manager);    // 勤怠管理者 差戻し 非表示
        },
        button_disabled_change(){
            return this.MAX_LENGTH_OVER_TIME_CLASS <= this.over_time_class_array.length || this.attendance_information.work_achievement_id === 9;
        },
        //申請日（申請ない場合は空欄）
        over_time_class_array(){
            if(this.over_time_class_array_holder == null || this.over_time_class_array_holder.length == 0)
            {
                this.over_time_class_array_holder.push({
                    'panel_index': 0,
                    'over_time_class_id': 0,
                    'over_time_start': 0,
                    'over_time_end': 0,
                    'over_time_rest_time': 0,
                    'over_time_rest_time_calc': 0, //calcは自動計算値
                    'over_time_midnight_rest_time': 0,
                    'over_time_midnight_rest_time_calc': 0, //calcは自動計算値
                    'is_auto_input_rest_time': true,
                    'deduction_reason_id': 0,
                    'deduction_time': 0,
                    'deduction_reason': "",
                    'over_time_reason': "",
                    'is_enable_select_start': true, //時間外区分とパネルの状態により変更したい　未実装
                    'is_enable_select_end': true, //時間外区分とパネルの状態により変更したい　未実装
                    'remove': this.onClickRemovePanel,
                    'is_empty': this.checkEmpty,
                    'is_new': true,
                });
            }
            //index修正
            for(let i = 0; i < this.over_time_class_array_holder.length; i++)
            {
                this.over_time_class_array_holder[i].panel_index = i
            }
            return this.over_time_class_array_holder;
        },
        //合計
        time_sum(){
            return this.serialToHoursStr(this.time_sum_serial);
        },
        //内深夜時間
        time_midnight_sum(){
            return this.serialToHoursStr(this.time_midnight_sum_serial);
        },
        //所定時間
        scheduled_time(){
            return this.attendance_information.work_zone ? this.attendance_information.work_zone.actual_work_time : 0;
        },
        //所定深夜
        scheduled_midnight_time(){
            return this.attendance_information.work_zone ? this.attendance_information.work_zone.midnight_actual_work_time : 0;
        },
        //所定休憩時間
        scheduled_rest_time(){
            return this.attendance_information.work_zone ? this.attendance_information.work_zone.break_time : 0;
        },
        //所定休憩時間（内深夜）
        scheduled_rest_time_midnight(){
            return this.attendance_information.work_zone ? this.attendance_information.work_zone.midnight_break_time : 0;
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
    },
    watch: {
    },
    watch: {
        attendance_information: {
            handler(value){
                this.last_come_back_punched_time = value.last_come_back_punched_time ? this.serialToTimeStr(value.last_come_back_punched_time) : '';
                this.last_come_back_punched_time_show = value.employee.field_work == 1;
                this.over_time_class_array_holder = value.over_time_class_array;
                for(let key in this.over_time_class_array_holder)
                {
                    //DB取得のオブジェクトの場合removeとis_emptyが無いので追加
                    if(!this.over_time_class_array_holder[key].is_empty)
                    {
                        this.over_time_class_array_holder[key].remove = this.onClickRemovePanel;
                        this.over_time_class_array_holder[key].is_empty = this.checkEmpty;
                    }
                }
            }
        },
    }
}
</script>