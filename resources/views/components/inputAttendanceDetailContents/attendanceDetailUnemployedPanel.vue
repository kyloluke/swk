<template>
    <form name="C105_04_form1" class="pt-1">
        <div class="row">
            <div class="col-sm-12 C105-tblidx" style="text-align: left">不就業{{unemployed.panel_index + 1}}
                <button type="button" class="clear-decoration" style="position: absolute; right: 5px;" v-on:click="onClickButtonDelete" v-bind:disabled="button_disabled_change"><i class="fas fa-times fa-lg" style="color: #fff" data-fa-transform="up-1"></i></button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:5px">届出内容</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend form-group">
                <select class="form-control form-control-sm" v-model="selected_unemployed_id" v-bind:disabled="select_disabled_change">
                    <option v-for="elm in unemployed_list" v-bind:value="elm.unemployed_id" v-bind:key="elm.detail_no">
                        {{elm.unemployed_name}}
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:22px">不就業時間</div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">開始</div>
                    <div class="col-sm-9 C105-tblcon C105-tblcon-border-rend-t">
                        <div class="row">
                            <div class="col-sm-4" style="padding: 0;">
                                <inputTypeTimeModel v-model="start_time" :isEnableInput="input_start_time_disabled_change" :setFocus="setFocusStartTime"></inputTypeTimeModel>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">終了</div>
                    <div class="col-sm-9 C105-tblcon C105-tblcon-border-rend-t">
                        <div class="row">
                            <div class="col-sm-4" style="padding: 0;">
                                <inputTypeTimeModel v-model="end_time" :isEnableInput="input_end_time_disabled_change"></inputTypeTimeModel>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 C105-tblidx"><br></div>
            <div class="col-sm-5 C105-tblidx C105-tblidx-border-left C105-tblidx-border-top" style="padding-top:7px">申請事由</div>
            <div class="col-sm-6 C105-tblcon C105-tblcon-border-rend-tb form-group">
                <input id="request_reason" type="text" class="form-control form-control-sm" maxlength="50" v-model="request_reason" v-bind:disabled="reason_disabled_change">
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "attendanceDetailUnemployedPanel",
    props: {
        attendance_information: Object,
        is_manager: Boolean,
        unemployed: Object,
    },
    data() {
        return {
            selected_unemployed_id: 0,
            start_time: 0,
            end_time: 0,
            request_reason: '',
            unemployed_take_unit_class: '',
            setFocusStartTime: false,
        };
    },
    methods:{
        onClickButtonDelete: function(){
            //親に削除ボタン押されたことを通知
            this.unemployed.remove(this.unemployed.panel_index);
        },
    },
    computed:{
        button_disabled_change(){
            return this.is_manager || this.attendance_information.work_achievement_id === 9;
        },
        select_disabled_change(){
            return !this.is_enable_select_unemployed || this.attendance_information.work_achievement_id === 9 || this.attendance_information.approval_state_id == 2 && this.is_manager || this.attendance_information.approval_state_id == 3 && this.is_manager || this.attendance_information.approval_state_id == 4 && this.is_manager;
        },
        input_start_time_disabled_change(){
            return this.is_enable_input_start_time && this.attendance_information.work_achievement_id !== 9;
        },
        input_end_time_disabled_change(){
            return this.is_enable_input_end_time && this.attendance_information.work_achievement_id !== 9;
        },
        reason_disabled_change(){
            return !this.is_enable_input_reason || this.attendance_information.work_achievement_id === 9;
        },
        unemployed_list(){
            let ret_array = [];
            //未選択
            ret_array.push({
                "unemployed_id": 0,
                "unemployed_name": "",
                "detail_no": 0, 
            });
            if(this.attendance_information.employee)
            {
                const unemployed_info = this.getMasterData().unemployed;
                const employment_style_info = this.getMasterData().employment_style;
                for(let i = 0; i < unemployed_info.length; i++)
                {
                    //削除済みは除外
                    if(unemployed_info[i].is_delete !== 0){
                        continue;
                    }

                    //直接入力の場合は1日単位のみ表示
                    if(unemployed_info[i].unemployed_take_unit_class !== 1)
                    {
                        //勤務帯が時給タイプかチェック
                        const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id === this.attendance_information.work_zone_id);
                        if(work_zone?.work_zone_aggrigation_class == 2)
                        {
                            continue;
                        }
                        //休日出勤かチェック（振替ありの場合はOK）
                        const work_achievement = this.getMasterData().work_achievement.find((elm) => elm.work_achievement_id == this.attendance_information.work_achievement_id);
                        if(work_achievement?.work_achievement_display_class == 3 )
                        {
                            continue;
                        }
                    }

                    ret_array.push(unemployed_info[i]);
                }
            }
            //リスト内にselected_unemployed_idが存在しない場合はリセット
            if(this.selected_unemployed_id != 0 && ret_array.find((elm) => elm.unemployed_id == this.selected_unemployed_id) == null)
            {
                this.unemployed.remove(this.unemployed.panel_index);
            }
    
            return ret_array;
        },
        //選択中の不就業
        selected_unemployed(){
            for(let key in this.unemployed_list)
            {
                if(this.unemployed_list[key].unemployed_id == this.selected_unemployed_id)
                {
                    return this.unemployed_list[key];
                }
            }
            //見つからない時は最初を返す
            return this.unemployed_list[0];
        },
        //入力可否
        is_enable_select_unemployed(){
            return !((this.attendance_information.approval_state_id==2 && this.is_manager)           // 勤怠管理者 申請中は選択不可
                    || (this.attendance_information.approval_state_id == 3 && this.is_manager)      // 勤怠管理者 承認済は選択不可
                    || (this.attendance_information.approval_state_id == 4 && this.ia_manager));    // 勤怠管理者 差戻しは選択不可
        },
        is_enable_input_start_time(){
            return !((this.attendance_information.approval_state_id==2 && this.is_manager)           // 勤怠管理者 申請中は選択不可
                    || (this.attendance_information.approval_state_id==3 && this.is_manager)        // 勤怠管理者 承認済は選択不可
                    || (this.attendance_information.approval_state_id==4 && this.is_manager)        // 勤怠管理者 差戻しは選択不可
                    || this.selected_unemployed_id == 0 //未選択時は選択不可
                    || this.selected_unemployed.unemployed_take_unit_class == 1); //１日単位の時は入力不可
        },
        is_enable_input_end_time(){
            return !((this.attendance_information.approval_state_id==2 && this.is_manager)          // 勤怠管理者 申請中は選択不可
                    || (this.attendance_information.approval_state_id==3 && this.is_manager)        // 勤怠管理者 承認済は選択不可
                    || (this.attendance_information.approval_state_id==4 && this.is_manager)        // 勤怠管理者 差戻しは選択不可
                    || this.selected_unemployed_id == 0 //未選択時は選択不可
                    || this.selected_unemployed.unemployed_take_unit_class == 1); //１日単位の時は入力不可
        },
        is_enable_input_reason(){
            return !((this.attendance_information.approval_state_id==2 && this.is_manager)
                    || (this.attendance_information.approval_state_id==3 && this.is_manager)
                    || (this.attendance_information.approval_state_id==4 && this.is_manager)); // 承認時は選択不可
        },
    },
    watch: {
        unemployed: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.panel_index = value.panel_index;
                this.selected_unemployed_id = value.unemployed_id;
                this.start_time = value.start_time;
                this.end_time = value.end_time;
                this.request_reason = value.request_reason;
            }
        },
        //ID選択変更
        selected_unemployed_id: {
            handler(value) {
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    unemployed_id: value,
                }});
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    unemployed_name: this.selected_unemployed.unemployed_name,
                }});
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    unemployed_take_unit_class: this.selected_unemployed.unemployed_take_unit_class,
                }});
                //空を選択したときに入力リセット
                if(this.selected_unemployed.unemployed_id == 0)
                {
                    this.start_time = 0;
                    this.end_time = 0;
                    this.request_reason = '';
                }
                if(this.selected_unemployed.unemployed_take_unit_class == 1)
                {
                    //１日単位の時は所定時間挿入
                    this.start_time = this.attendance_information.work_zone_time_start;
                    this.end_time = this.attendance_information.work_zone_time_end;
                }
                else if((this.selected_unemployed.unemployed_take_unit_class == 4 || this.selected_unemployed.unemployed_take_unit_class == 3) && (this.attendance_information.approval_state.approval_state_class == 1)){ //遅刻・早退、かつ、申請状態が初期状態のみ
                    if(this.selected_unemployed.late_early_leave_class == 1){
                        // 遅刻の場合、開始・終了ともに勤務帯の開始時刻で
                        this.start_time = this.attendance_information.work_zone_time_start;
                        this.end_time = this.attendance_information.work_zone_time_start;
                    }
                    if(this.selected_unemployed.late_early_leave_class == 2){
                        // 早退の場合、開始・終了ともに勤務帯の終了時刻で
                        this.start_time = this.attendance_information.work_zone_time_end;
                        this.end_time = this.attendance_information.work_zone_time_end;
                    }
                }                 
                //フォーカス移動
                this.setFocusStartTime = false;
                this.sleep(10).then(()=>{
                    this.setFocusStartTime = true;
                })
            }
        },
        request_reason:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    request_reason: value,
                }});
            }
        },
        unemployed_take_unit_class:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                    unemployed_take_unit_class: value,
                }});
            }
        },
        start_time:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                start_time: value,
            }});
            }
        },
        end_time:{
            handler(value){
                //値変化通知
                this.$emit("setParams", {panel_index: this.panel_index, values:{
                end_time: value,
            }});
            }
        },
    }
}
</script>