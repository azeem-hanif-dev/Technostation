<?php

use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\WeekState;
use Illuminate\Database\Seeder;

class WeekStateTableSeeder extends Seeder
{
    public function run()
    {
        $week_state = WeekState::create([
            'week_no' => 202321,
            'invoice_no' => 'INV0578',
            'delay_date' => '2023-05-05',
            'receive_date' => '2023-05-05',
            'invoice_date' => '2023-05-05',
            'status' => 'Nothing',
            'approved' => 1,
            'via_worksheet' => 0,
            'comments' => 'Nothing',
            'internal_notes' => 'Nothing',
        ]);

        $week_state->weekCards()->create([
            'personnel_id' => 1,
            'comments' => 'Heftruckchauffeur € 35,50 p/u',
        ]);
        SfWeekCard::find(1)->timeCards()->create([
            'date' => '2023-01-02',
            'day' => 'Sunday',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'total_hours' => 9,
        ]);

        $week_state = WeekState::create([
            'week_no' => 202321,
            'invoice_no' => 'INV0578',
            'delay_date' => '2023-05-05',
            'receive_date' => '2023-05-05',
            'invoice_date' => '2023-05-05',
            'status' => 'Nothing',
            'approved' => 1,
            'via_worksheet' => 0,
            'comments' => 'Nothing',
            'internal_notes' => 'Nothing',
        ]);
        $week_state->weekCards()->create([
            'personnel_id' => 1,
            'comments' => 'ddfe € 35,50 p/u',
        ]);
        SfWeekCard::find(2)->timeCards()->create([
            'date' => '2023-01-02',
            'day' => 'Sunday',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'total_hours' => 9,
        ]);

        $week_state = WeekState::create([
            'week_no' => 202321,
            'invoice_no' => 'INV0578',
            'delay_date' => '2023-05-05',
            'receive_date' => '2023-05-05',
            'invoice_date' => '2023-05-05',
            'status' => 'Nothing',
            'approved' => 1,
            'via_worksheet' => 1,
            'comments' => 'Nothing',
            'internal_notes' => 'Nothing',
        ]);
        $week_state->weekCards()->create([
            'personnel_id' => 1,
            'comments' => 'asasdwe € 35,50 p/u',
        ]);
        SfWeekCard::find(1)->timeCards()->create([
            'date' => '2023-03-02',
            'day' => 'Friday',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'total_hours' => 9,
        ]);

        $week_state = WeekState::create([
            'week_no' => 202321,
            'invoice_no' => 'INV0578',
            'delay_date' => '2023-05-05',
            'receive_date' => '2023-05-05',
            'invoice_date' => '2023-05-05',
            'status' => 'Nothing',
            'approved' => 1,
            'via_worksheet' => 0,
            'comments' => 'Nothing',
            'internal_notes' => 'Nothing',
        ]);
        $week_state->weekCards()->create([
            'personnel_id' => 1,
            'comments' => 'wrerer € 35,50 p/u',
        ]);
        SfWeekCard::find(1)->timeCards()->create([
            'date' => '2023-01-02',
            'day' => 'Sunday',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'total_hours' => 10,
        ]);

        $week_state = WeekState::create([
            'week_no' => 202321,
            'invoice_no' => 'INV0578',
            'delay_date' => '2023-05-05',
            'receive_date' => '2023-10-05',
            'invoice_date' => '2023-01-05',
            'status' => 'Nothing',
            'approved' => 1,
            'via_worksheet' => 0,
            'comments' => 'Nothing',
            'internal_notes' => 'Nothing',
        ]);
        $week_state->weekCards()->create([
            'personnel_id' => 1,
            'comments' => 'qweqweqweqw € 35,50 p/u',
        ]);
        SfWeekCard::find(1)->timeCards()->create([
            'date' => '2023-01-02',
            'day' => 'Sunday',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'total_hours' => 8,
        ]);

        for ($i = 1; $i < 6; $i++)
        {
            $week_state = WeekState::find($i);

            $week_state->projects()->attach($i);
        }
    }
}
