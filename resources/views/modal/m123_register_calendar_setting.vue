<template>
    <div class="modal-content" id="C123-01" style="width: 500px">
        <div class="modal-body">
            <div id="C123-01-01" class="mb-3 d-flex justify-content-center">カレンダ新規作成</div>

            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <label id="C123-01-02" style="cursor: pointer;">カレンダ名称</label>
                </div>
                <div class="form-group" style="width: 160px;">
                    <input id="C123-01-03" type="text" class="form-control" v-model="calendar_name"/>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <label id="C123-01-04" style="cursor: pointer;">対象年度</label>
                </div>
                <div class="form-group" style="width: 80px;">
                    <input id="C123-01-05" type="number" class="form-control" v-model="calendar_setting_year"/>
                </div>
                <div class="form-group" style="width: 80px;margin-left: 5px">
                    <label style="cursor: pointer;">年</label>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <label id="C123-01-06" style="cursor: pointer;">カレンダ開始月</label>
                </div>
                <div class="form-group" style="width: 80px;">
                    <input id="C123-01-07" type="number" class="form-control" v-model="start_month"/>
                </div>
                <div class="form-group " style="width: 80px;margin-left: 5px">
                    <label style="cursor: pointer;">月</label>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <label id="C123-01-08" style="cursor: pointer;">複製元カレンダ</label>
                </div>
                <div class="form-group" style="width: 160px;">
                    <select id="C123-01-09" class="form-control" v-model="selectedCalendar">
                        <option value=0>基本</option>
                        <option v-for="calendar in calendarList" :key="calendar.calendar_id" v-bind:value="calendar.calendar_id">{{ calendar.calendar_name }}</option>
                    </select>
                </div>
            </div>
            <div v-if="selectedCalendar !== '0'" class="row d-flex justify-content-center">
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label id="C123-01-10" style="cursor: pointer;">休日設定</label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-11" style="cursor: pointer;">月</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-12" class="form-control" v-model="monday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-13" style="cursor: pointer;">火</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-14" class="form-control" v-model="tuesday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-15" style="cursor: pointer;">水</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-16" class="form-control" v-model="wednesday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-17" style="cursor: pointer;">木</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-18" class="form-control" v-model="thursday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-19" style="cursor: pointer;">金</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-20" class="form-control" v-model="friday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-21" style="cursor: pointer;">土</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-22" class="form-control" v-model="saturday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <label id="C123-01-23" style="cursor: pointer;">日</label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <select id="C123-01-24" class="form-control" v-model="sunday_work_holiday_id">
                            <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="form-group" style="width: 160px;">
                        <label style="cursor: pointer;"></label>
                    </div>
                    <div class="form-group" style="width: 140px;">
                        <label id="C123-01-25" style="cursor: pointer;">祝日を所定休日とする</label>
                    </div>
                    <div class="form-group" style="width: 20px;">
                        <input id="C123-01-26" type="checkbox" class="form-control" v-model="is_holiday_rest"/>
                    </div>
                </div>
            </div>
            <div class="message-group row ml-1 mr-1 pt-3">
                <div id="C123-01-26" class="error-message text-center col-sm-12">
                    <div v-for="(item, i) in errorMessageArray" :key="i">{{item}}</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C123-01-27" type="button" class="btn btn-primary w-35" style="margin-right: 40px" v-on:click="updateClick">登録</button>
                <button id="C123-01-28" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            selectedCalendar: '0',
            work_holiday_list: [],
            calendarList: [],
            monday_work_holiday_id: 1,
            tuesday_work_holiday_id: 1,
            wednesday_work_holiday_id: 1,
            thursday_work_holiday_id: 1,
            friday_work_holiday_id: 1,
            saturday_work_holiday_id: 2,
            sunday_work_holiday_id: 3,
            calendar_name: '',
            calendar_setting_year: 0,
            start_month: 0,
            errorMessageArray: [],
            isError: false,
            is_holiday_rest: 1,
        }
    },
    methods: {
        updateClick() {
            if(this.validate())
            {
                

                this.message = "";
                this.op1.callback_regist(this.calendar_name,this.calendar_setting_year,this.start_month,this.selectedCalendar,this.monday_work_holiday_id,this.tuesday_work_holiday_id,this.wednesday_work_holiday_id,this.thursday_work_holiday_id,this.friday_work_holiday_id,this.saturday_work_holiday_id,this.sunday_work_holiday_id,this.is_holiday_rest);
                //モーダルを閉じる
                $('body').removeClass('modal-open');
                this.cleanModal();
                $('.modal-backdrop').remove();
                $('#' + this.id).modal('hide');
            }
        },
        //キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
        validate(){
            this.isError = false;
            this.errorMessageArray = [];
            //入力文字数
            if(this.calendar_name == '')
            {
                this.errorMessageArray.push("カレンダ名称を入力してください");
                this.isError = true;
            }
            if(20 < this.calendar_name.length)
            {
                this.errorMessageArray.push("カレンダ名称は20文字以内で入力してください");
                this.isError = true;
            }
            if(1900 > this.calendar_setting_year)
            {
                this.errorMessageArray.push("対象年度を1900年以降に指定してください。");
                this.isError = true;
            }
            if(12 < this.start_month || 0 == this.start_month )
            {
                this.errorMessageArray.push("開始月を１～１２に設定してください。");
                this.isError = true;
            }
            return !this.isError;
        }
    },
    mounted() {
        axios.get('getCalendarList', {
        }).then(response => {
            if(response.data.result){
                this.calendarList = response.data.values.calendarList;
            }
        }).catch(error => {
            //取得エラー発生。コーション出してリダイレクト
            //alert("不正なアクセスが発生しました。トップへ戻ります。");
            //location.href = '/swk';
        });
        this.work_holiday_list =  this.getMasterData().work_holiday;
    },
    computed:{

    }
};
</script>
