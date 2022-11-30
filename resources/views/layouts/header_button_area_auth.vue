<template>
    <div style="text-align:right;width:80%;display:inline-block;">
        <div class=" p-1 text-white border border-dark" style="background-color:#BCD2EE;text-align:right;display:inline-block;">
            <div id="C001-01-03" class="d-inline-block" style="color: white;text-align:left;font-size:11pt"> ログイン中： </div>
            <div id="C001-01-04" class="d-inline-block" style="color: white;text-align:left;font-size:11pt; margin-right: 20px;">
                <span>{{employeeCode}}</span>
                <span style="margin-left: 20px;">{{employeeName}}</span>
                <span style="margin-left: 20px;">{{employeeOfficeName}} </span>
            </div>
            <a id="C001-01-05" type="button"  class="btn btn-success" style="font-size:11pt" v-on:click="logout()">ログアウト</a>
        </div>       
    </div>
</template>

<script>


export default {
    name: "header_button_area_auth",
    props:{
        //セッション
        session_data: Object,
    },
    data() {
        return {
            op1:{
                buttons:[
                    {
                        exec : ()=>{
                            //sessionStorageクリア
                            sessionStorage.clear();
                            //リダイレクト
                            location.href = "/swk";
                            //リダイレクト後、/swkのCotroller内でログアウト処理が行われる
                        },
                        caption : "OK",
                        btnclass : "btn-success"
                    },
                    {
                        exec : ()=>{
                            //nothing to do
                        },
                        caption : "キャンセル",
                        btnclass : "btn-danger"
                    }
                ],
                message: "ログアウトしますか？"
            }
        };
    },
    mounted() {
    },
    methods: {
        logout(){
            //モーダル開く
            this.openModal('m112_common_message', '', this.op1);
        },
    },
    computed: {
        employeeCode: function() {
            return this.session_data.employee_code;
        },
        employeeName: function() {
            return this.session_data.employee_name;
        },
        employeeOfficeName: function() {
            let office = this.session_data.master_data.office.find((elm) => elm.office_id === this.session_data.office_id);
            return office.office_name;
        },
    }
}

</script>