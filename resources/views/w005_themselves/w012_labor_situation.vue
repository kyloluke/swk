<template>
    <div id="C012-01" class="container-fluid p-3 h-100 w-100">
        <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="container-fluid h-100 w-100" style="margin-top: 10pt">
                    <button id="C012-01-01" class="btn btn-primary" style="font-size:15pt; width:100pt; text-align: left; padding-left: 22px;" v-on:click="onClickPrev()">◀　前月</button>
                    <span id="C012-01-02" class="text-left" style="color: #000000; font-size: 24px; margin-left: 60px; margin-right: 60px; vertical-align: middle;" v-html="targetYearMonth"></span>
                    <button id="C012-01-04" class="btn btn-primary" style="font-size: 15pt; width: 100pt; text-align: right; padding-right: 22px;" v-on:click="onClickNext()">翌月　▶</button>
                </div>
            </div>
        </div>
        <laborSituation_board :employee_id="employeeID" :year_month="yearMonth" :session_data="session_data"></laborSituation_board>
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
        };
    },
    methods: {
        onClickPrev() {
            this.yearMonth = this.calcYearMonth(this.yearMonth, -1);
        },
        onClickNext() {
            this.yearMonth = this.calcYearMonth(this.yearMonth, 1);
        },
    },
    mounted() {
        this.yearMonth = Number(this.serialToDateStr(this.todaySerial(), "YYYYMM"));
        
    },
    computed:{
        targetYearMonth: function(){
            return this.yearMonthNumberToText(this.yearMonth);       
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
    }
}
</script>
