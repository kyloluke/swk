<template>
    <div id="C004-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
        <div id="C004-01-02-01" v-html="title" style="font-size:12pt"></div>
        <div class="row" style="margin-top:20pt;">
            <a class="col-3 text-center nav-link" data-toggle="pill" href="#C005" v-on:click="showTopMenu('#C011'), vshowTopMenu('#C005'), requiredApp()">
                <card_top id="C004-01-02-02" title="要申請" :number="number_of_violation_warning" unit="　件" :comment="violation_warning_comment" style="min-height: 220pt"></card_top>
            </a>
            <a class="col-3 text-center nav-link" data-toggle="pill" href="#C005" v-on:click="showTopMenu('#C011'), vshowTopMenu('#C005'), applying()">
                <card_top id="C004-01-02-03" title="申請中" :number="number_of_applications" unit="　件" :comment="applications_comment" style="min-height: 220pt"></card_top>
            </a>
            <a class="col-3 text-center nav-link" data-toggle="pill" href="#C007" v-on:click="showTopMenu('#C016'), vshowTopMenu('#C007')">
                <card_top id="C004-01-02-04" title="未承認" :number="number_of_unapproved" unit="　件" :comment="unapproved_comment" :comment2="unapproved_comment2" style="min-height: 220pt"></card_top>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
            number_of_applications: '0',
            applications_comment: '',
            number_of_violation_warning: '0',
            violation_warning_comment: '',
            number_of_unapproved: '0',
            unapproved_comment: '',
            unapproved_comment2: '',
        };
    },
    mounted(){
        this.yearMonth = this.dateStrToyearMonthNumber(this.serialToDateStr(this.todaySerial(), 'YYYY/MM/DD'), this.session_data.close_date.close_date);
        this.getTopAttendanceInfo();
    },
    methods: {
        requiredApp() {
            this.$emit('themselves'); //本人勤怠入力画面を当月に更新
        },
        applying() {
            this.$emit('themselves'); //本人勤怠入力画面を当月に更新
        },
        getTopAttendanceInfo(){
            axios.get('workingStatus', {
                //年月を6桁で送信
                params:{
                    'employeeID' : this.employeeID,
                    'targetDate' : this.yearMonth,
                }
            }).then(response => {
                if(response.data.result)
                {
                    //要申請件数（乖離の件数）、申請中件数、未承認件数
                    var wk_number_of_violation_warning = 0;
                    var wk_number_of_applications = 0;
                    var wk_number_of_unapproved = 0;
                    this.violation_warning_comment = '';
                    this.applications_comment = '申請中の項目はありません';
                    this.unapproved_comment = '申請された項目はありません';
                    this.unapproved_comment2 = '';
                    for(let i = 0; i < response.data.values.attendance_information.length; i++)
                    {
                        //当月の要申請件数（乖離の件数）をカウント
                        if(response.data.values.attendance_information[i].violation_warning_id === 2 || 
                        response.data.values.attendance_information[i].approval_state_id === 4 || 
                        response.data.values.attendance_information[i].approval_state_id === 5){
                             wk_number_of_violation_warning++;
                             this.number_of_violation_warning = String(wk_number_of_violation_warning);
                             this.violation_warning_comment = '勤怠入力画面を確認し、申請・修正を行ってください';
                        }
                        //当月の申請中件数をカウント
                        if(response.data.values.attendance_information[i].approval_state_id === 2){
                             wk_number_of_applications++;
                             this.number_of_applications = String(wk_number_of_applications);
                             this.applications_comment = '申請中の項目あり。管理者の承認が必要です。';
                        }
                    }

                    //被承認者ループ
                    for(let i = 0; i < response.data.values.approved_array.length; i++)
                    {
                        //当月日付ループ
                        for(let j = 0; j < response.data.values.approved_array[i].approved_attendance_information_array.length; j++)
                        {
                            //当月の未承認件数（被承認者が申請中）をカウント
                            if(response.data.values.approved_array[i].approved_attendance_information_array[j].approval_state_id === 2){
                                wk_number_of_unapproved++;
                                this.number_of_unapproved = String(wk_number_of_unapproved);
                                this.unapproved_comment = '申請されている項目があります。';
                                this.unapproved_comment2 = '承認を行ってください。';
                            }
                        }
                    }
                }
                else
                {
                    //取得失敗
                }
            })
        }
    },
}
</script>