<div id="loginCheckMenu" style="font-family: 'Noto Sans JP', sans-serif;">
    @auth
    @php
        $proxy = session('employee') -> authority_pattern -> proxy_input_menu;
        $attend = session('employee') -> authority_pattern -> attendance_admin_menu;
        $office = session('employee') -> authority_pattern -> office_menu;
        $general = session('employee') -> authority_pattern -> general_affairs_menu;
    @endphp
    <w002menu is_proxy={{$proxy}} is_attend={{$attend}} is_office={{$office}} is_general={{$general}} employee_id="{{Auth::user()->employee_id}}" session_data="{{session('employee')}}"></w002menu>
    @endauth
    @guest
    <div class="container-fluid h-100" style="min-height:600pt">
        <!-- Nav pills -->
        @if(!$is_multitennant)
        <div class="row h-100" style="min-width:800pt">
            <div class="col-auto h-100" style="width:200pt; margin-top: 55px">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="position:fixed; width: 240px; z-index:95; height:100%; background:#f8fafc;">
                    <div id="C002-01-01" style="text-align:center;font-size:11pt;margin-right:10px;">MENU</div>
                    <a id="C002-01-02" class="nav-link active"data-toggle="pill" href="#C003" style="margin-right: 2px;">打刻</a>
                </div>
                <div style="position:fixed; width: 238px; margin-top: 85px; z-index:100">
                    <a type="button" class="btn btn-xlg btn-primary btn-block text-white text-left" href="http://honkyu25:8080/sbLG/L000M001.aspx" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt" style="margin-right: 10px;"></i>給与・賞与明細等照会</a>
                </div>
                <div class="left-back"></div>
                <div style="position:fixed; bottom:88px; width: 240px; z-index:100">
                    <a type="button" class="btn btn-success btn-block text-white text-left" href="/manual"><i class="fas fa-question-circle" style="margin-right: 10px;"></i>操作マニュアル</a>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="col h-100" style="background-color:#fcfcfc;min-width:600pt;max-width:100%;min-height:600pt;margin-top:60px;">
                <div class="tab-content">
                    <div id="C003" class="tab-pane active">
                        @include('w003_record_time.w003_record_time')    
                    </div>
                </div>
            </div>
            <!-- Tab panes end -->
        </div>
        @else
        <!-- 企業ログイン画面 -->
        <multi_login></multi_login>
        @endif
    </div>
    @endguest
</div>
