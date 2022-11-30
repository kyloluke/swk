<div class="container-fluid p-3 h-100 w-100 shadow-sm" style="color:#27408B;font-size:11pt;background-color:#E8E8E8;margin-top:20pt">
    <div class="row">
        <div class="col-2">
            <div class="card shadow h-100" style="background-color:#BCD2EE;">
                <div class="card-body">
                    <button class="btn-sm btn-primary d-inline-block">
                        <span class="material-icons">arrow_left</span>
                    </button>
                    <div class="card-text d-inline-block" style="color:#000000;">2021年 6月</div>
                    <button class="btn-sm btn-primary d-inline-block">
                        <span class="material-icons">arrow_right</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-4">

        </div>
        <div class="col-4">
            <div class="card shadow h-100" style="background-color:#BCD2EE;">
                <div class="card-body">
                    <div class="card-title text-left">締め状態</div>
                    <div class="card-text d-inline-block" style="color:#000000;font-size:15pt">本人締め：</div>
                    <div class="card-text d-inline-block" style="color:#000000;font-size:15pt">未</div>
                    <div class="card-text d-inline-block" style="color:#000000;font-size:15pt">管理者締め：</div>
                    <div class="card-text d-inline-block" style="color:#000000;font-size:15pt">未</div>
                </div>
            </div>
        </div>
        <div class="col-2">
            <card title="締め区分" text="月末締め"></card>
        </div>
    </div>
    <div class="row" style="margin-top:20pt;">
        <div class="col-2 text-center">
            <card title="要申請" number=0 unit="件" comment="申請を行ってください！"></card>
        </div>
        <div class="col-2 text-center">
            <card title="申請中" number=1 unit="件"></card>
        </div>
        <div class="col-2 text-center">
            <card title="当月取得済み有休" number=0 unit="日" comment="5日取得義務達成まであと3日"></card>
        </div>
        <div class="col-2 text-center">
            <card title="当月残業(申請済み)" time=13:15 comment="45時間まであと31:45"></card>
        </div>
        <div class="col-2 text-center">
            <card title="当月遅刻・早退" number=0 unit="回"></card>
        </div>
        <div class="col-2 text-center">
            <card title="未取得の振替休日" number=1 unit="日" comment="振替休日を取得して下さい"></card>
        </div>
    </div>
    <div class="card shadow w-100 h-100" style="background-color:#BCD2EE;margin-top:20pt;">
        <div class="card-body">
            <!-- Rounded switch -->
            <div class="switch d-inline-block">
                <input type="checkbox">
                <span class="slider round"></span>
            </div>
            <div style="color:#000000;font-size:12pt" class="d-inline-block">
                詳細表示
            </div>
            <button style="font-size:11pt;width:100pt" class="btn btn-primary ml-3">WEB打刻一覧</button>
            <button style="font-size:11pt;width:100pt" class="btn btn-primary float-right">本人締め</button>
        </div>
    </div>
</div>
