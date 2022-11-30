<template>
    <div class="container-fluid h-100" style="min-height:600pt">
        <!-- Nav pills -->
        <div class="row h-100" style="min-width:1100pt;">
            <div class="col-auto h-100" style="width:200pt;margin-top:55px">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="position:fixed; width:240px; z-index:95; height:100%; background:#f8fafc;">
                    <div class="m-2" style="text-align:center;font-size:11pt;">MENU</div>
                    <a class="nav-link active" data-toggle="pill" href="#mng_001">企業管理</a>
                    <a class="nav-link" data-toggle="pill" href="#mng_002">マスタデータ管理</a>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="col h-100" style="background-color:#fcfcfc;min-width:900pt;max-width:100%;min-height:600pt;margin-top:55px">
                <div class="tab-content">
                    <div id="mng_001" class="tab-pane active">
                        <management_001_company></management_001_company>
                    </div>
                    <div id="mng_002" class="tab-pane">
                        <management_002_master_data></management_002_master_data>
                    </div>
                </div>
            </div>
            <!-- Tab panes end -->
        </div>
    </div>
</template>


<script>
export default {
    name: "management_menu",
    props:{
    },
    data() {
        return {
        };
    },
    mounted() {
    },
    methods: {
    },
    watch: {
    }
}
</script>