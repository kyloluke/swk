<template>

<div v-if="isVisibleList">
<div class="text-center" style="background-color:#ffffff;font-size:12pt;color:#000000;" v-if="isManager">{{targetEmployeeTerm}}</div>
    <table class="table-master" style="margin-top:20pt;font-size:12pt;">
        <thead>
            <tr>
                <th style="width:10%" v-if="isManager">社員番号</th>
                <th style="width:10%" v-if="isManager">名前</th>
                <th style="width:10%">日付</th>
                <th style="width:10%">曜日</th>
                <th style="width:10%">作業開始</th>
                <th style="width:10%">作業終了</th>
                <th style="width:10%">時間</th>
                <th style="width:15%">テーマ</th>
                <th style="width:35%">作業内容</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in dailyReportList" :key="item.daily_report_id">
                <td v-if="isManager">{{item.employee_code}}</td>
                <td v-if="isManager">{{item.employee_name}}</td>
                <td>{{item.date}}</td>
                <td>{{item.week}}</td>
                <td>{{item.work_time_start}}</td>
                <td>{{item.work_time_end}}</td>
                <td>{{item.work_time}}</td>
                <td style="text-align: left">{{item.work_item_name}}</td>
                <td style="text-align: left">{{item.work_content}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div v-else class="text-center" style="background-color:#ffffff;font-size:12pt;color:#000000;line-height: 46px;">条件に一致する日報はありませんでした。</div>
</template>

<script>
export default {
    name: "dailyreportlist_board",
    props:{
        employee_id: Number,
        startDateSerial: Number,
        endDateSerial: Number,
        selectedEmployeeId: Array,
        isManager: Boolean,
    },
    data() {
        return {
            dailyReportList: [],
            employeeID: 0,
            isVisibleList: false,
            firstday_serial: 0,
            lastday_serial: 0,
            selectedEmployeeIdList: [],
            targetEmployeeAndTerm: '',
        };
    },
    mounted() {

    },
    methods:{
        getDailyReportList()
        {
            this.targetEmployeeTerm = "";
            this.dailyReportList = [];
            axios.get('dailyReportList', {
                params:{
                    'firstday_serial' :this.firstday_serial,
                    'lastday_serial' : this.lastday_serial,
                    'employeeID' : this.employeeID,
                    'selectedEmployeeIdList' : this.selectedEmployeeIdList,
                }
            }).then(response => {
                if(response.data.result)
                {
                    if(response.data.t017_daily_report_info.length === 0){
                        this.isVisibleList= false;
                    }else{
                        this.isVisibleList= true;
                        //プロパティにセット
                        for(let i = 0; i < response.data.t017_daily_report_info.length; i++)
                        {
                            this.dailyReportList.push({
                                'daily_report_id' : response.data.t017_daily_report_info[i].daily_report_id,
                                'employee_code' : response.data.t017_daily_report_info[i].employee_code,
                                'employee_name' : response.data.t017_daily_report_info[i].employee_name,
                                'date' : this.serialToDateStr(response.data.t017_daily_report_info[i].work_date,"YYYY/MM/DD"),
                                'week' : this.serialToDateStr(response.data.t017_daily_report_info[i].work_date,"A"),
                                'work_time_start' : this.serialToTimeStr(response.data.t017_daily_report_info[i].work_time_start),
                                'work_time_end' : this.serialToTimeStr(response.data.t017_daily_report_info[i].work_time_end),
                                'work_time' : this.serialToHoursStr(
                                                Number(response.data.t017_daily_report_info[i].work_time_end) > Number(response.data.t017_daily_report_info[i].work_time_start) ?
                                                Number(response.data.t017_daily_report_info[i].work_time_end) - Number(response.data.t017_daily_report_info[i].work_time_start) :
                                                Number(response.data.t017_daily_report_info[i].work_time_end) - Number(response.data.t017_daily_report_info[i].work_time_start) + 1440), //日跨ぎ
                                'work_item_name' : response.data.t017_daily_report_info[i].work_item_name,
                                'work_content' : response.data.t017_daily_report_info[i].work_content,
                            });
                        }

                        this.uniqueEmployee = 1;
                        //社員の数をカウントする
                        for(let i = 1; i < response.data.t017_daily_report_info.length; i++){
                            if(response.data.t017_daily_report_info[i].employee_id === response.data.t017_daily_report_info[i-1].employee_id){
                                //同じ社員の場合、カウントしない
                            }else{
                                this.uniqueEmployee ++;
                            }
                        }

                        this.targetEmployeeTerm += "対象者：";
                        this.currentEmployee = 0;
                        for(let i = 0; i < response.data.t017_daily_report_info.length; i++)
                        {  
                            if(this.currentEmployee === 0){
                                //1名の場合
                                this.targetEmployeeTerm += response.data.t017_daily_report_info[i].employee_id + " " + response.data.t017_daily_report_info[i].employee_name;
                                this.currentEmployee++;
                            }else if(response.data.t017_daily_report_info[i].employee_id === response.data.t017_daily_report_info[i-1].employee_id){
                                //同じ社員の場合、何もしない
                            }else{
                                //2名か3名の場合
                                this.targetEmployeeTerm += "、 " + response.data.t017_daily_report_info[i].employee_id + " " + response.data.t017_daily_report_info[i].employee_name;
                                this.currentEmployee++;
                            }

                            if(this.currentEmployee === 3){
                                //4名以上の場合
                                if(this.uniqueEmployee >= 4){
                                    this.targetEmployeeTerm += "、 ...";
                                }
                                break;
                            }

                        }
                        this.targetEmployeeTerm += "　　　期間：" + this.serialToDateStr(this.firstday_serial, "YYYY/MM/DD") + " ～ " + this.serialToDateStr(this.lastday_serial, "YYYY/MM/DD");
                    }
                }
                else
                {
                    //取得失敗
                }
            });
        }
    },
    computed: {
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

                    //日報リスト取得に必要なデータが揃ったら取得処理する
                    if(this.employeeID && this.firstday_serial && this.lastday_serial){
                        if(this.isManager){
                            if(this.selectedEmployeeIdList){
                                this.getDailyReportList();
                            }
                        }
                        else{
                            this.getDailyReportList();
                        }
                    }
                }
            }
        },
        selectedEmployeeId: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            deep: true,
            handler(value) {
                if(value == null)
                {
                    
                    
                }
                else
                {
                    this.selectedEmployeeIdList = value;

                    //日報リスト取得に必要なデータが揃ったら取得処理する
                    if(this.employeeID && this.firstday_serial && this.lastday_serial){
                        if(this.isManager){
                            if(this.selectedEmployeeIdList){
                                this.getDailyReportList();
                            }
                        }
                        else{
                            this.getDailyReportList();
                        }
                    }
                }
            }
        },
        startDateSerial: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(value <= 0)
                {

                }
                else
                {
                    this.firstday_serial = value;

                    //日報リスト取得に必要なデータが揃ったら取得処理する
                    if(this.employeeID && this.firstday_serial && this.lastday_serial){
                        if(this.isManager){
                            if(this.selectedEmployeeIdList){
                                this.getDailyReportList();
                            }
                        }
                        else{
                            this.getDailyReportList();
                        }
                    }
                }
            }
        },
        endDateSerial: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(value <= 0)
                {

                }
                else
                {
                    this.lastday_serial = value;

                    //日報リスト取得に必要なデータが揃ったら取得処理する
                    if(this.employeeID && this.firstday_serial && this.lastday_serial){
                        if(this.isManager){
                            if(this.selectedEmployeeIdList){
                                this.getDailyReportList();
                            }
                        }
                        else{
                            this.getDailyReportList();
                        }
                    }
                }
            }
        },
    }
}

</script>