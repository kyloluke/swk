<template>
    <div class="card-body">
        <div class="container-C105-1">
            <div class="row">
                <div id="C105-06-02" class="col-sm-4 C105-tblidx" style="padding-top:10px">日付</div>
                <div class="col-sm-8">
                    <div class="row">
                        <div id="C105-06-03" class="col-sm-12 C105-tblidx C105-tblidx-border-left">打刻
                            <div class="row">
                                <div id="C105-06-04" class="col-sm-6 C105-tblidx C105-tblidx-border-top">時刻</div>
                                <div id="C105-06-05" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">区分</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-for="item in web_punch" :key="item.web_punch_key">
                <div class="col-sm-4 C105-tblcon C105-tblcon-border" v-bind:class="item.is_today ? $style.list_background_today : ''">{{item.punch_clock_date}}</div>
                <div class="col-sm-4 C105-tblcon C105-tblcon-border" v-bind:class="item.is_today ? $style.list_background_today : ''">{{item.punch_clock_time}}</div>
                <div class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b" v-bind:class="item.is_today ? $style.list_background_today : ''">{{item.clocking_in_out_name}}</div>
            </div>
            <div class="row" style="padding-top:10px">
                <button style="font-size:11pt;width:130pt;" class="btn" :class="inputPunchbuttonTypeClass" v-on:click="inputPunchMode()" v-text="inputPunchText" v-bind:disabled="button_disabled_change"></button>
                <span v-if="!isInputPunchMode">
                    <button style="font-size:11pt;width:130pt;margin-left:20px;" class="btn btn-danger ml-3" v-on:click="cancelMode()">手入力登録キャンセル</button>
                </span>
            </div>
            <div v-if="!isInputPunchMode" class="row" style="padding-top:10px">
                <div id="C105-06-02" class="col-sm-4 C105-tblidx" style="padding-top:10px">日付</div>
                <div class="col-sm-8">
                    <div class="row">
                        <div id="C105-06-03" class="col-sm-12 C105-tblidx C105-tblidx-border-left">手入力打刻
                            <div class="row">
                                <div id="C105-06-04" class="col-sm-6 C105-tblidx C105-tblidx-border-top">出勤</div>
                                <div id="C105-06-05" class="col-sm-6 C105-tblidx C105-tblidx-border-top C105-tblidx-border-left">退勤</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!isInputPunchMode" class="row">
                <div class="col-sm-4 C105-tblcon C105-tblcon-border" style="padding-top:5px">{{today_punch_clock_date}}</div>
                <div class="col-sm-4 C105-tblcon C105-tblcon-border" style="padding-left:50px"><inputTypeTimeModel v-model="today_punch_clock_time_in" :setFocus="setFocusStartTime"></inputTypeTimeModel></div>
                <div class="col-sm-4 C105-tblcon C105-tblcon-border-rend-b" style="padding-left:50px"><inputTypeTimeModel v-model="today_punch_clock_time_out"></inputTypeTimeModel></div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "attendanceDetailWebPunch",
    props: {
        session_data: Object,
        attendance_information: Object,
        is_manager: Boolean,
    },
    data() {
        return {
            isInputPunchMode: true,
            today_punch_clock_date: "",
            today_punch_clock_time_in: null,
            today_punch_clock_time_out: null,
            old_today_punch_clock_time_in: null,
            old_today_punch_clock_time_out: null,
            modal: {
                message: '',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            setFocusStartTime: false,
        };
    },
    methods:{
        inputPunchMode(){
            if(!this.isInputPunchMode){
                //入力不備がある場合はreturn 
                if(this.today_punch_clock_time_in == -1 || this.today_punch_clock_time_out == -1)
                {
                    //this.message = "入力に不備があります";
                    return;
                }
                axios.post('insert_update_today_input_punch', {
                    params: {
                        today_punch_clock_date: this.attendance_information.attendance_date,
                        today_punch_clock_time_in: this.today_punch_clock_time_in,
                        today_punch_clock_time_out: this.today_punch_clock_time_out,
                        old_today_punch_clock_time_in: this.old_today_punch_clock_time_in,
                        old_today_punch_clock_time_out: this.old_today_punch_clock_time_out,
                        employee_id: this.attendance_information.employee_id
                    }
                }).then(response => {
                    if(response.data.result)
                    {

                        const web_punch_array = this.attendance_information.web_punch_clock_list;
                        let ret_array = [];
                        //表示の開始日
                        const target_term_start_serial = this.attendance_information.attendance_date - 1;
                        //表示の終了日
                        const target_term_end_serial = this.attendance_information.attendance_date + 1;
                        
                        for(let target_date = target_term_start_serial; target_date <= target_term_end_serial; target_date++)
                        {
                            //対象日が当日かどうか（背景表示に使用）
                            const is_today = target_date === this.attendance_information.attendance_date; 
                            //対象日の表示形式
                            const punch_clock_date = this.serialToDateStr(target_date, "YYYY/MM/DD(A)");
                            //対象日の値を取得
                            const one_day_array =  web_punch_array.filter(item => item.punch_clock_date === target_date);
                            let is_inputed_in = false;
                            let is_inputed_out = false;
                            if(one_day_array.length > 0)
                            {
                                //打刻があった場合はすべて表示
                                for(let web_punch in one_day_array)
                                {
                                    if(one_day_array[web_punch].input_class == 1 && is_today){
                                        if((one_day_array[web_punch].clocking_in_out_id == 1 && this.today_punch_clock_time_in !== null) || (one_day_array[web_punch].clocking_in_out_id == 2 && this.today_punch_clock_time_out !== null)){
                                            ret_array.push({
                                                clocking_in_out_id : one_day_array[web_punch].clocking_in_out_id,
                                                input_class: one_day_array[web_punch].input_class,
                                                punch_clock_date: target_date,
                                                punch_clock_time: one_day_array[web_punch].clocking_in_out_id == 1 ? this.today_punch_clock_time_in : this.today_punch_clock_time_out,
                                            });
                                            if(one_day_array[web_punch].clocking_in_out_id == 1){
                                                is_inputed_in = true;
                                            }else{
                                                is_inputed_out = true;
                                            }
                                        }
                                    }else{
                                        ret_array.push({
                                            clocking_in_out_id : one_day_array[web_punch].clocking_in_out_id,
                                            input_class: one_day_array[web_punch].input_class,
                                            punch_clock_date: target_date,
                                            punch_clock_time: one_day_array[web_punch].punch_clock_time,
                                        });
                                    }
                                }
                            }
                            if(is_today){
                                if(this.today_punch_clock_time_in !== null && !is_inputed_in){
                                    ret_array.push({
                                        clocking_in_out_id : 1,
                                        input_class: 1,
                                        punch_clock_date: target_date,
                                        punch_clock_time: this.today_punch_clock_time_in,
                                    });
                                }
                                if(this.today_punch_clock_time_out !== null && !is_inputed_out){
                                    ret_array.push({
                                        clocking_in_out_id : 2,
                                        input_class: 1,
                                        punch_clock_date: target_date,
                                        punch_clock_time: this.today_punch_clock_time_out,
                                    });
                                }
                                is_inputed_in = false;
                                is_inputed_out = false;
                            }
                        }

                        this.$emit("setParams", {web_punch_clock_list: ret_array});
                        this.attendance_information.web_punch_clock_time_start = this.today_punch_clock_time_in;
                        this.attendance_information.web_punch_clock_time_end = this.today_punch_clock_time_out;
                        this.isInputPunchMode = true;
                        this.modal.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modal);
                    }
                    else
                    {
                        //選択がない状態でリクエストなど行われた場合
                        this.modal.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modal);
                    }
                }).catch(error => {
                    console.log(error.response);
                });
            }else{
                this.isInputPunchMode = false;
                //フォーカス移動
                this.setFocusStartTime = false;
                this.sleep(10).then(()=>{
                    this.setFocusStartTime = true;
                })
            }
        },
        cancelMode(){
            this.isInputPunchMode = true;
        },
    },
    computed:{
        button_disabled_change(){
            return this.attendance_information.work_achievement_id === 9
                || this.attendance_information.attendance_date > this.todaySerial()
                || this.attendance_information.approval_state_id == 3           // 承認済は選択不可
                || this.attendance_information.approval_state_id == 2 && this.is_manager    // 勤怠管理者 申請中は選択不可
                || this.attendance_information.approval_state_id == 4 && this.is_manager    // 勤怠管理者 差戻しは選択不可
                || this.attendance_information.is_office_closed
                || this.attendance_information.is_company_closed;
        },
        web_punch(){
            const web_punch_array = this.attendance_information.web_punch_clock_list;
            let ret_array = [];
            //表示の開始日
            const target_term_start_serial = this.attendance_information.attendance_date - 1;
            //表示の終了日
            const target_term_end_serial = this.attendance_information.attendance_date + 1;
            const clocking_in_out = this.session_data.master_data.clocking_in_out;
            //v-forのループ用key
            let web_punch_key = 0;
            
            for(let target_date = target_term_start_serial; target_date <= target_term_end_serial; target_date++)
            {
                //対象日が当日かどうか（背景表示に使用）
                const is_today = target_date === this.attendance_information.attendance_date; 
                //対象日の表示形式
                const punch_clock_date = this.serialToDateStr(target_date, "YYYY/MM/DD(A)");
                //対象日の値を取得
                const one_day_array =  web_punch_array.filter(item => item.punch_clock_date === target_date);
                if(one_day_array.length === 0)
                {
                    if(is_today){
                        this.today_punch_clock_date = punch_clock_date;
                        this.today_punch_clock_time_in = null;
                        this.today_punch_clock_time_out = null;
                        this.old_today_punch_clock_time_in = null;
                        this.old_today_punch_clock_time_out = null;
                    }
                    //打刻がない場合、日付のみ表示
                    ret_array.push({
                        web_punch_key : web_punch_key,
                        punch_clock_date: punch_clock_date,
                        punch_clock_time: "-",
                        clocking_in_out_name: "-",
                        is_today: is_today,
                    });
                    web_punch_key++;
                }
                else
                {
                    //打刻があった場合はすべて表示
                    for(let web_punch in one_day_array)
                    {
                        if(is_today){
                            this.today_punch_clock_date = punch_clock_date;
                            if(one_day_array[web_punch].input_class == 1){
                                if(one_day_array[web_punch].clocking_in_out_id == 1){
                                    this.today_punch_clock_time_in = one_day_array[web_punch].punch_clock_time;
                                    this.old_today_punch_clock_time_in = one_day_array[web_punch].punch_clock_time;
                                }else if(one_day_array[web_punch].clocking_in_out_id == 2){
                                    this.today_punch_clock_time_out = one_day_array[web_punch].punch_clock_time;
                                    this.old_today_punch_clock_time_out = one_day_array[web_punch].punch_clock_time;
                                }
                            }
                        }
                        ret_array.push({
                        web_punch_key : web_punch_key,
                            punch_clock_date: punch_clock_date,
                            punch_clock_time: one_day_array[web_punch].input_class == 1? this.serialToTimeStr(one_day_array[web_punch].punch_clock_time) + "※" : this.serialToTimeStr(one_day_array[web_punch].punch_clock_time),
                            clocking_in_out_name: clocking_in_out.find((elm)=> elm.clocking_in_out_id === one_day_array[web_punch].clocking_in_out_id).clocking_in_out_name,
                            is_today: is_today,
                        });
                        web_punch_key++;
                    }
                }
            }
            return ret_array;
        },

        inputPunchText: function(){
            return this.isInputPunchMode ? '手入力': '手入力登録';
        },
        inputPunchbuttonTypeClass:function(){
            return this.isInputPunchMode ? "btn-success" : "btn-primary";
        },
    },
    watch: {
    },
}
</script>
<style module> 
.list_background_today{
    background-color: #f5d9af !important;
}

</style>