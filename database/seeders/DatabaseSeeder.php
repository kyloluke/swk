<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(m001_information_type_seeder::class);
        $this->call(m003_company_seeder::class);
        $this->call(m008_system_administrator_seeder::class);
        $this->call(m011_authority_pattern_seeder::class);
        $this->call(m012_work_style_seeder::class);
        $this->call(m014_over_time_class_seeder::class);
        $this->call(m015_deduction_reason_seeder::class);
        $this->call(m016_close_date_seeder::class);
        $this->call(m017_rest_time_seeder::class);
        $this->call(m018_approval_state_seeder::class);
        $this->call(m019_close_state_seeder::class);
        $this->call(m021_calendar_seeder::class);
        $this->call(m025_clocking_in_out_seeder::class);
        $this->call(m026_week_seeder::class);
        $this->call(m027_work_holiday_seeder::class);
        $this->call(m028_web_punch_clock_deviation_time_seeder::class);
        $this->call(m029_theme_seeder::class);
        $this->call(m030_work_achievement_seeder::class);
        $this->call(m031_unemployed_seeder::class);
        $this->call(m032_grant_paid_leave_conditions_pattern_seeder::class);
        $this->call(m033_grant_paid_leave_pattern_seeder::class);
        $this->call(m034_36agreement_seeder::class);
        $this->call(m035_36agreement_aggregate_class_seeder::class);
        $this->call(m036_36agreement_max_time_seeder::class);
        $this->call(m037_violation_warning_seeder::class);
        $this->call(m038_violation_warning_type_seeder::class);
        $this->call(m039_prevention_overwork_check_seeder::class);
        $this->call(m040_36agreement_apply_seeder::class);
        $this->call(m041_window_name_seeder::class);
        $this->call(m042_operation_name_seeder::class);
        $this->call(m043_holiday_seeder::class);
        $this->call(m044_holiday_summary_seeder::class);
        $this->call(m046_grant_paid_leave_type_seeder::class);
        $this->call(AdminsTableSeeder::class);
    }
}
