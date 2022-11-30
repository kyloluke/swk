<template>
    <div id="C005-01" class="p-3">
        <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link-tab active" style="font-size:12pt;" id="C005-01-01" data-toggle="tab" href="#C011" role="tab" aria-controls="C011" aria-selected="true">勤怠入力</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C005-01-02" data-toggle="tab" href="#C012" role="tab" aria-controls="C012" aria-selected="false">労働・休暇状態確認</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C005-01-03" data-toggle="tab" href="#C013" role="tab" aria-controls="C013" aria-selected="false" v-on:click="onClickDailyReport">日報</a>
            </li>
            <div style="margin-left: auto">
                <a id="C005-01-04" style="font-size:12pt; margin-left: auto" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" v-on:click="openModal('m107_change_password')">
                    パスワード変更
                </a>
            </div>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active pagectl" id="C011" role="tabpanel" aria-labelledby="C005-01-01">
                <w011_input_attendance :employee_id="employee_id" :session_data="session_data" ref='w011_input_attendance'></w011_input_attendance>
            </div>
            <div class="tab-pane fade" id="C012" role="tabpanel" aria-labelledby="C005-01-02">
                <w012_labor_situation :employee_id="employee_id" :session_data="session_data"></w012_labor_situation>
            </div>
            <div class="tab-pane fade" id="C013" role="tabpanel" aria-labelledby="C005-01-03">
                <w013_daily_report :employee_id="employee_id" :session_data="session_data" ref='w013_daily_report'></w013_daily_report>
            </div>
        </div>

    </div>
    

</template>

<script>
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Stringで来る
        session_data: Object, //Sessionから取得した社員情報・マスタ情報
    },
    data() {
        return {
            yearMonth: 0, //ここでの値保持＆子へ渡す用
        };
    },
    methods:{
        //本人入力共通初期化（本人入力メニュークリックしたら呼ばれる）
        initialize(){
            this.$refs['w011_input_attendance'].initialize();
        },
        //日報メニュークリックしたら初期化
        onClickDailyReport(){
            this.$refs['w013_daily_report'].initialize();
        }
    },
    computed:{
    },
    watch: {
    }
}
</script>
