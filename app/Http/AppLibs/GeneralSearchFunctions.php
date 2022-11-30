<?php

namespace App\Http\AppLibs;

use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m007_employee;
use App\Models\m013_employment_style;
use App\Models\m014_over_time_class;
use App\Models\m015_deduction_reason;
use App\Models\m016_close_date;
use App\Models\m018_approval_state;
use App\Models\m019_close_state;
use App\Models\m023_work_zone;
use App\Models\m027_work_holiday;
use App\Models\m030_work_achievement;
use App\Models\m031_unemployed;
use App\Models\m034_36agreement;
use App\Models\m036_36agreement_max_time;
use App\Models\m037_violation_warning;
use App\Models\m040_36agreement_apply;
use App\Models\m044_holiday_summary;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t007_over_time_achievement;
use App\Models\t008_unemployed_information;
use App\Models\t010_acquired_holiday;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;

class GeneralSearchFunctions
{
    /**
     * 汎用検索用のリスト保持関数
     * マルチテナント化はあまり考えていない
     */
    public function defineGeneralSearchList()
    {
        $m031_unemployed = new m031_unemployed();
        $unemployed_data = $m031_unemployed->getData();
        $unemployed_array = array();
        //有効なIDを元に配列を作り直し
        foreach($unemployed_data as $unemployed)
        {
            $unemployed_array += [
                $unemployed['unemployed_id'] => [
                    'id' => $unemployed['unemployed_id'],
                    'displayName' => $unemployed['unemployed_name'],
                    //不就業マスタ内での集計方法追加する場合はここ
                ]
            ];
        }
        //時間外
        $m014_over_time_class = new m014_over_time_class();
        $over_time_data = $m014_over_time_class->getData();
        $over_time_array = array();
        $over_time_array +=[
            0 => [
                'id' => 0,
                'displayName' => "時間外合計",
            ]
        ];
        foreach($over_time_data as $over_time)
        {
            $over_time_array += [
                $over_time['over_time_class_id'] => [
                    'id' => $over_time['over_time_class_id'],
                    'displayName' => $over_time['over_time_class_name'],
                ]
            ];
        }
        //控除
        $m015_deduction_reason = new m015_deduction_reason();
        $deduction_time_data = $m015_deduction_reason->getData();
        $deduction_time_array = array();
        foreach($deduction_time_data as $deduction_time)
        {
            $deduction_time_array += [
                $deduction_time['deduction_reason_id'] => [
                    'id' => $deduction_time['deduction_reason_id'],
                    'displayName' => $deduction_time['deduction_reason'],
                ]
            ];
        }
        //DB指定リスト　※クライアントへ返却するときはcolumn名を削除する
        $BASE_LIST_DB = [
            //kind = 0 (m007_employee)
            0 => [
                'unitType' => 0,
                'displayName' => "社員情報",
                'getData' => function($id, $date){
                    $func = new EmployeeInfoFunctions();
                    return $func->getEmployeeInfo($id, $date);
                },
                'columns' => [
                    0 => [
                        'displayName' => '社員番号',
                        'column' => 'employee_code',
                        'type' => 'text',
                        'child' => null,
                    ],
                    1 => [
                        'displayName' => '氏名',
                        'column' => 'employee_name',
                        'type' => 'text',
                        'child' => null,
                    ],
                    2 => [
                        'displayName' => '事業所',
                        'column' => 'office_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $office = m004_office::find($id);
                            return $office == null ? "" : $office->office_name;
                        }
                    ],
                    3 => [
                        'displayName' => '所属名称',
                        'column' => 'dept_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $dept = m005_dept::find($id);
                            return $dept == null ? "" : $dept->dept_short_name;
                        }
                    ],
                    4 => [
                        'displayName' => '所属コード',
                        'column' => 'dept_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $dept = m005_dept::find($id);
                            return $dept == null ? "" : $dept->dept_code;
                        }
                    ],
                    5 => [
                        'displayName' => '締日',
                        'column' => 'close_date_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $close_date = m016_close_date::find($id);
                            return $close_date == null ? "" : $close_date->close_date_name;
                        }
                    ],
                    6 => [
                        'displayName' => '雇用区分名称',
                        'column' => 'employment_style_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $employment_style = m013_employment_style::find($id);
                            return $employment_style == null ? "" : $employment_style->employment_style_name;
                        }
                    ],
                    7 => [
                        'displayName' => '週所定日数',
                        'column' => 'week_scheduled_working_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    8 => [
                        'displayName' => '所定労働時間',
                        'column' => 'scheduled_working_hours',
                        'type' => 'time',
                        'child' => null,
                    ],
                ],
            ],
            //kind = 1 (t002_attendance_information) 
            1 => [
                'unitType' => 1,
                'displayName' => "基本",
                'getData' => function($employee_id, $target_date){
                    $model_t002_attendance_information = new t002_attendance_information();
                    return $model_t002_attendance_information->getAttendanceInformationByDate($employee_id, $target_date);
                },
                'columns' => [
                    0 => [
                        'displayName' => '年月日',
                        'column' => 'attendance_date',
                        'type' => 'date',
                        'child' => null,
                    ],
                    1 => [
                        'displayName' => '実績',
                        'column' => 'work_achievement_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $work_achievement = m030_work_achievement::find($id);
                            return $work_achievement == null ? "" : $work_achievement -> work_achievement_name;
                        }
                    ],
                    2 => [
                        'displayName' => '違反警告',
                        'column' => 'violation_warning_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $violation_warning = m037_violation_warning::find($id);
                            return $violation_warning == null ? "" : $violation_warning -> violation_warning_name;
                        }
                    ],
                    3 => [
                        'displayName' => '出休',
                        'column' => 'work_holiday_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $work_holiday = m027_work_holiday::find($id);
                            return $work_holiday == null ? "" : $work_holiday -> work_holiday_name;
                        }
                    ],
                    4 => [
                        'displayName' => '承認状態',
                        'column' => 'approval_state_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $approval_state = m018_approval_state::find($id);
                            return $approval_state == null ? "" : $approval_state -> approval_state_name;
                        }
                    ],
                    5 => [
                        'displayName' => '実績勤務帯',
                        'column' => 'work_zone_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $work_zone = m023_work_zone::find($id);
                            return $work_zone == null ? "" : $work_zone -> work_zone_name;
                        }
                    ],
                    6 => [
                        'displayName' => '実績勤務帯区分',
                        'column' => 'work_zone_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $work_zone = m023_work_zone::find($id);
                            return $work_zone == null ? "" : $work_zone -> work_zone_code;
                        }
                    ],
                    7 => [
                        'displayName' => '所定始業時刻',
                        'column' => 'work_zone_time_start',
                        'type' => 'time',
                        'child' => null,
                    ],
                    8 => [
                        'displayName' => '所定終業時刻',
                        'column' => 'work_zone_time_end',
                        'type' => 'time',
                        'child' => null,
                    ],
                    9 => [
                        'displayName' => '始業時刻',
                        'column' => 'work_time_start',
                        'type' => 'time',
                        'child' => null,
                    ],
                    10 => [
                        'displayName' => '終業時刻',
                        'column' => 'work_time_end',
                        'type' => 'time',
                        'child' => null,
                    ],
                    11 => [
                        'displayName' => '実働時間',
                        'column' => 'actual_work_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    12 => [
                        'displayName' => '打刻始業時刻',
                        'column' => 'web_punch_clock_time_start',
                        'type' => 'time',
                        'child' => null,
                    ],
                    13 => [
                        'displayName' => '打刻終業時刻',
                        'column' => 'web_punch_clock_time_end',
                        'type' => 'time',
                        'child' => null,
                    ],
                    14 => [
                        'displayName' => '法定内時間外時間',
                        'column' => 'statutory_working_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    15 => [
                        'displayName' => '法定外時間外時間',
                        'column' => 'non_statutory_working_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    16 => [
                        'displayName' => '深夜時間',
                        'column' => 'midnight_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    17 => [
                        'displayName' => '休憩時間',
                        'column' => 'break_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    18 => [
                        'displayName' => '休憩深夜時間',
                        'column' => 'midnight_break_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    19 => [
                        'displayName' => '休憩休日',
                        'column' => 'holiday_work_break_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    20 => [
                        'displayName' => '休憩休日深夜',
                        'column' => 'holiday_midnight_work_break_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    21 => [
                        'displayName' => '控除時間',
                        'column' => 'deduction_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    22 => [
                        'displayName' => '不就業時間',
                        'column' => 'unemployed_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    23 => [
                        'displayName' => '休日勤務時間',
                        'column' => 'holiday_work_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    24 => [
                        'displayName' => '休日深夜時間',
                        'column' => 'holiday_midnight_work_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    25 => [
                        'displayName' => '欠勤時間',
                        'column' => 'absent_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    26 => [
                        'displayName' => '休振/振休事由',
                        'column' => 'substitute_holiday_reason',
                        'type' => 'text',
                        'child' => null,
                    ],
                    27 => [
                        'displayName' => '連絡事項・事由',
                        'column' => 'information',
                        'type' => 'text',
                        'child' => null,
                    ],
                    28 => [
                        'displayName' => '差戻理由',
                        'column' => 'remand_reason',
                        'type' => 'text',
                        'child' => null,
                    ],
                    29 => [
                        'displayName' => '申請年月日',
                        'column' => 'approval_request_date',
                        'type' => 'date',
                        'child' => null,
                    ],
                    30 => [
                        'displayName' => '入力者',
                        'column' => 'input_employee_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $employee = m007_employee::find($id);
                            return $employee == null ? "" : $employee -> employee_name;
                        }
                    ],
                    31 => [
                        'displayName' => '承認者',
                        'column' => 'approval_employee_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $employee = m007_employee::find($id);
                            return $employee == null ? "" : $employee -> employee_name;
                        }
                    ],
                    32 => [
                        'displayName' => '締め状態',
                        'column' => 'close_state_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $close_state = m019_close_state::find($id);
                            return $close_state == null ? "" : $close_state -> close_state_name;
                        }
                    ],
                ],
            ],
            2 => [
                'unitType' => 2,
                'displayName' => "基本",
                'getData' => function($employee_id, $attendance_year_month){
                    $t003_attendance_aggregate = new t003_attendance_aggregate();
                    return $t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $attendance_year_month);
                },
                'columns' => [
                    0 => [
                        'displayName' => '年月',
                        'column' => 'attendance_year_month',
                        'type' => 'text',
                        'child' => null,
                    ],
                    1 => [
                        'displayName' => '所定就業日数',
                        'column' => 'scheduled_working_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    2 => [
                        'displayName' => '実働日数',
                        'column' => 'actual_working_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    3 => [
                        'displayName' => '休日出勤日数',
                        'column' => 'holiday_working_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    4 => [
                        'displayName' => '実労働時間',
                        'column' => 'actual_working_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    5 => [
                        'displayName' => '法定内時間外時間',
                        'column' => 'statutory_working_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    6 => [
                        'displayName' => '法定外時間外時間',
                        'column' => 'non_statutory_working_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    7 => [
                        'displayName' => '控除時間',
                        'column' => 'deduction_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    8 => [
                        'displayName' => '休日勤務時間',
                        'column' => 'holiday_work_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    9 => [
                        'displayName' => '深夜時間',
                        'column' => 'midnight_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    10 => [
                        'displayName' => '60時間超過',
                        'column' => 'over_60hours',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    11 => [
                        'displayName' => '有休取得日数',
                        'column' => 'acquired_paid_leave_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    12 => [
                        'displayName' => '有休取得半日数',
                        'column' => 'acquired_paid_leave_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    13 => [
                        'displayName' => '有休残日数',
                        'column' => 'remaining_paid_leave_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    14 => [
                        'displayName' => '有休残半日数',
                        'column' => 'remaining_paid_leave_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    15 => [
                        'displayName' => '有休遅刻早退回数',
                        'column' => 'paid_late_early_leave',
                        'type' => 'text',
                        'child' => null,
                    ],
                    16 => [
                        'displayName' => '遅早回数',
                        'column' => 'early_leave_late_arrival_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    17 => [
                        'displayName' => '遅早回数(欠勤)',
                        'column' => 'early_leave_late_arrival_days_absent',
                        'type' => 'text',
                        'child' => null,
                    ],
                    18 => [
                        'displayName' => '特別休暇日数(有給)',
                        'column' => 'special_paid_holiday_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    19 => [
                        'displayName' => '特別休暇半日数(有給)',
                        'column' => 'special_paid_holiday_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    20 => [
                        'displayName' => '特別休暇日数(無給)',
                        'column' => 'special_non_paid_holiday_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    21 => [
                        'displayName' => '特別休暇半日数(無給)',
                        'column' => 'special_non_paid_holiday_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    22 => [
                        'displayName' => '保存休取得日数',
                        'column' => 'accumulated_paid_leave_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    23 => [
                        'displayName' => '保存休取得半日数',
                        'column' => 'accumulated_paid_leave_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    24 => [
                        'displayName' => '保存休残日数',
                        'column' => 'unused_accumulated_paid_leave_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    25 => [
                        'displayName' => '保存休残半日数',
                        'column' => 'unused_accumulated_paid_leave_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    26 => [
                        'displayName' => '振替休日取得日数',
                        'column' => 'acquired_substitute_holidays',
                        'type' => 'text',
                        'child' => null,
                    ],
                    27 => [
                        'displayName' => '欠勤日数',
                        'column' => 'absent_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    28 => [
                        'displayName' => '欠勤半日数',
                        'column' => 'absent_half_days',
                        'type' => 'text',
                        'child' => null,
                    ],
                    29 => [
                        'displayName' => '欠勤時間',
                        'column' => 'absent_time',
                        'type' => 'hours',
                        'child' => null,
                    ],
                    30 => [
                        'displayName' => '締め状態',
                        'column' => 'close_state_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $close_state = m019_close_state::find($id);
                            return $close_state == null ? "" : $close_state->close_state_name;
                        }
                    ],
                    31 => [
                        'displayName' => '本人締め実施者',
                        'column' => 'close_employee_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $employee = m007_employee::find($id);
                            return $employee == null ? "" : $employee->employee_name;
                        }
                    ],
                    32 => [
                        'displayName' => '管理者締め実施者',
                        'column' => 'close_manager_employee_id',
                        'type' => 'text',
                        'child' => function($id) {
                            $employee = m007_employee::find($id);
                            return $employee == null ? "" : $employee->employee_name;
                        }
                    ],
                    33 => [
                        'displayName' => '36協定特別条項適用区分',
                        'column' => 'thirtysix_agreement_special_provisions_apply_class',
                        'type' => 'text',
                        'child' => function($id) {
                            $thirtysix_apply = m040_36agreement_apply::find($id);
                            return $thirtysix_apply == null ? "" : $thirtysix_apply->thirtysix_agreement_apply_name;
                        }
                    ],
                ],
            ],
            //以降はオプション
            //データの取得はemployeeのループ→attendance_informationのループ→オプションのループとなることを想定する
            //オプション取得するときには、attendance_information_idが確定している
            3 => [
                'unitType' => 1,
                'displayName' => "不就業",
                'getData' => function($attendance_information_id){
                    $t008_unemployed_information = new t008_unemployed_information();
                    return $t008_unemployed_information->getUnployedInformation($attendance_information_id);
                },
                'columns' => $unemployed_array,
                //全マスタが有効とすると、getNameByIDみたいなもの？　要は不就業情報からクライアント指定のIDでフィルターかけられるとベスト
                //つまり、フローは・・・
                //該当attendance_information_idのすべての不就業を取得
                //　クライアントから指定した不就業マスタIDと一致するものを表示対象とする
                //　　指定あったものでデータなかったものは0表示
                //　　指定無かったものは表示しない
                //ということは、1.不就業IDに一致する数の取得関数、2.不就業IDに一致する時間数の取得関数
                'totalCount' => function($info, $unemployed_id){
                    $count = 0;
                    foreach($info as $item)
                    {
                        if($item->unemployed_id == $unemployed_id)
                        {
                            $count++;
                        }
                    }
                    return $count;
                },
                'totalTimeSerial' => function($info, $unemployed_id){
                    $timeSerial = 0;
                    foreach($info as $item)
                    {
                        if($item->unemployed_id == $unemployed_id)
                        {
                            $timeSerial += $item->unemployed_time;
                        }
                    }
                    return $timeSerial;
                },
                //1行ずつ出したいときには個別で取得できるものも必要（getDataした後にループさせる想定）
                'count' => function($item, $unemployed_id){
                    return $item->unemployed_id == $unemployed_id ? 1 : 0;
                },
                'timeSerial' => function($item, $unemployed_id){
                    return $item->unemployed_id == $unemployed_id ? $item->unemployed_time : 0;
                }
            ],
            4 => [
                'unitType' => 1,
                'displayName' => "不就業",
                'getData' => function($attendance_information_id){
                    $t008_unemployed_information = new t008_unemployed_information();
                    return $t008_unemployed_information->getUnployedInformation($attendance_information_id);
                },
                'columns' => [
                    0 => [
                        'id' => 0,
                        'displayName' => "不就業事由1",
                    ],
                    1 => [
                        'id' => 1,
                        'displayName' => "不就業事由2",
                    ],
                    2 => [
                        'id' => 2,
                        'displayName' => "不就業事由3",
                    ],
                ],
                'indexedText' => function($info, $index)
                {
                    if($index + 1 <= count($info))
                    {
                        return $info[$index]->request_reason;
                    }
                    else
                    {
                        return "";
                    }
                }
            ],
            5 => [
                'unitType' => 1,
                'displayName' => "時間外",
                'getData' => function($attendance_information_id){
                    $t007_over_time_achievement = new t007_over_time_achievement();
                    return $t007_over_time_achievement->getOverTimeAchievementInformation($attendance_information_id);
                },
                'columns' => $over_time_array,
                'totalCount' => function($info, $over_time_class_id){
                    $count = 0;
                    foreach($info as $item)
                    {
                        if($over_time_class_id == 0)
                        {
                            //ID0は合計
                            $count++;
                        }
                        if($item->over_time_class_id == $over_time_class_id)
                        {
                            $count++;
                        }
                    }
                    return $count;
                },
                'totalTimeSerial' => function($info, $over_time_class_id){
                    $timeSerial = 0;
                    foreach($info as $item)
                    {
                        if($over_time_class_id == 0)
                        {
                            //ID0は合計
                            $timeSerial += ($item->over_time_end - $item->over_time_start - $item->over_time_rest_time - $item->over_time_midnight_rest_time - $item->deduction_time);
                        }
                        else if($item->over_time_class_id == $over_time_class_id)
                        {
                            //開始・終了・休憩・深夜休憩・控除時間を評価
                            $timeSerial += ($item->over_time_end - $item->over_time_start - $item->over_time_rest_time - $item->over_time_midnight_rest_time - $item->deduction_time);
                        }
                    }
                    return $timeSerial;
                },
                'count' => function($item, $over_time_class_id){
                    return $item->over_time_class_id == $over_time_class_id ? 1 : 0;
                },
                'timeSerial' => function($item, $over_time_class_id){
                    return $item->over_time_class_id == $over_time_class_id ? ($item->over_time_end - $item->over_time_start - $item->over_time_rest_time - $item->over_time_midnight_rest_time - $item->deduction_time) : 0;
                }
            ],
            6 => [
                'unitType' => 1,
                'displayName' => "時間外",
                'getData' => function($attendance_information_id){
                    $t007_over_time_achievement = new t007_over_time_achievement();
                    return $t007_over_time_achievement->getOverTimeAchievementInformation($attendance_information_id);
                },
                'columns' => [
                    0 => [
                        'id' => 0,
                        'displayName' => "時間外事由1",
                    ],
                    1 => [
                        'id' => 1,
                        'displayName' => "時間外事由2",
                    ],
                    2 => [
                        'id' => 2,
                        'displayName' => "時間外事由3",
                    ],
                ],
                'indexedText' => function($info, $index)
                {
                    if($index + 1 <= count($info))
                    {
                        return $info[$index]->over_time_reason;
                    }
                    else
                    {
                        return "";
                    }
                }
            ],
            7 => [
                'unitType' => 1,
                'displayName' => "時間外",
                'getData' => function($employee_id, $target_date){
                    return $this->getHolidayWorkTimeByDay($employee_id, $target_date);
                },
                'columns' => [
                    0 => [
                        'id' => 0,
                        'displayName' => "法定外休日勤務時間",
                        'column' => "non_statutory_holiday_work_time",
                        'type' => 'hours',
                        'child' => null,
                    ],
                    1 => [
                        'id' => 1,
                        'displayName' => "法定休日勤務時間",
                        'column' => "statutory_holiday_work_time",   
                        'type' => 'hours',
                        'child' => null,
                    ],
                ],
            ],
            8 => [
                'unitType' => 1,
                'displayName' => "控除",
                'getData' => function($attendance_information_id){
                    $t007_over_time_achievement = new t007_over_time_achievement();
                    return $t007_over_time_achievement->getOverTimeAchievementInformation($attendance_information_id);
                },
                'columns' => $deduction_time_array,
                'totalCount' => function($info, $deduction_reason_id){
                    $count = 0;
                    foreach($info as $item)
                    {
                        if($item->deduction_reason_id == $deduction_reason_id)
                        {
                            $count++;
                        }
                    }
                    return $count;
                },
                'totalTimeSerial' => function($info, $deduction_reason_id){
                    $timeSerial = 0;
                    foreach($info as $item)
                    {
                        if($item->deduction_reason_id == $deduction_reason_id)
                        {
                            $timeSerial += $item->deduction_time;
                        }
                    }
                    return $timeSerial;
                },
                'count' => function($item, $deduction_reason_id){
                    return $item->deduction_reason_id == $deduction_reason_id ? 1 : 0;
                },
                'timeSerial' => function($item, $deduction_reason_id){
                    return $item->deduction_reason_id == $deduction_reason_id ? $item->deduction_time : 0;
                }
            ],
            9 => [
                'unitType' => 1,
                'displayName' => "控除",
                'getData' => function($attendance_information_id){
                    $t007_over_time_achievement = new t007_over_time_achievement();
                    return $t007_over_time_achievement->getOverTimeAchievementInformation($attendance_information_id);
                },
                'columns' => [
                    0 => [
                        'id' => 0,
                        'displayName' => "控除事由1",
                    ],
                    1 => [
                        'id' => 1,
                        'displayName' => "控除事由2",
                    ],
                    2 => [
                        'id' => 2,
                        'displayName' => "控除事由3",
                    ],
                ],
                'indexedText' => function($info, $index)
                {
                    if($index + 1 <= count($info))
                    {
                        return $info[$index]->deduction_reason;
                    }
                    else
                    {
                        return "";
                    }
                }
            ],
            10 => [
                'unitType' => 0,
                'displayName' => "休暇",
                'getData' => function($id, $target_date = null){
                    return $this->getPaidLeaveDays($id, $target_date);
                },
                'columns' => [
                    0 => [
                        'id' => 0,
                        'displayName' => "有給休暇取得日数",
                        'column' => "paid_leave_days",
                        'type' => 'text',
                        'child' => null,
                    ],
                ],
            ],
            11 => [
                'unitType' => 1,
                'displayName' => "給与連携",
                'getData' => function($id){
                    return $this->getWageAttendanceTime($id);
                },
                'columns' => [
                    0 => [
                        'id' => 0,
                        'displayName' => "時給者出勤時間",
                        'column' => "wage_attendance_time",
                        'type' => 'hours',
                        'child' => null,
                    ],
                ],
            ],
            
            //休暇情報などの複数カラム合計が必要な場合の実装は検討中
            //GeneralSearch.phpにも実装検討を残している
            // 5 => [
            //     'displayName' => "休暇",
            //     'getData' => function($attendance_information_id){
            //         $t010_acquired_holiday = new t010_acquired_holiday();
            //         return $t010_acquired_holiday->getAcauiredHoliday($attendance_information_id);
            //     },
            //     'columns' => [
            //         0 => [
            //             'displayName' => '休暇日数',
            //             'columns' => [
            //                 ['column' => 'acquired_holiday_days', 'scale' => 1],
            //                 ['column' => 'acquired_holiday_half_days', 'scale' => 0.5],
            //             ],
            //             'type' => 'count',
            //             'child' => null,
            //         ],
            //     ],
            //     'totalDays' => function($info, $unemployed_id){
            //         $count = 0;
            //         foreach($info as $item)
            //         {
            //             if($item->unemployed_id == $unemployed_id)
            //             {
            //                 $count++;
            //             }
            //         }
            //         return $count;
            //     },
            //     'count' => function($item, $unemployed_id){
            //         return $item->unemployed_id == $unemployed_id ? 1 : 0;
            //     },
            // ],
        ];
        return $BASE_LIST_DB;
    }


     /**
     * Get holiday work time by day
     * 
     * @param int $employee_id,
     * @param double $target_date
     */

    public function getHolidayWorkTimeByDay($employee_id, $target_date){

        $model_t002_attendance_information = new t002_attendance_information();
        $info =  $model_t002_attendance_information->getAttendanceInformationByDate($employee_id, $target_date);

        // [時間外]法定休日勤務時間
        $statutory_holiday_work_time = 0;
        // [時間外]法定外休日勤務時間
        $non_statutory_holiday_work_time = 0;

        // [基本]休日勤務時間（[基本]出休が”所定休日”でフィルタ）
        if($info->work_holiday_id == 2){
            $non_statutory_holiday_work_time = $info->holiday_work_time;
        }

        // [基本]休日勤務時間（[基本]出休が”法定休日”でフィルタ）
        else if($info->work_holiday_id == 3){
            $statutory_holiday_work_time = $info->holiday_work_time;
        }

        return (object)[
            'non_statutory_holiday_work_time' => $non_statutory_holiday_work_time,
            'statutory_holiday_work_time' => $statutory_holiday_work_time,
        ];

     }

     
    /**
     * Get paid leave days (day/month)
     * 
     * @param int $target_id (employee_id or attendance_information_id),
     * @param int $yearMonth
     */
    public function getPaidLeaveDays($target_id, $yearMonth = null){

        $paid_leave_days = 0;

        //select day
        if($yearMonth == null){
            
            $t008_unemployed_information = new t008_unemployed_information();
            $info = $t008_unemployed_information->getUnployedInformation($target_id);

            //[不就業]有休[回数]
            $count_paid_holidays = 0;
            //[不就業]半休[回数]
            $count_half_holiday = 0;
            
            $paid_holidays_id = 1; //[不就業]有休[回数]ID
            $half_holiday_id = 2; //[不就業]半休[回数]ID

            // count holidays
            foreach($info as $item)
            {
                if($item->unemployed_id == $paid_holidays_id)
                {
                    $count_paid_holidays++;
                }else if($item->unemployed_id == $half_holiday_id){
                    $count_half_holiday++;
                }
            }

            // calculate paid leave days
            // 日：[不就業]有休[回数]+[不就業]半休[回数]×0.5
            $paid_leave_days = $count_paid_holidays + $count_half_holiday * 0.5; 

        }
         //select month
        else{
            $t003_attendance_aggregate = new t003_attendance_aggregate();
            $item = $t003_attendance_aggregate->getAttendanceAggregateWithinTerm($target_id, $yearMonth);

            // [基本]有休取得日数
            $acquired_paid_leave_days = $item->acquired_paid_leave_days;

            // [基本]有休取得半日数
            $acquired_paid_leave_half_days = $item->acquired_paid_leave_half_days;

            // calculate paid leave days 
            // 月：[基本]有休取得日数＋[基本]有休取得半日数×0.5
            $paid_leave_days = $acquired_paid_leave_days + $acquired_paid_leave_half_days * 0.5;
        }

        return (object)[
            'paid_leave_days' => $paid_leave_days,
        ];
    }
    
    /**
    * Get hourly wage attendance time
    *
    * @param int $attendance_information_id
    *
    */
    public function getWageAttendanceTime($attendance_information_id){
        
        $wage_attendance_time = 0;


        // 実働時間から時間外時間（法定内も）を除いた時間
        $t008_unemployed_information = new t008_unemployed_information();
        $non_working_paid = $t008_unemployed_information->getUnployedInformation($attendance_information_id);

        foreach($non_working_paid as $item)
        {
            if(//「不就業」天災／交通事情休暇（有給）「時間」ID
                $item->unemployed_id == 21
                //「不就業」特別休暇（そのほかの有給）「時間」ID
                || $item->unemployed_id == 25
                //「不就業」時間内活動（支給あり）「時間」ID
                || $item->unemployed_id == 27
                //「不就業」休識（その他の有給）「時間」 ID
                || $item->unemployed_id == 41)
            {
                $wage_attendance_time += $item->unemployed_time;
            }
        }
        // 不就業の有給対象区分が有給の場合の不就業時間
        $t007_over_time_achievement = new t007_over_time_achievement();
        $over_time = $t007_over_time_achievement->getOverTimeAchievementInformation($attendance_information_id);

        foreach($over_time as $item){
            // [時間外]時間外合計[時間]
            $wage_attendance_time += ($item->over_time_end - $item->over_time_start - $item->over_time_rest_time - $item->over_time_midnight_rest_time - $item->deduction_time);
        }

        return (object)[
            'wage_attendance_time' => $wage_attendance_time,
        ];
    }
}