<?php

use App\Models\Role;
use App\Models\StaffingCompany\Contact;
use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\UserRight;
use App\Models\StaffingCompany\WeekState;
use App\Models\User;
use Carbon\Carbon;

function getDay($element_id, $task_id, $job_id, $day = 'monday')
{
    $day = \DB::table('days')->where('element_id', $element_id)
        ->where('days', $day)->where('job_id', $job_id)->where('task_id', $task_id)->first();
    if (is_null($day)) {
        return '';
    } else {
        return "X";
    }
}

function _getPastYears($number): array
{
    $current_year = Carbon::now()->year;

    $past_years = [];

    for ($year = $current_year; $year >= $current_year - 10; $year--) {
        $past_years[] = $year;
    }

    return $past_years;
}

function _user()
{
    return \Auth::user();
}

function _isEmployeeInMoreThanOneProject($employeeId, $date): bool
{
    $formatted_date = Carbon::parse($date)->format('Y-m-d');
    $planningCount = EmployeeProjectPlanning::where('employee_id', $employeeId)
        ->where('planning_delete', 0)
        ->whereHas('projectPlanning', function ($query) use ($formatted_date) {
            $query->whereDate('date', $formatted_date);
        })
        ->count();
    return $planningCount > 1;
}


function _isUserRightToSee($right_id): bool
{
    $user_id = \Auth::user()->id;


    if (!$user_right) {
        return false;
    }

    $user_right_ids = $user_right->rightsList()->pluck('id')->unique()->toArray();

    return in_array($right_id, $user_right_ids);
}

function _isUserAdmin(): bool
{
    $user = \Auth::user();

    $staffing_company = Role::where('name', 'StaffingCompany')->first();

    if ($user->role_id == $staffing_company->id) {
        return true;
    } else {
        return false;
    }
}


function _currentDateTime(): string
{
    return Carbon::now()->format('Y-m-d H:i');
}

function _currentWeekNo(): string
{
    $current_date = Carbon::now();
    $year = $current_date->year;
    $week = $current_date->weekOfYear;
    $week_no = $week;

    if ($week < 10) {
        $week_no = '0' . $week;
    }

    return $year . $week_no;
}

function _weekNos(): array
{
    $week_nos = [];

    for ($i = 1; $i <= 52; $i++) {
        $week_no = $i;
        if ($i < 10) {
            $week_no = '0' . $i;
        }
        $week_nos[] = $week_no;
    }

    return $week_nos;
}

function _getProjectNameById($id)
{
    return StaffingProject::find($id);
}

function _getProjectOnlyOpenWeekStates($project)
{
    return $project->weekStates()->where('approved', 0)->get();
}

function _getWeekStateWeekCards($week_state_id, $week_no, $agency_id)
{
    $week_state = WeekState::find($week_state_id);
    return $week_state->weekCards()
        ->where('week_no', $week_no)
        ->whereHas('personnel', function ($query) use ($agency_id) {
            $query->where('employ_agency_id', $agency_id);
        })
        ->get();
}



function _formatPhoneNumber($phone)
{
    // Remove all non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (substr($phone, 0, 2) === '06' && strlen($phone) === 10) {
        return substr($phone, 0, 2) . '-' . substr($phone, 2);
    }
    return $phone;
}

// function _formatPhoneNumber($phone)
// {

//     $phone = preg_replace('/[^0-9]/', '', $phone);
//     if (substr($phone, 0, 2) === '06' && strlen($phone) === 10) {
//         return substr($phone, 0, 2) . '-' . substr($phone, 2);
//     }
//     return $phone;
// }

function _supervisor()
{
    $user = _user();
    $supervisor = Contact::where('email', $user->email)->first();
    return $supervisor;
}

function _personnel()
{
    $user = _user('role_id', 3);
    $personnel = Personnel::where('email', $user->email)->first();
    return $personnel;
}
