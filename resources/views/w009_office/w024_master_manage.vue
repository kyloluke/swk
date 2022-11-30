<template>
    <div id="C024-01" class="container-fluid p-3 h-100 w-100" style="margin-top:20pt;">
        <ul class="nav nav-pills">
            <li class="nav-item tab-1">
                <a class="nav-link active" data-toggle="tab" href="#tab24_1">社員情報マスタ</a>
            </li>
        </ul>
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="tab24_1" role="tabpanel">
                <employee_info_board title="社員情報マスタ" :session_data="session_data" :employee_id="employeeID" :is_menu_office="true" ></employee_info_board>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    components: {
        
    },
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object, //Sessionから取得した社員情報・マスタ情報
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持＆子へ渡す用
            yearMonth: 0, //ここでの値保持＆子へ渡す用
        };
    },
    methods:{
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
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
}
</script>

