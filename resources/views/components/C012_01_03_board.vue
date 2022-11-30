<template>
    <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
        <div id="C012-01-03-01" class="text-left">
            違反・警告情報
        </div>
        <div v-if="isExistData">
            <div id="C012-01-03-02" class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
                <span v-for="(item, i) in warning_message" :key="i">{{item}}</span>
            </div>
        </div>
        <div v-if="!isExistData">
            <div class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
            データがありません
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'C012_01_03_board',
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
    },
    data() {
        return {
            yearMonth: 0,
            employeeID: 0,
            warning_message: [],
            labor_situation_info: '',
        };
    },
    methods: {
         getLaborSituationList: async function(){
            this.warning_message = [];
            this.labor_situation_info = await this.getAttendanceInformationMonthly(this.employeeID, this.yearMonth);
            if(this.isExistData)
            {
                //乖離、違反、警告の件数をそれぞれカウントして表示
                var kairi_count = 0;
                var ihan_count = 0;
                var keikoku_count = 0;
                for(let i = 0; i < this.labor_situation_info.attendance_information.length; i++)
                {
                    switch(this.labor_situation_info.attendance_information[i].violation_warning_id)
                    {
                        case 2:
                            kairi_count++;
                            break;
                        case 3:
                            ihan_count++;
                            break;
                        case 4:
                            keikoku_count++;
                            break;
                    }
                }
                if(kairi_count > 0){
                    this.warning_message.push(this.session_data.master_data.violation_warning[1].violation_warning_name+"が"+kairi_count+"件あります　　");
                }
                if(ihan_count > 0){
                    this.warning_message.push(this.session_data.master_data.violation_warning[2].violation_warning_name+"が"+ihan_count+"件あります　　");
                }
                if(keikoku_count > 0){
                    this.warning_message.push(this.session_data.master_data.violation_warning[3].violation_warning_name+"が"+keikoku_count+"件あります");
                }
                if(this.warning_message.length === 0){
                    this.warning_message.push("現在警告はありません");
                }
            }
        }
    },
    computed:{
        isExistData: function()
        {
            return this.labor_situation_info.attendance_aggregate;
        },
    },
    watch: {
        employee_id: {
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.employeeID = Number(value);
                    //yearMonthが初期値の時は処理しない（初回2度読み防止）
                    if(this.yearMonth){
                        this.getLaborSituationList();
                    }
                }
            }
        },
        year_month: {
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.yearMonth = value;
                    //employeeIDが初期値の時は処理しない（初回2度読み防止）
                    if(this.employeeID){
                        this.getLaborSituationList();
                    }
                }
            }
        }
    },
}
</script>