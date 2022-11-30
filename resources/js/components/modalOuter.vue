<!-- モーダルの外側コンポ 中身はcomponentタグで読み込む-->
<template>
    <div
        class="modal fade"
        :id="id"
        tabindex="-1"
        role="dialog"
        aria-labelledby="id"
        aria-hidden="true"
    >
        <div class="modal-dialog" :class="size" role="document">
            <component v-bind:is="contents" :id="id" :op1="option1" :op2="option2"></component>
        </div>
    </div>
</template>

<script>
import M102data from "../../views/modal/m102_information.vue";
import M103data from "./../../views/modal/m103_record_time_result.vue";
import M104data from "./../../views/modal/m104_record_time_list.vue";
import M106data from "./../../views/modal/m106_daily_report.vue";
import M112data from "./../../views/modal/m112_common_message.vue";
export default {
    components: {
        M102data,
        M103data,
        M104data,
        M106data,
        M112data,
    },
    props: { contents: null, size: null, id: null, option1: null, option2: null },
};
</script>