<template>
<div>
    <div id="C030-02-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt">
        <div class="row">
            <div class="px-2">
                <div id="C030-02-02-01" class="text-left">
                    年次有給休暇情報
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div id="C030-02-02-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">有休日数・付与情報</div>
                        <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <tbody>
                                <tr>
                                    <td>有休残日数</td>
                                    <td>{{this.remaining_holiday_days}}日</td>
                                </tr>
                                <tr>
                                    <td>付与起算日</td>
                                    <td>{{this.first_grant_date}}</td>
                                </tr>
                                <tr>
                                    <td>次回付与日</td>
                                    <td>{{this.next_grant_date}}</td>
                                </tr>
                                <tr>
                                    <td>次回付与日数</td>
                                    <td>{{this.next_grant_holiday_days}}日</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div id="C030-02-02-03" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">有休使用状況</div>
                        <div v-if="isAcquiredData">
                            <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                                <thead>
                                    <tr>
                                        <th>付与日</th>
                                        <th>付与日数</th>
                                        <th>使用済み</th>
                                        <th>残日数</th>
                                        <th>期限</th>
                                        <th>修正</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in acquired_absent_info" :key="item.holiday_management_id">
                                        <td>{{item.grant_date}}</td>
                                        <td>{{item.grant_holiday_days}}日</td>
                                        <td>{{item.acquired_holiday_days}}日</td>
                                        <td>{{item.remaining_holiday_days}}日</td>
                                        <td>{{item.valid_date_end}}</td>
                                        <td><button class="btn btn-primary" v-on:click="openFixModal(item.holiday_management_id,item.holiday_id,item.grant_date,item.valid_date_end,item.grant_holiday_days,item.acquired_holiday_days)">修正</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center w-100" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">
                            有給休暇情報がありません
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="C030-02-03" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt">
        <div class="row">
            <div class="px-2">
                <div id="C030-02-03-01" class="text-left">
                    保存休情報
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
                <div id="C030-02-03-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">保存休使用状況</div>
                        <div v-if="isAccumulatedData">
                            <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>最終付与日</th>
                                        <th>付与日数</th>
                                        <th>使用済み</th>
                                        <th>残日数</th>
                                        <th>期限</th>
                                        <th>修正</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in accumulated_absent_info" :key="item.holiday_management_id">
                                        <td>保存休暇</td>
                                        <td>{{item.grant_date}}</td>
                                        <td>{{item.grant_holiday_days}}日</td>
                                        <td>{{item.acquired_holiday_days}}日</td>
                                        <td>{{item.remaining_holiday_days}}日</td>
                                        <td>{{item.valid_date_end}}</td>
                                        <td><button class="btn btn-primary" v-on:click="openFixModal(item.holiday_management_id,item.holiday_id,item.grant_date,item.valid_date_end,item.grant_holiday_days,item.acquired_holiday_days)">修正</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center w-100" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">
                            保存休暇情報がありません
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                
            </div>
        </div>
    </div>
    <div id="C030-02-04" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt">
        <div class="row">
            <div class="px-2">
                <div id="C030-02-04-01" class="text-left">
                    その他有給休暇情報（期限付き有給休暇）
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
                <div id="C030-02-04-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <button class="btn btn-primary" style="width:200px;" v-on:click="openGiveModal()">その他休暇付与</button>
                        <div v-if="isReserveData">
                            <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>付与日</th>
                                        <th>付与日数</th>
                                        <th>使用済み</th>
                                        <th>残日数</th>
                                        <th>期限</th>
                                        <th>修正</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in reserve_absent_info" :key="item.holiday_management_id">
                                        <td>{{item.holiday_name}}</td>
                                        <td>{{item.grant_date}}</td>
                                        <td>{{item.grant_holiday_days}}日</td>
                                        <td>{{item.acquired_holiday_days}}日</td>
                                        <td>{{item.remaining_holiday_days}}日</td>
                                        <td>{{item.valid_date_end}}</td>
                                        <td><button class="btn btn-primary" v-on:click="openFixModal(item.holiday_management_id,item.holiday_id,item.grant_date,item.valid_date_end,item.grant_holiday_days,item.acquired_holiday_days)">修正</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center w-100" style="margin-top:20pt;background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">
                            その他休暇情報がありません
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>
<script>

