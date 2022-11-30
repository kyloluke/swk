<template>
    <div class="container-fluid h-100" style="min-height:600pt">
        <!-- Nav pills -->
        <div class="row h-100" style="min-width:1100pt;">
            <div class="col-auto h-100" style="width:200pt;margin-top:55px">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="position:fixed; width:240px; z-index:95; height:100%; background:#f8fafc;">
                    <div class="m-2" style="text-align:center;font-size:11pt;">MENU</div>
                    <a id="C002-01-03" class="nav-link active" data-toggle="pill" href="#C004">トップ</a>
                    <a id="C002-01-04" class="nav-link" data-toggle="pill" href="#C005" v-on:click="showTopMenu('#C011'), initializePage('w005_themselves')">本人入力</a>
                    <a id="C002-01-05" class="nav-link" data-toggle="pill" href="#C006" v-if="isProxy" v-on:click="showTopMenu('#C014'), initializePage('w006_substitute')">代理入力</a>
                    <a id="C002-01-06" class="nav-link" data-toggle="pill" href="#C007" v-if="isAttend" v-on:click="showTopMenu('#C016'), initializePage('w007_attendance_manager')">勤怠管理者</a>
                    <a id="C002-01-07" class="nav-link" data-toggle="pill" href="#C009" v-if="isOffice" v-on:click="showTopMenu('#C020'), initializePage('w009_office')">事業所</a>
                    <a id="C002-01-08" class="nav-link" data-toggle="pill" href="#C010" v-if="isGeneral" v-on:click="showTopMenu('#C025'), initializePage('w010_company')">総務</a>
                </div>
                <div class="left-back"></div>
                <div style="position:fixed; bottom:88px; width: 240px; z-index:100">
                    <a type="button" class="btn btn-success btn-block text-white text-left" href="/manual"><i class="fas fa-question-circle" style="margin-right: 10px;"></i>操作マニュアル</a>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="col h-100" style="background-color:#fcfcfc;min-width:900pt;max-width:100%;min-height:600pt;margin-top:55px">
                <div class="tab-content">
                    <div id="C004" class="tab-pane active">
                        <w004_top :employee_id="employeeID" ref='w004_top' :session_data="sessionData" @themselves="initializePage('w005_themselves')"></w004_top>
                    </div>
                    <div id="C005" class="tab-pane fade">
                        <w005_themselves :employee_id="employeeID" ref='w005_themselves' :session_data="sessionData"></w005_themselves>
                    </div>
                    <div v-if="isProxy" id="C006" class="tab-pane fade">
                        <w006_substitute :employee_id="employeeID" ref='w006_substitute' :session_data="sessionData"></w006_substitute>
                    </div>
                    <div v-if="isAttend" id="C007" class="tab-pane fade">
                        <w007_attendance_manager :employee_id="employeeID" ref='w007_attendance_manager' :session_data="sessionData"></w007_attendance_manager>
                    </div>
                    <div v-if="isOffice" id="C009" class="tab-pane fade">
                        <w009_office :employee_id="employeeID" ref='w009_office' :session_data="sessionData"></w009_office>
                    </div>
                    <div v-if="isGeneral" id="C010" class="tab-pane fade">
                        <w010_company :employee_id="employeeID" ref='w010_company' :session_data="sessionData"></w010_company>
                    </div>
                </div>
            </div>
            <!-- Tab panes end -->
        </div>
    </div>
</template>


<script>
export default {
    name: "w002menu",
    props:{
        //権限
        is_proxy: String,
        is_attend: String,
        is_office: String,
        is_general: String,
        //ログインユーザー
        employee_id: String,
        //セッション
        session_data: String,
    },
    data() {
        return {
            isProxy: false,
            isAttend: false,
            isOffice: false,
            isGeneral: false,
            employeeID: 0,
            sessionData: [],
        };
    },
    mounted() {
    },
    methods: {
    },
    watch: {
        is_proxy: {
            immediate: true,
            handler(value) {
                this.isProxy = !!Number(value);
            }
        },
        is_attend: {
            immediate: true,
            handler(value) {
                this.isAttend = !!Number(value);
            }
        },
        is_office: {
            immediate: true,
            handler(value) {
                this.isOffice = !!Number(value);
            }
        },
        is_general: {
            immediate: true,
            handler(value) {
                this.isGeneral = !!Number(value);
            }
        },
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
                }
            }
        },
        session_data: {
            immediate: true,
            handler(value) {
                if(value)
                {
                    this.sessionData = JSON.parse(value);
                    this.setMasterData(JSON.parse(value).master_data);
                }
            }
        },
    }
}
</script>