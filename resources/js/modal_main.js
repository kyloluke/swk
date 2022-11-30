import modalOuter from './components/modalOuter.vue'

//画面外クリックで閉じないモーダルを登録
const backdropStatic = [
    'm112_common_message',
    'm105_input_attendance_details',
    'm106_daily_report',
    'm108_setting_target',
    'm115_check_upload',
];

//views/modal/配下の.vueファイルすべてをモーダルのコンポーネントとして登録
const files = require.context('./../views/modal/', true, /\.vue$/);
const components = {};
files.keys().forEach(key => {
    components[key.replace(/(\.\/|\.vue)/g, '')] = files(key).default;
});

// 読み込んだvueファイルをグローバルコンポーネントとして登録
Object.keys(components).forEach(key => {
    Vue.component(key, components[key]);
});

//モーダル用function
var modalFunc = {
    methods: {
        openModal(contents, size, option1, option2) {
            var currentModal = document.querySelectorAll(".modal");
            var modalLength = currentModal.length;
            var modalId = 'modal' + (modalLength + 1);
            var ComponentClass = Vue.extend(modalOuter);
            var instance = new ComponentClass({
                propsData: {
                    contents: contents,
                    size: size,
                    id: modalId,
                    option1: option1,
                    option2: option2
                }
            });
            instance.$mount();
            document.body.appendChild(instance.$el);
            var zIndexbase = 1050;
            this.$nextTick(function () {
                var $currentModal = $('#' + modalId);
                $currentModal.css('z-index', zIndexbase + modalLength).on('transitionstart', function () {
                $(this).next('.modal-backdrop').css('z-index', zIndexbase + modalLength - 1)
                })
                $currentModal.css('overflow-y', 'auto');
                if(backdropStatic.indexOf(contents) !== -1){
                    $currentModal.modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                }
                $currentModal.modal('show');
            })
        },
        cleanModal(){
            //全modalをremoveする
            for(let i = 1;;i++)
            {
                if($("#modal" + i).length){
                    $("#modal" + i).remove();
                }
                else
                {
                    break;
                }
            }
        }
    },
}
Vue.mixin(modalFunc);
