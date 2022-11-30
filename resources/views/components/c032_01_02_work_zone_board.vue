<template>
    <div id="C032-01-02">
        <div id="C032-01-02-01" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C032-01-02-01-01" class="text-left">
                        勤務帯マスタメニュー
                    </div>
                </div>
            </div>
            <div class="row">
                <button id="C032-01-02-01-02" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" v-on:click="editWorkZone(null)">新規登録</button>
                <button id="C032-01-02-01-03" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" v-on:click="searchMember()">勤務帯検索</button>
            </div>
        </div>
        <div id="C032-01-02-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C032-01-02-02-01" class="text-left">
                        勤務帯一覧
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="p-2 col-12 text-left">
                    <div id="C032-01-02-02-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div id="C032-01-02-02-02-001" class="text-center" style="color:#000000;display:inline-block">
                                <select class="form-control" id="c032officeId" v-model="officeID" v-on:change="onChangeDepartment">
                                    <option value="0">全社共通</option>
                                    <option v-for="option in officeList" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                                </select>
                            </div>
                            <table v-if="workZoneList.length != 0" class="table-master" style="margin-top:20pt;font-size:12pt;">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">名称</th>
                                        <th rowspan="2">コード</th>
                                        <th class="noBorder" colspan="2">所定時間</th>
                                        <th rowspan="2">所定労働時間</th>
                                        <th rowspan="2">所定休憩時間</th>
                                        <th class="noBorder" colspan="2">所定休憩1</th>
                                        <th class="noBorder" colspan="2">所定休憩2</th>
                                        <th class="noBorder" colspan="2">所定休憩3</th>
                                    </tr>
                                    <tr>
                                        <th>開始</th>
                                        <th>終了</th>
                                        <th>開始</th>
                                        <th>終了</th>
                                        <th>開始</th>
                                        <th>終了</th>
                                        <th>開始</th>
                                        <th>終了</th>
                                    </tr>
                                </thead>
                                <tbody id="C032">
                                    <tr v-for="item in workZoneList" :key="item.list_id">
                                        <td style="cursor: pointer; color: blue; text-decoration: underline; text-decoration-color: blue;" v-on:click="editWorkZone(item.workZoneId)">{{item.workZoneId}}</td>
                                        <td style="text-align: left">{{item.workZoneName}}</td>
                                        <td>{{item.workZoneCode}}</td>
                                        <td>{{serialToTimeStr(item.prescribedTimeStart)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedTimeEnd)}}</td>
                                        <td>{{serialToHoursStr(item.work_time)}}</td>
                                        <td>{{serialToHoursStr(item.break_time)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedRest1Start)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedRest1End)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedRest2Start)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedRest2End)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedRest3Start)}}</td>
                                        <td>{{serialToTimeStr(item.prescribedRest3End)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else>
                                <div class="text-center" style="color:#000000;font-size:15pt">
                                データがありません
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
export default {
    data() {
        return {
            op1:{
                callback_regist: ()=>{this.m114_callback_regist();},
                callback_cancel: ()=>{this.m114_callback_cancel();},
                work_zone_id: null,
            },
            officeID: 0,
            workZoneList: [],
        }
    },
    methods:{
        editWorkZone(workZoneID)
        {
            //モーダルを開く
            this.op1.workZoneID = workZoneID;
            this.openModal('m114_register_work_pattern', '', this.op1)
        },
        searchMember()
        {
            //モーダルを開く TODO
        },
        onChangeDepartment(){
            // 勤務帯一覧をリセットする
            this.getWorkZoneList();
        },
        async getWorkZoneList(isNeedReset){
            this.workZoneList = [];
            if(isNeedReset)
            {
                //SessionStorageの置き換え処理
                await this.resetMasterData('work_zone');
                await this.resetMasterData('work_zone_time');
            }
            const work_zone_info = this.getMasterData().work_zone;
            const work_zone_time_info = this.getMasterData().work_zone_time;
            let index_count = 1;
            const work_zone_in_office = work_zone_info.filter((elm) => elm.office_id == this.officeID);
            if(work_zone_in_office.length != 0)
            {
                for(let i = 0; i < work_zone_in_office.length; i++)
                {
                    const work_zone = work_zone_in_office[i];
                    const work_zone_time = work_zone_time_info.filter((elm) => elm.work_zone_id == work_zone.work_zone_id);
                    const work_zone_time_actual = work_zone_time.find((elm) => elm.time_type_class == 1);
                    const work_zone_time_rest = work_zone_time.filter((elm) => elm.time_type_class == 2);
                    
                    let break_times = Array(3);
                    for(let break_count = 0; break_count < break_times.length; break_count++)
                    {
                        if(work_zone_time_rest[break_count])
                        {
                            if(work_zone_time_rest[break_count].start_time >= 1440)
                            {
                                work_zone_time_rest[break_count].start_time -= 1440;
                            }
                            if(work_zone_time_rest[break_count].end_time >= 1440)
                            {
                                work_zone_time_rest[break_count].end_time -= 1440;
                            }
                            break_times[break_count] = {
                                work_zone_time_id: work_zone_time_rest[break_count].work_zone_time_id,
                                start_time: work_zone_time_rest[break_count].start_time,
                                end_time: work_zone_time_rest[break_count].end_time,
                                };
                        }
                        else
                        {
                            break_times[break_count] = {start_time: null,
                                                        end_time: null,
                                                        };
                        }
                    }
                    this.workZoneList.push({
                        'list_id': index_count,
                        'workZoneId': work_zone.work_zone_id,
                        'targetOffice': this.officeID,
                        'workZoneCode': work_zone.work_zone_code,
                        'workZoneName': work_zone.work_zone_name,
                        'prescribedTimeId': work_zone_time_actual.work_zone_time_id,
                        'prescribedTimeStart': work_zone_time_actual.start_time,
                        'prescribedTimeEnd': work_zone_time_actual.end_time,
                        'work_time': work_zone.actual_work_time + work_zone.midnight_actual_work_time,
                        'break_time': work_zone.break_time + work_zone.midnight_break_time,
                        'prescribedRest1Id':    break_times[0].work_zone_time_id,
                        'prescribedRest1Start': break_times[0].start_time,
                        'prescribedRest1End':   break_times[0].end_time,
                        'prescribedRest2Id':    break_times[1].work_zone_time_id,
                        'prescribedRest2Start': break_times[1].start_time,
                        'prescribedRest2End':   break_times[1].end_time,
                        'prescribedRest3Id':    break_times[2].work_zone_time_id,
                        'prescribedRest3Start': break_times[2].start_time,
                        'prescribedRest3End':   break_times[2].end_time,
                    });
                    index_count++;
                }
            }
        },
        m114_callback_regist(){
            //勤務帯一覧をリセットする
            this.getWorkZoneList(true);
        },
        m114_callback_cancel(){
        
        },
    },
    computed: {
        officeList: function(){
            let retArray = [];
            const officeList = this.getMasterData().office;
            for(let i = 0; i < officeList.length; i++)
            {
                retArray.push({
                    'office_id': officeList[i].office_id, 
                    'office_name': officeList[i].office_name,
                });
            }
            return retArray;
        }
    },
    mounted(){  //PC032-01-02 初期化処理
        this.getWorkZoneList();
    }
}
</script>
