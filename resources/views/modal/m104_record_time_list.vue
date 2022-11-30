<template>
    <div id="C104-01" class="modal-content">
        <div class="modal-body">
            <div class="print-area">
                <div class="container-C104-1 text-center pb-3">
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div id="C104-01-01" class="col-sm-6 C104-tblidx">期間</div>
                        <div id="C104-01-03" class="col-sm-2 C104-tblidx C104-tblidx-border-left">締め日区分</div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div id="C104-01-02" class="col-sm-6 C104-tblcon-border">{{m104_record_time_list.target_term_start}}～{{m104_record_time_list.target_term_end}}</div>
                        <div id="C104-01-04" class="col-sm-2 C104-tblcon-border-rend">{{m104_record_time_list.close_date_name}}</div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div id="C104-01-05" class="col-sm-2 C104-tblidx">社員番号</div>
                        <div id="C104-01-07" class="col-sm-3 C104-tblidx C104-tblidx-border-left">名前</div>
                        <div id="C104-01-09" class="col-sm-3 C104-tblidx C104-tblidx-border-left">所属</div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div id="C104-01-06" class="col-sm-2 C104-tblcon-border">{{m104_record_time_list.employee_code}}</div>
                        <div id="C104-01-08" class="col-sm-3 C104-tblcon-border">{{m104_record_time_list.employee_name}}</div>
                        <div id="C104-01-10" class="col-sm-3 C104-tblcon-border-rend">{{m104_record_time_list.office_name}}</div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>

                <div class="container-C104-1 text-center">
                    <div class="row">
                        <div class="col-sm-1"></div>
                        <div id="C104-01-11" class="col-sm-1 C104-tblidx" style="padding-top:14px">日付</div>
                        <div id="C104-01-12" class="col-sm-1 C104-tblidx C104-tblidx-border-left" style="padding-top:14px">実績</div>
                        <div id="C104-01-13" class="col-sm-2 C104-tblidx C104-tblidx-border-left" style="padding-top:14px">不就業</div>
                        <div id="C104-01-14" class="col-sm-2 C104-tblidx C104-tblidx-border-left" style="padding-top:14px">勤務帯</div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div id="C104-01-15" class="col-sm-12 C104-tblidx C104-tblidx-border-left">打刻
                                    <div class="row">
                                        <div id="C104-01-16" class="col-sm-3 C104-tblidx C104-tblidx-border-top">出勤</div>
                                        <div id="C104-01-17" class="col-sm-3 C104-tblidx C104-tblidx-border-top C104-tblidx-border-left">外出</div>
                                        <div id="C104-01-18" class="col-sm-3 C104-tblidx C104-tblidx-border-top C104-tblidx-border-left">戻り</div>
                                        <div id="C104-01-19" class="col-sm-3 C104-tblidx C104-tblidx-border-top C104-tblidx-border-left">退勤</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="C104-01-20" class="container-C104-1 text-center">
                    <!-- ここから日付分繰り返し -->
                    <div class="row" v-for="item in punch_clock_data_list" :key="item.date_str">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-1 C104-tblcon-border" v-bind:class="item.isOff ? $style.list_background_offday : ''" v-html="item.date_str"></div>
                        <div class="col-sm-1 C104-tblcon-border" v-bind:class="item.isOff ? $style.list_background_offday : ''" v-html="item.work_achivement_name"></div>
                        <div class="col-sm-2 C104-tblcon-border" v-bind:class="item.isOff ? $style.list_background_offday : ''" v-html="item.unemployed_name"></div>
                        <div class="col-sm-2 C104-tblcon-border" v-bind:class="item.isOff ? $style.list_background_offday : ''" v-html="item.work_zone_name"></div>
                        <div class="col-sm-1 C104-tblcon-border" v-bind:class="item.isOff ? $style.list_background_offday : ''">
                            <div><span v-if="item.web_punches[0][0] !== 0" v-html="serialToTimeStr(item.web_punches[0][0])"></span></div>
                            <div v-if="!isChangeMode"><span v-if="item.input_punches[0][0] !== null" v-html="serialToTimeStr(item.input_punches[0][0]) + '※'"></span></div>
                            <div v-else><span><inputTypeTimeModel v-model="item.input_punches[0][0]" :isEnableInput="item.isEnable"></inputTypeTimeModel></span></div>
                        </div>
                        <div class="col-sm-1 C104-tblcon-border-nakanuke" v-bind:class="item.isOff ? $style.list_background_offday : ''"><div v-for="punch in item.web_punches[2]" :key="punch" ><span v-if="punch !== 0" v-html="serialToTimeStr(punch)"></span></div></div>
                        <div class="col-sm-1 C104-tblcon-border-nakanuke" v-bind:class="item.isOff ? $style.list_background_offday : ''"><div v-for="punch in item.web_punches[3]" :key="punch" ><span v-if="punch !== 0" v-html="serialToTimeStr(punch)"></span></div></div>
                        <div class="col-sm-1 C104-tblcon-border-taikin" v-bind:class="item.isOff ? $style.list_background_offday : ''">
                            <div><span v-if="item.web_punches[1][0] !== 0" v-html="serialToTimeStr(item.web_punches[1][0])"></span></div>
                            <div v-if="!isChangeMode"><span v-if="item.input_punches[1][0] !== null" v-html="serialToTimeStr(item.input_punches[1][0]) + '※'"></span></div>
                            <div v-else><span><inputTypeTimeModel v-model="item.input_punches[1][0]" :isEnableInput="item.isEnable"></inputTypeTimeModel></span></div>
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                    <!-- ここまで日付分繰り返し -->
                </div>
            </div> <!-- print-area -->
      　    <div v-if="message.length != 0" style="margin-top: 20px; font-size: 1.2rem; height: 40px; line-height: 40px;" class="message-group row ml-1 mr-1 mb-3">
                <div id="C106-01-20" class="error-message text-center col-sm-12">
                    <div>{{message}}</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center pb-0">
                <button id="C104-01-21" type="button" class="btn btn-primary w-15" :class="changeButtonTextTypeClass" style="margin-right: 60px" v-on:click="changeMode" v-text="changeButtonText" :disabled="is_disabled_punch_btn"></button>
                <button id="C104-01-21" type="button" class="btn btn-primary w-15" style="margin-right: 120px" :disabled="isChangeMode" v-on:click="printM104">印刷</button>
                <button id="C104-01-22" type="button" class="btn btn-success w-15" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "M104data",
    props: ['op1','id'],
    data() {
        return {
                m104_record_time_list: [],
                punch_clock_data_list: [],
                isChangeMode: false,
                message: "",
                is_disabled_punch_btn: false,
                modal: {
                    message: '',
                    buttons:[{
                            exec : ()=>{
                                this.btn="OK"; //ボタンが押された時の処理をここに記載
                                //モーダルを閉じる
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-success"
                        }],
                },
            };
    },
    methods: {
        printM104() {
            //プリントしたいエリアの取得
            var printArea = document.getElementsByClassName("print-area");

            //プリント用の要素「#print」を作成し、上で取得したprintAreaをその子要素に入れる。
            $('body').append('<div id="print" class="printBc"></div>');
            $(printArea).clone().appendTo('#print');

            //プリントしたいエリア以外に、非表示のcssが付与されたclassを追加
            $('body > :not(#print)').addClass('print-off');

            //モーダルの背景削除＆閉じる
            $('.modal-backdrop').remove();
            $('#'+this.id).modal('hide');

            window.print();

            //window.print()を実行した後、作成した「#print」と、非表示用のclass「print-off」を削除
            $('#print').remove();
            $('.print-off').removeClass('print-off');
        },
        changeMode() {
            if(this.isChangeMode){
                this.message = "";
                for(let i = 0; i < this.punch_clock_data_list.length; i++)
                {
                    //入力不備がある場合はreturn 
                    if(this.punch_clock_data_list[i].input_punches[0][0] == -1 || this.punch_clock_data_list[i].input_punches[1][0] == -1)
                    {
                        this.message = "入力に不備があります";
                        return;
                    }
                }
                axios.post('insert_update_input_punches', {
                    params: {
                        punchClockList: this.punch_clock_data_list,
                        employeeID: this.op1.employee_id
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.isChangeMode = false;
                        this.modal.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modal);
                        //this.op1.updateViolationWarningId();
                        this.op1.reflectChange();
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
                this.isChangeMode = true;
            }
        },
    },
    mounted() {
        axios.get('m104_record_time_list', {
            //年月を6桁で送信
            params:{
                'term' : this.op1.year_month,
                'employeeID' : this.op1.employee_id,
            }
        }).then(response => {
            if(response.data.result)
            {
                //全ての勤務情報はもう締めの状態であれば、「手入力」ボタンをdisabledにする
                let is_attendance_info_all_closed = response.data.values.attendance_information_data.every(function(value,index) {
                    return value.close_state_id > 2;
                })
                this.is_disabled_punch_btn = this.op1.isManager || is_attendance_info_all_closed || response.data.values.is_company_closed || response.data.values.is_office_closed;

                //プロパティにセット
                this.m104_record_time_list = response.data.values;
                const puche_array = this.m104_record_time_list.puch_clock_data;
                //対象期間の日付分のListを作る
                for(let i = 0; i < this.term_serial_array.length; i++)
                {
                    //WEB打刻時間(4種)
                    const web_punches = [[0], [0], [0], [0]];
                    //手入力打刻時間(2種)
                    const input_punches = [[null], [null]];
                    //手入力打刻時間(2種)(旧データ保存用)
                    const old_input_punches = [[null], [null]];

                    const target_panches = puche_array.filter(element => element.punch_clock_date == this.term_serial_array[i]);
                    
                    if(0 < target_panches.length)
                    {
                        for(const index in target_panches)
                        {
                            const punch = target_panches[index];
                            const punch_id = punch.clocking_in_out_id;
                            const input_class = punch.input_class;
                            if(input_class == 0){
                                switch(punch_id)
                                {
                                    //打刻種別1は先勝ち
                                    case 1:
                                        //値が無いか、早い時間の場合は値セット
                                        if(!web_punches[punch_id - 1][0] || web_punches[punch_id - 1][0] > punch.punch_clock_time)
                                        {
                                            web_punches[punch_id - 1][0] = punch.punch_clock_time;
                                        }
                                        break;
                                    //打刻種別2は後勝ち
                                    case 2:
                                        //値が無いか、遅い時間の場合は値セット
                                        if(!web_punches[punch_id - 1][0] || web_punches[punch_id - 1][0] < punch.punch_clock_time)
                                        {
                                            web_punches[punch_id - 1][0] = punch.punch_clock_time;
                                        }
                                        break;
                                        
                                    //打刻種別3,4はすべて
                                    case 3:
                                    case 4:
                                        //配列で値セット
                                        web_punches[punch_id - 1].push(punch.punch_clock_time);
                                        break;
                                }
                            }else{
                                input_punches[punch_id - 1][0] = punch.punch_clock_time;
                                old_input_punches[punch_id - 1][0] = punch.punch_clock_time;
                            }
                        }
                    }

                    this.punch_clock_data_list.push({
                        'date_str': this.term_days_array[i], //日付
                        'date_serial': this.term_serial_array[i], //日付シリアル
                        'work_achivement_name': this.term_dayinfo_array[this.term_serial_array[i]].work_achivemetn_name,
                        'unemployed_name': this.term_dayinfo_array[this.term_serial_array[i]].unemployed_name,
                        'work_zone_name': this.term_dayinfo_array[this.term_serial_array[i]].work_zone_name,
                        'web_punches': web_punches, //WEB打刻
                        'old_input_punches': old_input_punches,
                        'input_punches': input_punches, //手入力打刻
                        'isOff': this.term_dayinfo_array[this.term_serial_array[i]].isOff, //休日フラグ
                        //手打刻できるのは過去の日 && 承認済みではない場合のみ
                        'isEnable': this.term_serial_array[i] <= this.todaySerial() && this.m104_record_time_list.attendance_information_data[i].approval_state_id != 3,
                    });
                }
            }
            else
            {
                //取得失敗
            }
        })
    },
    computed: {
        //対象期間のシリアル値
        term_serial_array: function(){
            const ret_array = new Array();
            for(let i = this.m104_record_time_list.target_term_start_serial; i <= this.m104_record_time_list.target_term_end_serial; i++)
            {
                ret_array.push(i);
            }
            return ret_array;
        },
        //対象期間の日付文字列
        term_days_array: function(){
            const ret_array = new Array();
            for(let i = this.m104_record_time_list.target_term_start_serial; i <= this.m104_record_time_list.target_term_end_serial; i++)
            {
                ret_array.push(this.serialToDateStr(i, "MM/DD(A)"));
            }
            return ret_array;
        },
        term_dayinfo_array: function(){
            const ret_array = new Array();
            for(let i = 0; i < this.m104_record_time_list.attendance_information_data.length; i++)
            {
                const info = this.m104_record_time_list.attendance_information_data[i];
                const dateSerial = info.attendance_date;
                let work_achivemetn_name = this.m104_record_time_list.work_achivement_names[info.work_achievement_id];
                if(info.work_achievement_id === 0 && (info.work_holiday_id === 2 || info.work_holiday_id === 3)){
                    work_achivemetn_name = '会社休日';
                }
                const unemployed_take_unit_class = this.m104_record_time_list.unemployed_take_unit_classes[info.unemployed_id];
                let isOff = false;
                if((info.work_achievement_id === 0 && (info.work_holiday_id === 2 || info.work_holiday_id === 3)) || info.work_achievement_id === 8 || info.work_achievement_id === 9 || unemployed_take_unit_class === 1){
                    isOff = true;
                }
                const work_zone_name = this.m104_record_time_list.workzone_names[info.work_zone_id];
                const unemployed_name = this.m104_record_time_list.unemployed_names[info.unemployed_id];
                ret_array[dateSerial] = {'work_achivemetn_name': work_achivemetn_name, 'work_zone_name': work_zone_name, 'unemployed_name': unemployed_name, 'isOff': isOff};
            }
            return ret_array;
        },
        changeButtonText: function(){
            return this.isChangeMode ? '手入力登録': '手入力';
        },
        changeButtonTextTypeClass:function(){
            return this.isChangeMode ? "btn-success" : "btn-primary";
        },
    }
}
</script>
<style module> 
.list_background_offday{
    background-color: #f5d9af !important;
}

</style>