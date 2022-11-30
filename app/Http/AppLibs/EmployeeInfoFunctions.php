<?php

namespace App\Http\AppLibs;

use App\Models\m007_employee;
use App\Models\t018_office_history;
use App\Models\t019_work_closing_belonging_office_history;
use App\Models\t020_dept_history;
use App\Models\t021_post_history;
use App\Models\t023_employment_style_history;

class EmployeeInfoFunctions
{
    /**
     * 社員情報更新
     */
    public static function updateEmployeeInfo($employee_id, $date_serial)
    {

    }

    /**
     * 社員情報取得(履歴を含む)
     */
    public static function getEmployeeInfo($employee_id, $date_serial)
    {
        $employeeData = m007_employee::find($employee_id);
        $t018 = new t018_office_history();
        $office_history = $t018->getDataWithinTerm($employee_id, $date_serial);
        $t019 = new t019_work_closing_belonging_office_history();
        $work_closing_belonging_office_history = $t019->getDataWithinTerm($employee_id, $date_serial);
        $t020 = new t020_dept_history();
        $dept_history = $t020->getDataWithinTerm($employee_id, $date_serial);
        $t021 = new t021_post_history();
        $post_history = $t021->getDataWithinTerm($employee_id, $date_serial);
        $t023 = new t023_employment_style_history();
        $employment_style_history = $t023->getDataWithinTerm($employee_id, $date_serial);

        $employeeData->valid_date_start = 367; //履歴を持たない場合1901/1/1をデフォルトにする。1900年うるう年問題のため
        //指定基準日の履歴が存在したら履歴の値を返す(有効期限はどれも同じなので代表でofficeから取得)
        if($office_history->isNotEmpty()){
            $employeeData->office_id = $office_history[0]->office_id;
            $employeeData->office->office_id = $office_history[0]->office_id;
            $employeeData->office->office_code = $office_history[0]->office_code;
            $employeeData->office->office_name = $office_history[0]->office_name;
            $employeeData->office->office_short_name = $office_history[0]->office_short_name;
            $employeeData->valid_date_start = $office_history[0]->valid_date_start;
            $employeeData->valid_date_end = $office_history[0]->valid_date_end;
        }else{
            $employeeData->office;
        }
        if($work_closing_belonging_office_history->isNotEmpty()){
            $employeeData->work_closing_belonging_office_id = $work_closing_belonging_office_history[0]->office_id;
        }else{
            $employeeData->work_closing_belonging_office_id;
        }
        if($dept_history->isNotEmpty()){
            $employeeData->dept_id = $dept_history[0]->dept_id;
            $employeeData->dept->dept_id = $dept_history[0]->dept_id;
            $employeeData->dept->dept_code = $dept_history[0]->dept_code;
            $employeeData->dept->dept_name = $dept_history[0]->dept_name;
            $employeeData->dept->dept_short_name = $dept_history[0]->dept_short_name;
        }else{
            $employeeData->dept;
        }
        if($post_history->isNotEmpty()){
            $employeeData->post_id = $post_history[0]->post_id;
            $employeeData->post->post_id = $post_history[0]->post_id;
            $employeeData->post->post_code = $post_history[0]->post_code;
            $employeeData->post->post_name = $post_history[0]->post_name;
            $employeeData->post->post_short_name = $post_history[0]->post_short_name;
        }else{
            $employeeData->post;
        }
        if($employment_style_history->isNotEmpty()){
            $employeeData->employment_style_id = $employment_style_history[0]->employment_style_id;
            $employeeData->employment_style->employment_style_id = $employment_style_history[0]->employment_style_id;
            $employeeData->employment_style->employment_style_name = $employment_style_history[0]->employment_style_name;
            $employeeData->employment_style->employment_style_short_name = $employment_style_history[0]->employment_style_short_name;
        }else{
            $employeeData->employment_style;
        }

        return $employeeData;
    }
}