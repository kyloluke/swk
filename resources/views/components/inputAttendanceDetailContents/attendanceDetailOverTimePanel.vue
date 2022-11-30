<template>
    <form name="C105_05_form1" class="pt-1">
        <div class="row">
            <div class="col-sm-12 C105-tblidx" style="text-align: left">時間外時間{{over_time_class.panel_index + 1}}
                <button type="button" class="clear-decoration" style="position: absolute; right: 5px;" v-on:click="onClickButtonDelete" v-bind:disabled="button_disabled_change"><i class="fas fa-times fa-lg" style="color: #fff" data-fa-transform="up-1"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:5px">時間外区分</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend form-group">
                <select class="form-control form-control-sm" v-model="selected_over_time_class_id" v-bind:disabled="select_disabled_change" @change="onChangedOverTimeClass">
                    <option v-for="elm in over_time_class_list" v-bind:value="elm.over_time_class_id" v-bind:key="elm.detail_no">
                        {{elm.over_time_class_name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:22px">時間外時間</div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">開始</div>
                    <div class="col-sm-9 C105-tblcon C105-tblcon-border-rend-t">
                        <div class="row">
                            <div class="col-sm-4" style="padding: 0;">
                                <inputTypeTimeModel v-model="over_time_start" :isEnableInput="input_time_disabled_change" :setFocus="setFocusStartTime"></inputTypeTimeModel>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">終了</div>
                    <div class="col-sm-9 C105-tblcon C105-tblcon-border-rend-t">
                        <div class="row">
                            <div class="col-sm-4" style="padding: 0;">
                                <inputTypeTimeModel v-model="over_time_end" :isEnableInput="input_time_disabled_change"></inputTypeTimeModel>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">時間外事由</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend-t form-group">
                <input id="over_time_reason" type="text" class="form-control form-control-sm" v-model="over_time_reason" v-bind:disabled="select_disabled_change">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:22px">休憩時間</div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">通常</div>
                    <div class="col-sm-9 C105-tblcon C105-tblcon-border-rend-t">
                        <div class="row">
                            <div class="col-sm-4" style="padding: 0;">
                                <inputTypeTimeModel v-model="over_time_rest_time_input" :isEnableInput="rest_time_disabled_change"></inputTypeTimeModel>
                            </div>
                            <div class="col-sm-8" style="padding: 0; top: 5px;">
                                <input type="checkbox" v-model="is_auto_input_rest_time" v-bind:disabled="attendance_information.work_achievement_id === 9"/>
                                <label>自動計算</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">深夜</div>
                    <div class="col-sm-9 C105-tblcon C105-tblcon-border-rend-t">
                        <div class="row">
                            <div class="col-sm-4" style="padding: 0;">
                                <inputTypeTimeModel v-model="over_time_midnight_rest_time_input" :isEnableInput="rest_time_disabled_change"></inputTypeTimeModel>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:8px">控除時間</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend-t">
                <div class="row">
                    <div class="col-sm-4" style="padding: 0;">
                        <inputTypeTimeModel v-model="deduction_time" :isEnableInput="input_time_disabled_change"></inputTypeTimeModel>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:5px">控除事由区分</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend-t form-group">
                <select class="form-control form-control-sm" v-model="selected_deduction_reason_id" v-bind:disabled="select_disabled_change">
                    <option v-for="elm in deduction_reason_list" v-bind:value="elm.deduction_reason_id" v-bind:key="elm.detail_no">
                        {{elm.deduction_reason}}
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:8px">控除事由</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend-tb form-group">
                <input id="deduction_reason" type="text" class="form-control form-control-sm" v-model="deduction_reason" v-bind:disabled="select_disabled_change">
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "attendanceDetailOverTimePanel",
    props: {
        attendance_information: Object,
        is_manager: Boolean,
        over_time_class: Object,
        over_time_rest_time_calc : Number,
        over_time_midnight_rest_time_calc : Number,
    },
    data() {
        return {
            selected_over_time_class_id: 0,
            over_time_start: 0,
            over_time_end: 0,
            selected_deduction_reason_id: 0,
            deduction_time: 0,
            deduction_reason: "",
            over_time_reason: "",
            is_auto_input_rest_time: true,
            over_time_rest_time_input: 0,
            over_time_midnight_rest_time_input: 0,
            setFocusStartTime: false,
        };
    },
    methods:{
        onChangedOverTimeClass: function(){
            //パネル選択時に、時間外か早出かで値固定
            if((this.over_time_start == 0 || this.over_time_start == null) && (this.over_time_end == 0 || this.over_time_end == null))
            {
                //時間外の時は所定終了に固定
                if(this.selected_over_time_class_id == 1)
                {
                    this.over_time_start = this.attendance_information.work_zone_time_end;
                    this.over_time_end = this.attendance_information.work_zone_time_end;
                }
                //早出の時は所定開始に固定
                else if(this.selected_over_time_class_id == 2)
                {
                    this.over_time_start = this.attendance_information.work_zone_time_start;
                    this.over_time_end = this.attendance_information.work_zone_time_start;
                }
            }
            //値変化通知
            this.$emit("setParams", {panel_index: this.panel_index, values:{
                over_time_class_id: this.selected_over_time_class_id,
            }});
            //フォーカス移動
            this.setFocusStartTime = false;
            this.sleep(10).then(()=>{
                this.setFocusStartTime = true;
            })
        },
        onClickButtonDelete: function(){
            //親に削除ボタン押されたことを通知
            this.over_time_class.remove(this.over_time_class.panel_index);
        },
        getWorkZoneTime(time_type_class){
            //勤務帯から取得
            let ret_time = 0;
            let work_zone_times = this.attendance_information.work_zone?.work_zone_time;
            if(!work_zone_times)
            {
                work_zone_times = [];
                //見つからない場合はマスタから
                for(let key in this.getMasterData().work_zone_time)
                {
                    if(this.getMasterData().work_zone_time[key].is_delete)
                    {
                        continue;
                    }
                    if(this.getMasterData().work_zone_time[key].work_zone_id == this.attendance_information.work_zone.work_zone_id)
                    {
                        work_zone_times.push(this.getMasterData().work_zone_time[key]);
                    }
                }
            }
            if(work_zone_times.length == 0)
            {
                //これでも見つからなければ0返す
                return 0;
            }
            for(let work_zone_time in work_zone_times)
            {
                //対象のタイプのみ対象
                if(work_zone_times[work_zone_time].time_type_class == time_type_class)
                {
                    ret_time += work_zone_times[work_zone_time].end_time - work_zone_times[work_zone_time].start_time;
                }
            }
            return ret_time;
        },
    },
    computed:{
        button_disabled_change(){
            return (this.attendance_information.approval_state_id == 2 && this.is_manager)      // 勤怠管理者 申請中は選択不可
                || (this.attendance_information.approval_state_id == 3 && this.is_manager)      // 勤怠管理者 承認済は選択不可
                || (this.attendance_information.approval_state_id == 4 && this.is_manager)      // 勤怠管理者 差戻しは選択不可
                || (this.attendance_information.work_achievement_id === 9);
        },
        select_disabled_change(){
            return !this.is_enable_input || this.attendance_information.work_achievement_id === 9;
        },
        input_time_disabled_change(){
            return this.is_enable_input && this.attendance_information.work_achievement_id !== 9;
        },
        rest_time_disabled_change(){
            return !this.is_auto_input_rest_time && this.attendance_information.work_achievement_id !== 9;
        },
        over_time_class_list(){
            let ret_array = [];
            //未選択
            ret_array.push({
                "over_time_class_id": 0,
                "over_time_class": 1,
                "over_time_class_name": "",
                "detail_no": 0, 
            });
            for(let i = 0; i < this.getMasterData().overtime_class.length; i++)
            {
                //「外勤営業時間外」選択肢が表示するかどうか制御
                if(Object.keys(this.attendance_information).length > 0) {
                    if(this.attendance_information.employee.field_work != 1 && this.getMasterData().overtime_class[i].over_time_class_id == 3)
                    {
                        continue;
                    } 
                }
                
                //削除済み除外
                if(this.getMasterData().overtime_class[i].is_delete === 0)
                {
                    ret_array.push(this.getMasterData().overtime_class[i])
                }
            }
            //detail_noでソート
            ret_array = ret_array.sort(function (a, b) {
                return a.detail_no - b.detail_no;
            });
            return ret_array;
        },
        deduction_reason_list(){
            let ret_array = [];
            //未選択
            ret_array.push({
                "over_time_class_id": 0,
                "over_time_class": 1,
                "over_time_class_name": "",
                "detail_no": 0,
                "deduction_reason_id": 0, 
            });
            for(let i = 0; i < this.getMasterData().deduction_reason.length; i++)
            {
                //削除済み除外
                if(this.getMasterData().deduction_reason[i].is_delete === 0)
                {
                    ret_array.push(this.getMasterData().deduction_reason[i])
                }
            }
            return ret_array;
        },
        //所定休憩時間
        scheduled_rest_time(){
            return this.getWorkZoneTime(2);
        },
        //休憩考えない時間外時間
        over_time_calc(){
            let val = this.over_time_end - this.over_time_start;
            return val;
        },
        //時間外を考慮した必要な休憩時間
        rest_time_calc(){
            const gathered_time = this.over_time_calc + this.getWorkZoneTime(1);
            if(480 <= gathered_time)
            {
                return 60 - this.scheduled_rest_time;
            }
            if(360 <= gathered_time)
            {
                return 45 - this.scheduled_rest_time;
            }
            //休憩時間不要
            return 0;
        },
        is_enable_input(){
            let selected_work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == this.attendance_information.work_zone_id);
            let selected_work_achievement_id = this.getMasterData().work_achievement.find((elm) => elm.work_achievement_id == this.attendance_information.work_achievement_id);
            return !((this.attendance_information.approval_state_id == 2 && this.is_manager)    // 申請中は選択不可
                    || (this.attendance_information.approval_state_id == 3 && this.is_manager)  // 承認済は選択不可
                    || (this.attendance_information.approval_state_id == 4 && this.is_manager)   // 差戻しは選択不可
                    || (selected_work_zone?.work_zone_aggrigation_class == 2) //時給者の時は入力不可
                    || (selected_work_achievement_id?.work_achievement_display_class == 3)); //休日出勤の時は入力不可
        },
    },
    watch: {
        over_time_class: {
            immediate: true,
            handler(value) {
                //既に存在している不就業からの初期値投入
                this.panel_index = value.panel_index;
                this.selected_over_time_class_id = value.over_time_class_id;
                this.over_time_start = value.over_time_start;
                this.over_time_end = value.over_time_end;
                this.selected_deduction_reason_id = value.deduction_reason_id;
                this.deduction_time = value.deduction_time;
                this.deduction_reason = value.deduction_reason;
                this.over_time_reason = value.over_time_reason;
                this.is_auto_input_rest_time = !(value.is_auto_input_rest_time === false);
                this.over_time_rest_time_input = value.over_time_rest_time;
                this.over_time_midnight_rest_time_input = value.over_time_midnight_rest_time;
                this.is_new = value.is_new;
            }
        },
        selected_deduction_reason_id: {
            handler(value){
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    deduction_reason_id: value,
                }});
            }
        },
        deduction_reason: {
            handler(value){
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    deduction_reason: value,
                }});
            }
        },
        over_time_reason: {
            handler(value){
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    over_time_reason: value,
                }});
            }
        },
        is_auto_input_rest_time:{
            handler(value){
                if(value)
                {
                    //自動入力
                    this.over_time_rest_time_input = this.over_time_rest_time_calc
                    this.over_time_midnight_rest_time_input = this.over_time_midnight_rest_time_calc;
                }
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    is_auto_input_rest_time: value,
                }});
            }
        },
        over_time_rest_time_input:{
            handler(value){
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    over_time_rest_time: value,
                }});
            }
        },
        over_time_midnight_rest_time_input:{
            handler(value){
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    over_time_midnight_rest_time: value,
                }});
            }
        },
        over_time_rest_time_calc:{
            handler(value){
                if(this.is_new === undefined)
                {
                    //DBから取得した要素なので値と異なるか判定
                    if(this.over_time_rest_time_input != value)
                    {
                        this.is_auto_input_rest_time =false;
                        this.is_new = false;
                    }
                }
                if(this.is_auto_input_rest_time)
                {
                    //自動入力
                    this.over_time_rest_time_input = value;
                }
            }
        },
        over_time_midnight_rest_time_calc:{
            handler(value){
                if(this.is_new === undefined)
                {
                    //DBから取得した要素なので値と異なるか判定
                    if(this.over_time_midnight_rest_time_input != value)
                    {
                        this.is_auto_input_rest_time =false;
                        this.is_new = false;
                    }
                }
                if(this.is_auto_input_rest_time)
                {
                    //自動入力
                    this.over_time_midnight_rest_time_input = value;
                }
            }
        },
        over_time_start:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                over_time_start: value,
            }});
            }
        },
        over_time_end:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                over_time_end: value,
            }});
            }
        },
        deduction_time:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                deduction_time: value,
            }});
            }
        },
    }
}
</script>