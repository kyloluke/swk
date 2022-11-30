<div id="loginCheckHeader">
    @auth
    <div class="border border-dark border-bottom-0 p-1 bg-primary text-white" style="text-align:right;display:inline-block;width:100%;">
        <div style="text-align:left;width:20%;float:left;display:inline;height:30pt;">
            <div id="C001-01-01" class="d-inline-block" style="color: white;text-align:left;height:11pt;padding-left:5pt;font-size:11pt;">ShachihataCloud 勤怠管理</div>
        </div>
        <header_button_area_auth :session_data="{{session('employee')}}"></header_button_area_auth>
    </div>
    @endauth
    @guest
    <div class="border border-dark border-bottom-0 p-1 bg-primary text-white" style="text-align:right;display:inline-block;width:100%;min-width:800pt;">
        <div style="text-align:left;width:20%;float:left;display:inline;height:30pt;">
            <div class="d-inline-block" style="color: white;text-align:left;height:11pt;padding-left:5pt;font-size:11pt;">ShachihataCloud 勤怠管理</div>
        </div>
        @if(!$is_multitennant)
        <div class="p-1" style="text-align:right;display:inline-block;">
            <button id="C001-01-02" type="button" class="btn btn-success" style="font-size:11pt" v-on:click="openModal('m101_login')">ログイン</button>     
        </div>
        @endif
    </div>
    @endguest
</div>