export default {
    name: "absent_manage_board",
    props:{
        employee_id: Number,
    },
    data() {
        return {
            employeeID: 0,
            remaining_holiday_days: 0,
            first_grant_date: "",
            next_grant_date: "",
            next_grant_holiday_days: 0,
            acquired_absent_info: [],
            accumulated_absent_info: [],
            reserve_absent_info: [],
            isAcquiredData: false,
            isAccumulatedData: false,
            isReserveData: false,
            selectHolidayManagementId: 0,
            selectHolidayId: 0,
            modalOptionchecked: {
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
        getAbsentList()
        {
            this.acquired_absent_info = [];
            this.accumulated_absent_info = [];
            this.reserve_absent_info = [];

            axios.get('getAbsentInfo', {
                //年月を6桁で送信
                params:{
                    'employeeID': this.employeeID,
                    'targetdate': this.todaySerial(),
                }
            }).then(response => {
                if(response.data.result)
                {
                    //有休残日数
                    this.remaining_holiday_days = 0;
                    //付与起算日
                    this.first_grant_date = this.serialToDateStr(response.data.values.grant_starting_date);
                    //次回付与日
                    this.next_grant_date = this.serialToDateStr(response.data.values.paid_leave_date_start_serial);
                    //次回付与日数
                    this.next_grant_holiday_days = response.data.values.grant_paid_leave_days;

                    this.isAcquiredData = response.data.values.acquired_absent_info.length != 0;
                    if(this.isAcquiredData){

                        for(let i = 0; i < response.data.values.acquired_absent_info.length; i++)
                        {
                            this.remaining_holiday_days += response.data.values.acquired_absent_info[i].remaining_holiday_days;
                            this.acquired_absent_info.push({
                                'holiday_management_id' : response.data.values.acquired_absent_info[i].holiday_management_id,
                                'holiday_id' : response.data.values.acquired_absent_info[i].holiday_id,
                                'grant_date' : this.serialToDateStr(response.data.values.acquired_absent_info[i].grant_date),
                                'grant_holiday_days' : response.data.values.acquired_absent_info[i].grant_holiday_days,
                                'acquired_holiday_days' : response.data.values.acquired_absent_info[i].acquired_holiday_days,
                                'remaining_holiday_days' : response.data.values.acquired_absent_info[i].remaining_holiday_days,
                                'valid_date_end' : this.serialToDateStr(response.data.values.acquired_absent_info[i].valid_date_end),
                            });
                        }
                    }
                    this.isAccumulatedData = response.data.values.accumulated_absent_info.length != 0;
                    if(this.isAccumulatedData){
                        for(let i = 0; i < response.data.values.accumulated_absent_info.length; i++)
                        {
                            this.accumulated_absent_info.push({
                                'holiday_management_id' : response.data.values.accumulated_absent_info[i].holiday_management_id,
                                'holiday_id' : response.data.values.accumulated_absent_info[i].holiday_id,
                                'grant_date' : this.serialToDateStr(response.data.values.accumulated_absent_info[i].grant_date),
                                'grant_holiday_days' : response.data.values.accumulated_absent_info[i].grant_holiday_days,
                                'acquired_holiday_days' : response.data.values.accumulated_absent_info[i].acquired_holiday_days,
                                'remaining_holiday_days' : response.data.values.accumulated_absent_info[i].remaining_holiday_days,
                                'valid_date_end' : this.serialToDateStr(response.data.values.accumulated_absent_info[i].valid_date_end),
                            });
                        }
                    }
                    this.isReserveData = response.data.values.reserve_absent_info.length != 0;
                    if(this.isReserveData){
                        for(let i = 0; i < response.data.values.reserve_absent_info.length; i++)
                        {
                            this.reserve_absent_info.push({
                                'holiday_management_id' : response.data.values.reserve_absent_info[i].holiday_management_id,
                                'holiday_id' : response.data.values.reserve_absent_info[i].holiday_id,
                                'holiday_name' : response.data.values.reserve_absent_info[i].holiday_name,
                                'grant_date' : this.serialToDateStr(response.data.values.reserve_absent_info[i].grant_date),
                                'grant_holiday_days' : response.data.values.reserve_absent_info[i].grant_holiday_days,
                                'acquired_holiday_days' : response.data.values.reserve_absent_info[i].acquired_holiday_days,
                                'remaining_holiday_days' : response.data.values.reserve_absent_info[i].remaining_holiday_days,
                                'valid_date_end' : this.serialToDateStr(response.data.values.reserve_absent_info[i].valid_date_end),
                            });
                        }
                    }
                }
                else
                {
                    
                }
             })
        },
        openGiveModal()
        {
            const option = {
                //ToDo対象者情報渡す必要あり
                firstPaidFlg:true,
                giveDay:this.todaySerial(),
                limitDay:this.todaySerial(),
                isFixMode: false,
                callback_regist: (absentType, giveDay, limitDay, giveNums)=>{this.callback_regist(absentType, giveDay, limitDay, giveNums);},
                callback_cancel: ()=>{this.callback_cancel();},
            }
            this.openModal('m111_give_paid_absents', '', option);
        },
        openFixModal(holiday_management_id,holiday_id,grant_date,valid_date_end,grant_holiday_days,acquired_holiday_days)
        {
            const option = {
                //ToDo対象者情報渡す必要あり
                //休暇種別と休暇ID渡す必要あり
                absentType:holiday_id,
                giveDay:this.checkDate(grant_date),
                limitDay:this.checkDate(valid_date_end),
                giveNums: grant_holiday_days,
                usedNums: acquired_holiday_days,
                isFixMode: true,
                callback_edit: (giveNums, usedNums)=>{this.callback_edit(giveNums, usedNums);},
                callback_cancel: ()=>{this.callback_cancel();},
            }
            this.selectHolidayManagementId = holiday_management_id;
            this.selectHolidayId = holiday_id;
            this.openModal('m111_give_paid_absents', '', option);
        },
        //モーダルにてキャンセルされたときのコールバック
        callback_cancel(){
            
        },
        callback_edit(giveNums, usedNums){
            axios.post('update_holiday_management', {
                holiday_management_id: this.selectHolidayManagementId,
                holiday_id: this.selectHolidayId,
                grant_holiday_days: Number(giveNums),
                acquired_holiday_days: Number(usedNums),
                employee_id: this.employeeID,
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOptionchecked.message =  "休暇管理情報" + response.data.values.holiday_management_id + "の休暇付与日数(" + response.data.values.grant_holiday_days + ")、休暇取得日数(" + response.data.values.acquired_holiday_days + ")を更新しました。";
                    this.openModal("m112_common_message", "", this.modalOptionchecked);
                    this.getAbsentList();
                }
                else
                {
                    this.modalOptionchecked.message = "更新失敗しました。";
                    this.openModal("m112_common_message", "", this.modalOptionchecked);
                }
            }).catch(error => {
                console.log(error.response);
            });
        },
        callback_regist(absentType, giveDay, limitDay, giveNums){
            axios.post('insert_holiday_management', {
                holiday_id: absentType,
                employee_id: this.employeeID,
                grant_date: this.checkDate(giveDay),
                grant_holiday_days: giveNums,
                valid_date_end: this.checkDate(limitDay),
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOptionchecked.message =  "休暇管理情報" + response.data.values.holiday_management_id +"を新規登録しました。";
                    this.openModal("m112_common_message", "", this.modalOptionchecked);
                    this.getAbsentList();
                }
                else
                {
                    this.modalOptionchecked.message = "新規登録失敗しました。";
                    this.openModal("m112_common_message", "", this.modalOptionchecked);
                }
            }).catch(error => {
                console.log(error.response);
            });   
        },
    }, 
    watch: {
        employee_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.employeeID = Number(value);
                    this.getAbsentList();
                }
            }
        },
    }
}

</script>