<template>
    <div>
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div class="text-left">
                        カレンダ設定
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="px-3 col-3 text-left">
                    <button style="font-size:11pt;width:150pt" class="btn btn-primary" v-bind:disabled="is_new_calendar_setting" v-on:click="newCalendar()">カレンダ新規作成</button>
                </div>
                <div class="px-3 col-9 text-right">
                    <button style="font-size:11pt;width:100pt" class="btn btn-primary" v-on:click="saveCalendar()">変更を保存</button>
                    <button style="font-size:11pt;width:100pt;margin-left:5pt;" class="btn btn-primary" v-on:click="forgiveCalendar()">変更を破棄</button>
                </div>
            </div>
            <div class="row">
                <div class="p-2 col-12 text-left">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">

                            <div class="row">
                                <div v-if="!is_new_calendar_setting" class="px-3 col-3 text-center">
                                    <select class="form-control" v-model="calendar_id">
                                        <option v-for="calendar in calendarList" :key="calendar.calendar_id" v-bind:value="calendar.calendar_id">{{ calendar.calendar_name }}</option>
                                    </select>
                                </div>
                                <div v-else class="px-3 col-3 text-center">
                                    <label style="cursor: pointer;font-size:16pt;color:#000000;">{{calendar_name}}</label>
                                </div>
                                <div v-if="!is_new_calendar_setting" class="px-3 col-2 text-center">
                                    <input class="form-control" style="font-size:16pt;width:100%" type="number" v-model='calendar_setting_year'>
                                </div>
                                <div v-else class="px-3 col-1 text-right">
                                    <label style="cursor: pointer;font-size:16pt;color:#000000;">{{calendar_setting_year}}</label>
                                </div>
                                <div class="px-3 col-1 text-center">
                                    <label style="cursor: pointer;font-size:16pt;color:#000000;">年度</label>
                                </div>
                            </div>
                            <div v-if="calendar_info.length != 0" style="margin-top:20pt;background-color:#ffffff;padding:5pt">
                                <div class="row">
                                    <div class="px-3 col-12 text-right">
                                        <label style="cursor: pointer;font-size:16pt;color:#000000;">{{workday_of_year_label}}</label>
                                        <label style="cursor: pointer;font-size:16pt;color:#000000;" >{{holiday_of_year_label}}</label>
                                    </div>
                                </div>

                                <div v-for="rows in 4" :key="rows" class="row">
                                    <div v-for="columns in 3" :key="columns" class="px-3 col-4 text-center">
                                        <div class="text-center" style="color:#000000;font-size:15pt">
                                            <label style="cursor: pointer;">{{calendar_year_month_list[(rows - 1) * 3 + columns - 1]}}</label>
                                        </div>
                                        <table class="table-calendar" style="margin-top:10pt;font-size:12pt;">
                                            <thead>
                                                <tr>
                                                    <th>日</th>
                                                    <th>月</th>
                                                    <th>火</th>
                                                    <th>水</th>
                                                    <th>木</th>
                                                    <th>金</th>
                                                    <th>土</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in calendar_setting_list[(rows - 1) * 3 + columns - 1]" :key="item.index">
                                                    <td v-if="item.sunday.day !== null" :class="$style[item.sunday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.sunday)" v-bind:disabled="item.sunday.day === null">{{item.sunday.day}}</td>
                                                    <td v-else :class="$style[item.sunday.background_class]"></td>
                                                    <td v-if="item.monday.day !== null" :class="$style[item.monday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.monday)" v-bind:disabled="item.monday.day === null">{{item.monday.day}}</td>
                                                    <td v-else :class="$style[item.monday.background_class]"></td>
                                                    <td v-if="item.tuesday.day !== null" :class="$style[item.tuesday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.tuesday)" v-bind:disabled="item.tuesday.day === null">{{item.tuesday.day}}</td>
                                                    <td v-else :class="$style[item.tuesday.background_class]"></td>
                                                    <td v-if="item.wednesday.day !== null" :class="$style[item.wednesday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.wednesday)" v-bind:disabled="item.wednesday.day === null">{{item.wednesday.day}}</td>
                                                    <td v-else :class="$style[item.wednesday.background_class]"></td>
                                                    <td v-if="item.thursday.day !== null" :class="$style[item.thursday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.thursday)" v-bind:disabled="item.thursday.day === null">{{item.thursday.day}}</td>
                                                    <td v-else :class="$style[item.thursday.background_class]"></td>
                                                    <td v-if="item.friday.day !== null" :class="$style[item.friday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.friday)" v-bind:disabled="item.friday.day === null">{{item.friday.day}}</td>
                                                    <td v-else :class="$style[item.friday.background_class]"></td>
                                                    <td v-if="item.saturday.day !== null" :class="$style[item.saturday.background_class]" style="cursor: pointer; color: black; text-decoration-color: black;" v-on:click="editCalendar(item.saturday)" v-bind:disabled="item.saturday.day === null">{{item.saturday.day}}</td>
                                                    <td v-else :class="$style[item.saturday.background_class]"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            

                                <div class="row" style="margin-top:20pt;">
                                    <div class="px-3 col-12 text-left">
                                        <button style="font-size:11pt;width:200pt" class="btn btn-primary" v-on:click="setHolidayRest()">祝日を会社休日として反映</button>
                                        <button style="font-size:11pt;width:400pt;margin-left:5pt;" class="btn btn-primary" v-on:click="setWeekend()">土曜日を所定休日 日曜日を法定休日に指定</button>
                                    </div>
                                </div>
                            </div>
                            <div v-else style="background-color:#ffffff;margin-top:20pt;padding:5pt">
                                <div class="text-center" style="color:#000000;font-size:20pt">
                                    カレンダが作成されていません
                                </div>
                                <div class="text-center" style="margin-top:20pt;">
                                    <button style="font-size:11pt;width:200pt" class="btn btn-primary" v-on:click="newSelectedCalendar()">選択した年度のカレンダを作成する</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        "loading":Loading
    },
    data() {
        return {
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            op1:{
                callback_regist: (work_holiday_id)=>{this.m122_callback_regist(work_holiday_id);},
                callback_cancel: ()=>{this.m122_callback_cancel();},
                calendar_date: null,
                work_holiday_id: 0,
            },
            op2:{
                callback_regist: (calendar_name,calendar_setting_year,start_month,copyed_calendar_id,monday_work_holiday_id,tuesday_work_holiday_id,wednesday_work_holiday_id,thursday_work_holiday_id,friday_work_holiday_id,saturday_work_holiday_id,sunday_work_holiday_id,is_holiday_rest)=>{this.m123_callback_regist(calendar_name,calendar_setting_year,start_month,copyed_calendar_id,monday_work_holiday_id,tuesday_work_holiday_id,wednesday_work_holiday_id,thursday_work_holiday_id,friday_work_holiday_id,saturday_work_holiday_id,sunday_work_holiday_id,is_holiday_rest);},
                callback_cancel: ()=>{this.m123_callback_cancel();},
            },
            modalOption_m112: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            calendar_info: [],
            calendar_setting_list: [],
            calendar_setting_year_info: [],
            calendar_year_month_list: [],
            calendarList: [],
            calendar_id: 1,
            calendar_setting_year: 2020,
            old_calendar_setting_year: 2020,
            workday_of_year: 0,
            holiday_of_year: 0,
            select_calendar: [],
            calendar_name: '',
            start_month: 0,
            monday_work_holiday_id: 0,
            tuesday_work_holiday_id: 0,
            wednesday_work_holiday_id: 0,
            thursday_work_holiday_id: 0,
            friday_work_holiday_id: 0,
            saturday_work_holiday_id: 0,
            sunday_work_holiday_id: 0,
            is_holiday_rest: 0,
            is_new_calendar: false,
            is_new_calendar_setting: false,
            holidays: [],
        }
    },
    methods:{
        editCalendar(calendar)
        {
            this.select_calendar = [];
            this.select_calendar = calendar;
            //モーダルを開く
            this.op1.calendar_date = this.serialToDateStr(calendar.calendar_date, "YYYY/MM/DD(A)");
            this.op1.work_holiday_id = calendar.work_holiday_id;
            this.openModal('m122_calendar_setting', '', this.op1)
        },
        newCalendar()
        {
            this.openModal('m123_register_calendar_setting', '', this.op2)
        },
        newSelectedCalendar()
        {
            
            //選択した年度のカレンダを作成する
            for(let i = 0; i < this.calendarList.length; i++){
                if(this.calendarList[i].calendar_id == this.calendar_id){
                    this.start_month = this.calendarList[i].start_month;
                    this.is_holiday_rest = this.calendarList[i].is_holiday_rest;
                    this.monday_work_holiday_id = this.calendarList[i].monday_work_holiday_id;
                    this.tuesday_work_holiday_id = this.calendarList[i].tuesday_work_holiday_id;
                    this.wednesday_work_holiday_id = this.calendarList[i].wednesday_work_holiday_id;
                    this.thursday_work_holiday_id = this.calendarList[i].thursday_work_holiday_id;
                    this.friday_work_holiday_id = this.calendarList[i].friday_work_holiday_id;
                    this.saturday_work_holiday_id = this.calendarList[i].saturday_work_holiday_id;
                    this.sunday_work_holiday_id = this.calendarList[i].sunday_work_holiday_id;
                    this.calendar_name = this.calendarList[i].calendar_name;
                    break;
                }
            }

            this.bulidData();
        },
        //m021からm022データを生成する
        bulidData(){
            this.isLoading = true;
            this.is_new_calendar_setting = true;
            this.calendar_info = [];
            this.calendar_setting_year_info = [];
            this.holiday_of_year = 0;
            this.workday_of_year = 0;
            let year = this.calendar_setting_year;
            let month = this.start_month;
            let next_year = year;
            let next_month = month;

            for(let x = 0; x < 12; x++){
                this.calendar_setting_year_info[x] = [];
                this.calendar_setting_list[x] = [];
                this.calendar_year_month_list[x] = [];
                this.calendar_year_month_list[x] = String(year) + '年' + String(month) + '月';
                if(month == 12){
                    next_month = 1;
                    next_year++;
                }else{
                    next_month++;
                }
                let this_month_date_str = String(year) + '/' + String(month) + '/' + '1';
                let next_month_date_str = String(next_year) + '/' + String(next_month) + '/' + '1';
                let this_month_first_day = this.dateStrToSerial(this.getValidDateStr(this_month_date_str));
                let next_month_first_day = this.dateStrToSerial(this.getValidDateStr(next_month_date_str));
                let first_day_week = this.serialToWeekNumber(this_month_first_day);
                let this_month_index = next_month_first_day - this_month_first_day;

                let calendar_date = 0;
                let work_holiday_id = 0;
                let day = 0;
                let week = 0;

                for(let j = 0; j < 42; j++)
                {
                    if(j < first_day_week || j >= first_day_week + this_month_index){
                        this.calendar_setting_year_info[x].push({
                            'calendar_setting_id': 0,
                            'calendar_date': 0,
                            'work_holiday_id': 0,
                            'day': null,
                            'week': 0,
                            'background_class': this.backgroundColorClass(null),
                        });
                        continue;
                    }

                    calendar_date = this_month_first_day + day;
                    week = this.serialToWeekNumber(calendar_date);
                    switch(week)
                    {
                        case 0: 
                            work_holiday_id = this.sunday_work_holiday_id;
                            break;
                        case 1: 
                            work_holiday_id = this.monday_work_holiday_id;
                            break;
                        case 2: 
                            work_holiday_id = this.tuesday_work_holiday_id;
                            break;
                        case 3: 
                            work_holiday_id = this.wednesday_work_holiday_id;
                            break;
                        case 4: 
                            work_holiday_id = this.thursday_work_holiday_id;
                            break;
                        case 5: 
                            work_holiday_id = this.friday_work_holiday_id;
                            break;
                        case 6: 
                            work_holiday_id = this.saturday_work_holiday_id;
                            break;
                    }

                    this.calendar_setting_year_info[x].push({
                        'calendar_setting_id': 0,
                        'calendar_date': calendar_date,
                        'work_holiday_id': work_holiday_id,
                        'day': day + 1,
                        'week': week,
                        'background_class': this.backgroundColorClass(work_holiday_id),
                    });

                    this.calendar_info.push({
                        'calendar_date': calendar_date,
                        'work_holiday_id': work_holiday_id,
                    });
                    day++;
                }
                for(let y = 0; y < 6; y++){
                    this.calendar_setting_list[x].push({
                        'index': y,
                        'sunday': this.calendar_setting_year_info[x][y * 7],
                        'monday': this.calendar_setting_year_info[x][y * 7 + 1],
                        'tuesday': this.calendar_setting_year_info[x][y * 7 + 2],
                        'wednesday': this.calendar_setting_year_info[x][y * 7 + 3],
                        'thursday': this.calendar_setting_year_info[x][y * 7 + 4],
                        'friday': this.calendar_setting_year_info[x][y * 7 + 5],
                        'saturday': this.calendar_setting_year_info[x][y * 7 + 6],
                    });
                }
                if(month == 12){
                    month = 1;
                    year++;
                }else{
                    month++;
                }
            }
            this.isLoading = false;
            if(this.is_holiday_rest){
                this.setHolidayRest();
            }
            for(let yearIndex = 0; yearIndex < this.calendar_info.length; yearIndex++){
                if(this.calendar_info[yearIndex].work_holiday_id == 2 || this.calendar_info[yearIndex].work_holiday_id == 3){
                    this.holiday_of_year++;
                }else{
                    this.workday_of_year++;
                }
            }
        },
        setWeekend(){
            this.isLoading = true;
            for(let i = 0; i < 12; i++){
                this.calendar_setting_list[i] = [];
                for(let j = 0; j < 42; j++)
                {
                    if(this.calendar_setting_year_info[i][j].day === null){
                        continue;
                    }
                    if(this.calendar_setting_year_info[i][j].week === 6){
                        this.calendar_setting_year_info[i][j].work_holiday_id = 2;
                        this.calendar_setting_year_info[i][j].background_class = this.backgroundColorClass(2);
                        continue;
                    }else if(this.calendar_setting_year_info[i][j].week === 0){
                        this.calendar_setting_year_info[i][j].work_holiday_id = 3;
                        this.calendar_setting_year_info[i][j].background_class = this.backgroundColorClass(3);
                        continue;
                    }
                }
                for(let y = 0; y < 6; y++){
                    this.calendar_setting_list[i].push({
                        'index': y,
                        'sunday': this.calendar_setting_year_info[i][y * 7],
                        'monday': this.calendar_setting_year_info[i][y * 7 + 1],
                        'tuesday': this.calendar_setting_year_info[i][y * 7 + 2],
                        'wednesday': this.calendar_setting_year_info[i][y * 7 + 3],
                        'thursday': this.calendar_setting_year_info[i][y * 7 + 4],
                        'friday': this.calendar_setting_year_info[i][y * 7 + 5],
                        'saturday': this.calendar_setting_year_info[i][y * 7 + 6],
                    });
                }
            }
            for(let x = 0; x < this.calendar_info.length; x++){
                if(this.serialToDateStr(this.calendar_info[x].calendar_date, "A") === '土'){
                    this.calendar_info[x].work_holiday_id = 2;
                    continue;
                }else if(this.serialToDateStr(this.calendar_info[x].calendar_date, "A") === '日'){
                    this.calendar_info[x].work_holiday_id = 3;
                    continue;
                }
            }
            this.isLoading = false;
            this.modalOption_m112.message =  "土曜日を所定休日 日曜日を法定休日に指定しました。";
            this.openModal("m112_common_message", "", this.modalOption_m112);
        },
        setHolidayRest(){
            this.isLoading = true;
            axios.get('getHolidayList', {
                params:{
                    'calendar_setting_year': this.calendar_setting_year,
                    'start_month': this.start_month,
                }
            }).then(response => {

                if(response.data.result)
                {
                    this.holidays = response.data.values.holidays;

                    let holiday_index = 0;

                    for(let i = 0; i < 12; i++){
                        this.calendar_setting_list[i] = [];
                        for(let j = 0; j < 42; j++)
                        {
                            if(this.calendar_setting_year_info[i][j].day === null){
                                continue;
                            }
                            if(holiday_index < this.holidays.length && this.holidays[holiday_index].holiday_date == this.calendar_setting_year_info[i][j].calendar_date){
                                this.calendar_setting_year_info[i][j].work_holiday_id = 2;
                                this.calendar_setting_year_info[i][j].background_class = this.backgroundColorClass(2);
                                holiday_index++;
                                continue;
                            }
                        }
                        for(let y = 0; y < 6; y++){
                            this.calendar_setting_list[i].push({
                                'index': y,
                                'sunday': this.calendar_setting_year_info[i][y * 7],
                                'monday': this.calendar_setting_year_info[i][y * 7 + 1],
                                'tuesday': this.calendar_setting_year_info[i][y * 7 + 2],
                                'wednesday': this.calendar_setting_year_info[i][y * 7 + 3],
                                'thursday': this.calendar_setting_year_info[i][y * 7 + 4],
                                'friday': this.calendar_setting_year_info[i][y * 7 + 5],
                                'saturday': this.calendar_setting_year_info[i][y * 7 + 6],
                            });
                        }
                    }
                    holiday_index = 0;
                    for(let x = 0; x < this.calendar_info.length; x++){
                        if(holiday_index < this.holidays.length && this.holidays[holiday_index].holiday_date == this.calendar_info[x].calendar_date){
                            this.calendar_info[x].work_holiday_id = 2;
                            holiday_index++;
                            continue;
                        }
                    }

                    this.isLoading = false;
                    this.modalOption_m112.message = "祝日を会社休日として反映しました。";
                    this.openModal("m112_common_message", "", this.modalOption_m112);

                }else{
                    this.isLoading = false;
                    this.modalOption_m112.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
            }).catch(error => {
                this.isLoading = false;
                //取得エラー発生。コーション出してリダイレクト
                //alert("不正なアクセスが発生しました。トップへ戻ります。");
                //location.href = '/swk';
            });
        },
        saveCalendar()
        {
            this.isLoading = true;
            if(this.is_new_calendar_setting){

                axios.post('insertCalendar', {
                    calendar_info: this.calendar_info,
                    calendar_setting_year: this.calendar_setting_year,
                    calendar_name: this.calendar_name,
                    start_month: this.start_month,
                    is_holiday_rest: this.is_holiday_rest,
                    monday_work_holiday_id: this.monday_work_holiday_id,
                    tuesday_work_holiday_id: this.tuesday_work_holiday_id,
                    wednesday_work_holiday_id: this.wednesday_work_holiday_id,
                    thursday_work_holiday_id: this.thursday_work_holiday_id,
                    friday_work_holiday_id: this.friday_work_holiday_id,
                    saturday_work_holiday_id: this.saturday_work_holiday_id,
                    sunday_work_holiday_id: this.sunday_work_holiday_id,
                    is_new_calendar: this.is_new_calendar,
                    calendar_id: this.calendar_id,
                }).then(response => {
                    if(response.data.result)
                    {
                        this.updateCalendarList();
                        this.calendar_id = response.data.values.calendar_id;
                        this.isLoading = false;
                        this.modalOption_m112.message =  "保存しました。";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                    else
                    {
                        this.isLoading = false;
                        this.modalOption_m112.message = "保存失敗しました。";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                }).catch(error => {
                    this.isLoading = false;
                    console.log(error.response);
                });

            }else{
                this.isLoading = true;
                axios.post('editCalendar', {
                    calendar_info: this.calendar_info,
                }).then(response => {
                    if(response.data.result)
                    {
                        this.isLoading = false;
                        this.modalOption_m112.message =  "保存しました。";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                    else
                    {
                        this.isLoading = false;
                        this.modalOption_m112.message = "保存失敗しました。";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                }).catch(error => {
                    this.isLoading = false;
                    console.log(error.response);
                });
            }
            
            this.is_new_calendar_setting = false;
            this.is_new_calendar = false;
            this.updateCalendar();
        },
        forgiveCalendar()
        {
            this.modalOption_m112.message =  "破棄しました。";
            this.openModal("m112_common_message", "", this.modalOption_m112);
            this.calendar_setting_year = this.old_calendar_setting_year;
            this.is_new_calendar_setting = false;
            this.is_new_calendar = false;
            this.updateCalendar();
        },
        updateCalendar(){
            this.isLoading = true;
            //新着情報取得
            axios.get('getCalendar', {
                params:{
                    'calendar_id' : this.calendar_id,
                    'calendar_setting_year': this.calendar_setting_year,
                }
            }).then(response => {
                this.calendar_info = [];
                this.calendar_info = response.data.values.calendar_info;
                this.holiday_of_year = 0;
                this.workday_of_year = 0;
                for(let yearIndex = 0; yearIndex < this.calendar_info.length; yearIndex++){
                    if(this.calendar_info[yearIndex].work_holiday_id == 2 || this.calendar_info[yearIndex].work_holiday_id == 3){
                        this.holiday_of_year++;
                    }else{
                        this.workday_of_year++;
                    }
                }
                if(this.calendar_info.length !== 0){
                    for(let x = 0; x < 12; x++){
                        this.calendar_setting_year_info[x] = [];
                        this.calendar_setting_list[x] = [];
                        this.calendar_year_month_list[x] = [];
                        this.calendar_year_month_list[x] = response.data.values.calendar_setting_year_info[x].calendar_setting_year_month;
                        let index = 0;
                        for(let j = 0; j < 42; j++)
                        {
                            if(index >= response.data.values.calendar_setting_year_info[x].calendar_setting_info.length){
                                this.calendar_setting_year_info[x].push({
                                    'calendar_setting_id': 0,
                                    'calendar_date': 0,
                                    'work_holiday_id': 0,
                                    'day': null,
                                    'week': 0,
                                    'background_class': this.backgroundColorClass(null),
                                });
                                continue;
                            }
                            if(j == 0){
                                j = response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].week;
                                for(let i = 0; i < j; i++){
                                    this.calendar_setting_year_info[x].push({
                                        'calendar_setting_id': 0,
                                        'calendar_date': 0,
                                        'work_holiday_id': 0,
                                        'day': null,
                                        'week': 0,
                                        'background_class': this.backgroundColorClass(null),
                                    });
                                }
                            }
                            this.calendar_setting_year_info[x].push({
                                'calendar_setting_id': response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].calendar_setting_id,
                                'calendar_date': response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].calendar_date,
                                'work_holiday_id': response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].work_holiday_id,
                                'day': response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].day,
                                'week': response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].week,
                                'background_class': this.backgroundColorClass(response.data.values.calendar_setting_year_info[x].calendar_setting_info[index].work_holiday_id),
                            });
                            index++;
                        }
                        for(let y = 0; y < 6; y++){
                            this.calendar_setting_list[x].push({
                                'index': y,
                                'sunday': this.calendar_setting_year_info[x][y * 7],
                                'monday': this.calendar_setting_year_info[x][y * 7 + 1],
                                'tuesday': this.calendar_setting_year_info[x][y * 7 + 2],
                                'wednesday': this.calendar_setting_year_info[x][y * 7 + 3],
                                'thursday': this.calendar_setting_year_info[x][y * 7 + 4],
                                'friday': this.calendar_setting_year_info[x][y * 7 + 5],
                                'saturday': this.calendar_setting_year_info[x][y * 7 + 6],
                            });
                        }
                    }
                }
                this.old_calendar_setting_year = this.calendar_setting_year;
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
                //取得エラー発生。コーション出してリダイレクト
                //alert("不正なアクセスが発生しました。トップへ戻ります。");
                //location.href = '/swk';
            });
        },
        m122_callback_regist(work_holiday_id){
            this.isLoading = true;
            for(let i = 0; i < 12; i++){
                this.calendar_setting_list[i] = [];
                for(let j = 0; j < 42; j++)
                {
                    if(this.calendar_setting_year_info[i][j].calendar_date === this.select_calendar.calendar_date){
                        this.calendar_setting_year_info[i][j].work_holiday_id = work_holiday_id;
                        this.calendar_setting_year_info[i][j].background_class = this.backgroundColorClass(work_holiday_id);
                        break;
                    }
                }
                for(let y = 0; y < 6; y++){
                    this.calendar_setting_list[i].push({
                        'index': y,
                        'sunday': this.calendar_setting_year_info[i][y * 7],
                        'monday': this.calendar_setting_year_info[i][y * 7 + 1],
                        'tuesday': this.calendar_setting_year_info[i][y * 7 + 2],
                        'wednesday': this.calendar_setting_year_info[i][y * 7 + 3],
                        'thursday': this.calendar_setting_year_info[i][y * 7 + 4],
                        'friday': this.calendar_setting_year_info[i][y * 7 + 5],
                        'saturday': this.calendar_setting_year_info[i][y * 7 + 6],
                    });
                }
            }
            for(let x = 0; x < this.calendar_info.length; x++){
                if(this.calendar_info[x].calendar_date === this.select_calendar.calendar_date){
                    this.calendar_info[x].work_holiday_id = work_holiday_id;
                    break;
                }
            }
            this.isLoading = false;
        },
        m122_callback_cancel(){
        
        },
        m123_callback_regist(calendar_name,calendar_setting_year,start_month,copyed_calendar_id,monday_work_holiday_id,tuesday_work_holiday_id,wednesday_work_holiday_id,thursday_work_holiday_id,friday_work_holiday_id,saturday_work_holiday_id,sunday_work_holiday_id,is_holiday_rest){
            this.is_new_calendar_setting = true;
            this.is_new_calendar = true;
            this.calendar_name = calendar_name;
            this.old_calendar_setting_year = this.calendar_setting_year;
            this.calendar_setting_year = Number(calendar_setting_year);
            this.start_month = Number(start_month);

            if(copyed_calendar_id == '0'){
                this.is_holiday_rest = 1;
                this.monday_work_holiday_id = 1;
                this.tuesday_work_holiday_id = 1;
                this.wednesday_work_holiday_id = 1;
                this.thursday_work_holiday_id = 1;
                this.friday_work_holiday_id = 1;
                this.saturday_work_holiday_id = 2;
                this.sunday_work_holiday_id = 3;
                this.bulidData();
            }else{
                this.is_holiday_rest = is_holiday_rest ? 1 : 0;
                this.monday_work_holiday_id = monday_work_holiday_id;
                this.tuesday_work_holiday_id = tuesday_work_holiday_id;
                this.wednesday_work_holiday_id = wednesday_work_holiday_id;
                this.thursday_work_holiday_id = thursday_work_holiday_id;
                this.friday_work_holiday_id = friday_work_holiday_id;
                this.saturday_work_holiday_id = saturday_work_holiday_id;
                this.sunday_work_holiday_id = sunday_work_holiday_id;
                this.bulidData();
            }
        },
        m123_callback_cancel(){
        
        },
        backgroundColorClass:function(work_holiday_id){
            if(work_holiday_id === null){
                return "background_gray";
            }
            switch(work_holiday_id)
            {
                case 2: 
                    return "background_blue";
                case 3: 
                    return "background_red";
            }
            //初期状態
            return "background_white";
        },
        getBaseCalendar(){

        },
        updateCalendarList(){
            axios.get('getCalendarList', {
            }).then(response => {
                if(response.data.result){
                    this.calendarList = response.data.values.calendarList;
                    for(let i = 0; i < this.calendarList.length; i++){
                        if(this.calendarList[i].calendar_id == this.calendar_id){
                            this.start_month = this.calendarList[i].start_month;
                            this.is_holiday_rest = this.calendarList[i].is_holiday_rest;
                            this.monday_work_holiday_id = this.calendarList[i].monday_work_holiday_id;
                            this.tuesday_work_holiday_id = this.calendarList[i].tuesday_work_holiday_id;
                            this.wednesday_work_holiday_id = this.calendarList[i].wednesday_work_holiday_id;
                            this.thursday_work_holiday_id = this.calendarList[i].thursday_work_holiday_id;
                            this.friday_work_holiday_id = this.calendarList[i].friday_work_holiday_id;
                            this.saturday_work_holiday_id = this.calendarList[i].saturday_work_holiday_id;
                            this.sunday_work_holiday_id = this.calendarList[i].sunday_work_holiday_id;
                            this.calendar_name = this.calendarList[i].calendar_name;
                            break;
                        }
                    }
                }
            }).catch(error => {
                //取得エラー発生。コーション出してリダイレクト
                //alert("不正なアクセスが発生しました。トップへ戻ります。");
                //location.href = '/swk';
            });
        },
        onCancel() {
            //Loading画面のキャンセル
        },
    },
    computed: {
        workday_of_year_label:function(){
            return "年間所定日数：" + String(this.workday_of_year);
        },
        holiday_of_year_label:function(){
            return "年間所定休日：" + String(this.holiday_of_year);
        },
    },
    
    mounted() {
        this.updateCalendarList();
    },
    watch: {
        calendar_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.calendar_id = value;
                this.updateCalendar();
            }
        },
        calendar_setting_year: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(!this.is_new_calendar_setting){
                    this.calendar_setting_year = value;
                    this.updateCalendar();
                }
            }
        },
    }
}
</script>
<style module> 

.background_white{
    background-color: #ffffff !important;
}
.background_blue{
    background-color: #a7cdff !important;
}
.background_red{
    background-color: #ff8888 !important;
}
.background_gray{
    background-color: #a2a2a2 !important;
}

</style>
