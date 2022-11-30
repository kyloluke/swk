<template>
    <div id="C011-01" class="container-fluid p-3 h-100 w-100">
        <inputAttendance_board :employee_id="employeeID" :year_month="yearMonth" :is_manager="false" :background_type="backgroundType" :information_attendance_mode="0" :session_data="session_data"></inputAttendance_board>
    </div>
</template>

<script>
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持＆子へ渡す用
            yearMonth: 0, //ここでの値保持＆子へ渡す用
            backgroundType: 1,  //背景色
        };
    },
    methods: {
        initialize(){
            let close_date_id = this.getMasterData().close_date_id;
            let close_date = this.getMasterData().close_date[close_date_id - 1].close_date;
            let display_switch_date = this.getMasterData().close_date[close_date_id - 1].display_switch_date;
            display_switch_date = display_switch_date == 0 ? close_date + 1 : display_switch_date;
            this.yearMonth = this.getDisplaySwitchDate(display_switch_date);
        },
        refresh() {
            //
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
                    this.employeeID = value;
                }
            }
        }
    },
    mounted() {
        this.initialize();
    },
}
</script>
