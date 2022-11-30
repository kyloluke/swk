<template>
    <div id="C023-01" class="container-fluid p-3 h-100 w-100">
        <div v-if="isSelectedTarget">
            <generalSearch_board :session_data="session_data" :is_office_page="true"></generalSearch_board>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        session_data: Object,
    },
    components: {
    },
    data() {
        return {
            isSelectedTarget: true,
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
        },
    },
    mounted(){
    },
    computed:{

    },
    watch: {

    }
}
</script>