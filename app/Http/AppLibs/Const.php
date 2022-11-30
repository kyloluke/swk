<?php
    //共通
    define('DATE_SERIAL_MAX',  2958465);  //日付シリアルMAX値（9999/12/31）
    define('WEEKLY_LEGAL_WORK_MINUTES', 40 * 60); //週法定労働時間40H（分単位）

    //対象者設定種別
    define('SETTING_TARGET_TYPE_APPROVER',   1);  //承認対象者
    define('SETTING_TARGET_TYPE_INPUTAGENT', 2);  //代理入力者

    //M018_承認状態
    define('APPROVAL_STATE_INITIAL',   1);  //初期状態
    define('APPROVAL_STATE_REQUEST',   2);  //申請中
    define('APPROVAL_STATE_DONE',      3);  //承認済み
    define('APPROVAL_STATE_REMAND',    4);  //差戻
    define('APPROVAL_STATE_TEMPORARY', 5);  //削除確認用ダミー

    //M019_締め状態
    define('CLOSE_STATE_INITIAL',    1);  //初期状態
    define('CLOSE_STATE_THEMSELVES', 2);  //本人締め
    define('CLOSE_STATE_MANAGER',    3);  //管理者締め
    define('CLOSE_STATE_OFFICE',     4);  //事業所締め
    define('CLOSE_STATE_COMPANY',    5);  //全社締め
    define('CLOSE_STATE_TO_COMPANY', 6);  //全社締め中
    define('CLOSE_STATE_DUMMY',      7);  //削除確認用ダミー

    //M027_出休
    define('WORK_HOLIDAY_NORMAL',    1);  //通常出勤
    define('WORK_HOLIDAY_SCHEDULED', 2);  //所定休日
    define('WORK_HOLIDAY_LEGAL',     3);  //法定休日
    define('WORK_HOLIDAY_AM',        4);  //午前休日
    define('WORK_HOLIDAY_PM',        5);  //午後休日
    define('WORK_HOLIDAY_DUMMY',     6);  //削除確認用ダミー

    //M037_違反警告
    define('VIOLATION_WARNING_NORMAL',    1); //正常
    define('VIOLATION_WARNING_DEVIATION', 2); //乖離
    define('VIOLATION_WARNING_VIOLATION', 3); //違反
    define('VIOLATION_WARNING_WARNING',   4); //警告
    define('VIOLATION_WARNING_DUMMY',     5); //削除確認用ダミー

    define('NATIONAL_HOLIDAYS_API',     "https://api.national-holidays.jp/"); //祝日一覧取得URL
?>