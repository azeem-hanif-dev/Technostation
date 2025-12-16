<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\EmployAgency;
use App\Models\EmploymentAgencyOverview;
use App\Models\StaffingCompany\Comment;
use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\WeekState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class EmploymentAgencyOverviewController extends Controller
{
    public function index()
    {
        $week_no = _currentWeekNo();

        $agencies = EmployAgency::whereHas('personnels.weekCards', function ($query) use ($week_no) {
            $query->where('week_no', $week_no);
        })->with(['personnels.weekCards' => function ($query) use ($week_no) {
            $query->where('week_no', $week_no);
        }])->get();

        foreach ($agencies as $agency) {
            $totalHours = 0;
            $totalCost = 0;

            foreach ($agency->personnels as $person) {
                $hourlyRate = $person->cost_per_hour ?? 0;

                foreach ($person->weekCards as $weekcard) {
                    $hours = $weekcard->total_hours ?? 0;
                    $totalHours += $hours;
                    $totalCost += $hourlyRate * $hours;
                }
            }

            $agency->total_hours = $totalHours;
            $agency->total_cost = $totalCost;
        }
        $years = _getPastYears(10);

        $agency_overviews_details = EmploymentAgencyOverview::latest()->get();

        return view('StaffingCompany.AgencyOverview.index', compact(
            'agencies',
            'week_no',
            'agency_overviews_details',
            'years'
        ));
    }

    public function agencyWeekState($id, $week_no)
    {
        $agency = EmployAgency::find($id);

        $staff_ids = $agency->personnels()->pluck('id');
        $start_date = $this->getStartAndEndDateOfWeek($week_no)['week_start'];
        $end_date = $this->getStartAndEndDateOfWeek($week_no)['week_end'];

        $week_state_ids = SfWeekCard::whereIn('personnel_id', $staff_ids)->where('week_no', $week_no)->pluck('week_state_id')->unique()->toArray();
        $week_cards = SfWeekCard::whereIn('week_state_id', $week_state_ids)
            ->where('week_no', $week_no)
            ->whereHas('personnel', function ($query) use ($id) {
                $query->where('employ_agency_id', $id);
            })->latest()
            ->get();
        $week_state_ids = implode(', ', $week_state_ids);

        return view('StaffingCompany.AgencyOverview.agency_week_state', compact(
            'week_cards',
            'week_no',
            'agency',
            'start_date',
            'week_state_ids',
            'end_date'
        ));
    }
    //new
    public function create($ids, $agency_id, $week_no)
    {

        $personnels = Personnel::where('employ_agency_id', $agency_id)->get();
        //$personnels = Personnel::all();
        $comments = Comment::all();
        $translations = __('Staffing_Company/Week_State/crud');
        $week_state_over_view = EmploymentAgencyOverview::with('documents')->where('employ_agency_id', $agency_id)->where('week_no', $week_no)->first();
        $ids = explode(',', $ids);

        //        $week_cards = SfWeekCard::whereIn('week_state_id', $ids)
        //            ->where('week_no', $week_no)
        //            ->whereHas('personnel', function ($query) use ($agency_id) {
        //                $query->where('employ_agency_id', $agency_id);
        //            })
        //            ->get();
        $week_cards = SfWeekCard::selectRaw('
    MAX(id) as id,
    MAX(week_state_id) as week_state_id,
    personnel_id,
    MAX(week_no) as week_no,
    SUM(hours_1) as hours_1,
    SUM(hours_2) as hours_2,
    SUM(hours_3) as hours_3,
    SUM(hours_4) as hours_4,
    SUM(hours_5) as hours_5,
    SUM(hours_6) as hours_6,
    SUM(hours_7) as hours_7,
    SUM(total_hours) as total_hours,
    MAX(customer) as customer,
    MAX(cost) as cost,
    MAX(directing) as directing,
    MAX(comments) as comments
')
            ->whereIn('week_state_id', $ids)
            ->where('week_no', $week_no)
            ->whereHas('personnel', function ($query) use ($agency_id) {
                $query->where('employ_agency_id', $agency_id);
            })
            ->groupBy('personnel_id')
            ->get();



        return view('StaffingCompany.AgencyOverview.create', compact('ids', 'week_cards', 'agency_id', 'week_no', 'personnels', 'comments', 'translations', 'week_state_over_view'));
    }
    //         public function create($ids, $agency_id, $week_no)
    //     {
    //         $personnels = Personnel::where('employ_agency_id', $agency_id)->get();
    //         $comments = Comment::all();
    //         $translations = __('Staffing_Company/Week_State/crud');
    //
    //         $week_state_over_view = EmploymentAgencyOverview::with('documents')
    //             ->where('employ_agency_id', $agency_id)
    //             ->where('week_no', $week_no)
    //             ->first();
    //
    //         $ids = explode(',', $ids);
    //
    //         $week_cards = SfWeekCard::whereIn('week_state_id', $ids)
    //             ->where('week_no', $week_no)
    //             ->whereHas('personnel', function ($query) use ($agency_id) {
    //                 $query->where('employ_agency_id', $agency_id);
    //             })
    //             ->with('personnel')
    //             ->get();
    //         // Merge entries for same personnel
    //             $merged_week_cards = $week_cards->groupBy('personnel_id')->map(function ($group) {
    //             $first = $group->first();
    //
    //             $hours_fields = ['hours_1', 'hours_2', 'hours_3', 'hours_4', 'hours_5', 'hours_6', 'hours_7'];
    //             $daily_hours = [];
    //
    //             foreach ($hours_fields as $field) {
    //                 $daily_hours[$field] = $group->sum($field);
    //             }
    //
    //             return [
    //                 'personnel_id'   => $first->personnel_id,
    //                 'personnel_name' => $first->personnel->full_name,
    //                 'week_no'        => $first->week_no,
    //                 'total_hours'    => $group->sum('total_hours'),
    //                 'week_state_ids' => $group->pluck('week_state_id')->unique()->values(),
    //                 'daily_hours'    => $daily_hours,
    //             ];
    //         })->values()->toArray();
    //         return view('StaffingCompany.AgencyOverview.create', [
    //             'ids' => $ids,
    //             'week_cards' => $merged_week_cards,
    //             'agency_id' => $agency_id,
    //             'week_no' => $week_no,
    //             'personnels' => $personnels,
    //             'comments' => $comments,
    //             'translations' => $translations,
    //             'week_state_over_view' => $week_state_over_view,
    //         ]);
    //     }


    public function store(Request $request)
    {
        $agency_overview = EmploymentAgencyOverview::updateOrCreate(
            [
                'employ_agency_id' => $request->employ_agency_id,
                'week_no' => $request->week_no
            ],
            [
                'comments' => $request->comments,
                'status' => $request->status,
                'notes' => $request->notes,
                'invoice_date' => $request->invoice_date,
                'receive_date' => $request->receive_date,
                'completed' => $request->completed,
                'dispatch_date' => $request->dispatch_date,
                'invoice_number' => $request->invoice_number
            ]
        );

        if ($request->document_logs) {
            foreach ($request->document_logs as $document) {
                if ($document['file']) {
                    $file = $document['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('agency_overview', $fileName, 'public');

                    $agency_overview->documents()->create([
                        'type' => $document['doc_type'],
                        'expiry_date' => $document['expiry'],
                        'file' => $fileName,
                    ]);
                }
            }
        }

        if ($request->week_state_ids && count($request->week_state_ids) > 0) {
            $week_state_id_for_new_week_card = max($request->week_state_ids);
            $week_state = WeekState::find($week_state_id_for_new_week_card);

            if ($week_state && $request->personnel_logs) {
                foreach ($request->personnel_logs as $personnel) {
                    if (!empty($personnel['personnel']) && is_numeric($personnel['personnel'])) {
                        $week_state->weekCards()->create([
                            'week_no' => $personnel['week_no'],
                            'personnel_id' => $personnel['personnel'],
                            'comments' => $personnel['wage'],
                            'hours_1' => $personnel['hours_1'],
                            'hours_2' => $personnel['hours_2'],
                            'hours_3' => $personnel['hours_3'],
                            'hours_4' => $personnel['hours_4'],
                            'hours_5' => $personnel['hours_5'],
                            'hours_6' => $personnel['hours_6'],
                            'hours_7' => $personnel['hours_7'],
                            'total_hours' => $personnel['total_hours'],
                            'customer' => $personnel['rate'],
                            'cost' => $personnel['cost'],
                            'directing' => $personnel['directing'],
                        ]);
                    }
                }
            }
        }

        if ($request->personnel_update_logs) {
            foreach ($request->personnel_update_logs as $personnel) {
                if ($personnel['week_card_id']) {
                    SfWeekCard::where('id', $personnel['week_card_id'])->update([
                        'personnel_id' => $personnel['personnel'],
                        'comments' => $personnel['wage'],
                        'hours_1' => $personnel['hours_1'],
                        'hours_2' => $personnel['hours_2'],
                        'hours_3' => $personnel['hours_3'],
                        'hours_4' => $personnel['hours_4'],
                        'hours_5' => $personnel['hours_5'],
                        'hours_6' => $personnel['hours_6'],
                        'hours_7' => $personnel['hours_7'],
                        'total_hours' => $personnel['total_hours'],
                        'customer' => $personnel['rate'],
                        'cost' => $personnel['cost'],
                        'directing' => $personnel['directing'],
                    ]);
                }
            }
        }

        if ($request->removedPersonnelIds) {
            SfWeekCard::whereIn('id', $request->removedPersonnelIds)->delete();
        }

        return response()->json([
            'message' =>__('Staffing_Company/Agency/crud.employee_agency_overview')
        ]);
    }


    public function search(Request $request)
    {
        $week_no = $request->week_no;
        $year = $request->year;

        if ($week_no < 10) {
            $week_no = '0' . $week_no;
        }
        $selected_week_no = $request->week_no;
        $selected_year = $request->year;

        $week_no = $year . $week_no;
        $week_no = (int)$week_no;

        $agencies = EmployAgency::whereHas('personnels.weekCards', function ($query) use ($week_no) {
            $query->where('week_no', $week_no);
        })->with(['personnels.weekCards' => function ($query) use ($week_no) {
            $query->where('week_no', $week_no);
        }])->get();

        foreach ($agencies as $agency) {
            $totalHours = 0;
            $totalCost = 0;

            foreach ($agency->personnels as $person) {
                $hourlyRate = $person->cost_per_hour ?? 0;

                foreach ($person->weekCards as $weekcard) {
                    $hours = $weekcard->total_hours ?? 0;
                    $totalHours += $hours;
                    $totalCost += $hourlyRate * $hours;
                }
            }
            $agency->total_hours = $totalHours;
            $agency->total_cost = $totalCost;
        }

        $agency_overviews_details = EmploymentAgencyOverview::latest()->get();

        $years = _getPastYears(10);

        return view('StaffingCompany.AgencyOverview.index', compact('week_no', 'agency_overviews_details', 'selected_week_no', 'selected_year', 'agencies', 'years'));
    }
    function getStartAndEndDateOfWeek($week_no)
    {
        $week_no = substr($week_no, 4);
        $year = date('Y');
        $dto = new \DateTime();
        $dto->setISODate($year, $week_no);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }

    function getNumericValueFromString($string)
    {
        $pattern = '/\d+,\d+/';
        preg_match($pattern, $string, $matches);
        $numeric_value = str_replace(',', '.', $matches[0]);
        return (float)$numeric_value;
    }

    // public function WeekCardPDF(Request $request)
    // {
    //     $ids = json_decode($request->query('week_state_ids'), true);
    //     $week_no = $request->query('week_no');
    //     $agency_id = $request->query('agency_id');

    //     if (is_array($ids)) {
    //         $week_states = WeekState::with('weekCards.timeCards', 'weekCards.personnel')->whereIn('id', $ids)->get();
    //         $ids = implode(', ', $ids);
    //     } else {
    //         $ids = $request->input('week_state_ids');
    //         $week_states = WeekState::with('weekCards.timeCards', 'weekCards.personnel')->whereIn('id', array_map('trim', explode(',', $ids)))->get();
    //     }


    //     $start_date = $this->getStartAndEndDateOfWeek($week_no)['week_start'];
    //     $end_date = $this->getStartAndEndDateOfWeek($week_no)['week_end'];

    //     if (empty($week_states)) {
    //         return redirect('admin/404');
    //     }

    //     $all_week_cards_total_hours = 0;
    //     // foreach ($week_states as $week_state){
    //     //     $week_cards = $week_state->weekCards;
    //     //     $total_hours = 0;
    //     //     foreach ($week_cards as $week_card){
    //     //         $total_hours += $week_card->total_hours;
    //     //     }
    //     //     $all_week_cards_total_hours += $total_hours;
    //     // }

    //     $all_week_cards_total_hours = 0;
    //      foreach ($week_states as $week_state) {
    //          $week_cards = $week_state->weekCards()
    //              ->where('week_no', $week_no)
    //              ->whereHas('personnel', function ($query) use ($agency_id) {
    //                  $query->where('employ_agency_id', $agency_id);
    //              })
    //              ->get();

    //          $total_hours = $week_cards->sum('total_hours');
    //     $all_week_cards_total_hours += $total_hours;
    //     }


    //     // return view('StaffingCompany.AgencyOverview.pdf', compact('week_states','week_state_ids','week_no','all_week_cards_total_hours','start_date','end_date'));

    //     $pdf = PDF::loadView('StaffingCompany.AgencyOverview.pdf', compact('agency_id', 'week_states', 'ids', 'week_no', 'all_week_cards_total_hours', 'start_date', 'end_date'));
    //     $pdf->setPaper('a4', 'portrait');
    //     return $pdf->download('Weekstaat.pdf');
    // }
    public function WeekCardPDF(Request $request)
    {
      
        $ids = json_decode($request->query('week_state_ids'), true);
        $week_no = $request->query('week_no');
        $agency_id = $request->query('agency_id');

        if (is_array($ids)) {
            $week_states = WeekState::with('weekCards.timeCards', 'weekCards.personnel')->whereIn('id', $ids)->get();
            $ids = implode(', ', $ids);
        } else {
            $ids = $request->input('week_state_ids');
            $week_states = WeekState::with('weekCards.timeCards', 'weekCards.personnel')->whereIn('id', array_map('trim', explode(',', $ids)))->get();
        }

        if ($week_states->isEmpty()) {
            return redirect('admin/404');
        }

        $start_date = $this->getStartAndEndDateOfWeek($week_no)['week_start'];
        $end_date = $this->getStartAndEndDateOfWeek($week_no)['week_end'];

        // Collect all week cards
        $all_week_cards = collect();

        foreach ($week_states as $week_state) {
            $week_cards = $week_state->weekCards()
                ->where('week_no', $week_no)
                ->whereHas('personnel', function ($query) use ($agency_id) {
                    $query->where('employ_agency_id', $agency_id);
                })
                ->get();

            $all_week_cards = $all_week_cards->merge($week_cards);
        }

        // Group by personnel_id and merge hours
        $grouped_week_cards = $all_week_cards->groupBy('personnel_id')->map(function ($cards) {
            $first_card = $cards->first();

            // Sum hours for all days
            $merged_card = clone $first_card;
            $merged_card->hours_1 = $cards->sum('hours_1');
            $merged_card->hours_2 = $cards->sum('hours_2');
            $merged_card->hours_3 = $cards->sum('hours_3');
            $merged_card->hours_4 = $cards->sum('hours_4');
            $merged_card->hours_5 = $cards->sum('hours_5');
            $merged_card->hours_6 = $cards->sum('hours_6');
            $merged_card->hours_7 = $cards->sum('hours_7');
            $merged_card->total_hours = $cards->sum('total_hours');

            return $merged_card;
        });

        $all_week_cards_total_hours = $grouped_week_cards->sum('total_hours');

        $pdf = PDF::loadView('StaffingCompany.AgencyOverview.pdf', [
            'agency_id' => $agency_id,
            'grouped_week_cards' => $grouped_week_cards,
            'ids' => $ids,
            'week_no' => $week_no,
            'all_week_cards_total_hours' => $all_week_cards_total_hours,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Weekstaat.pdf');
    }

    public function agencyEmail(Request $request)
    {
        $ids = $request->input('week_state_ids');
        $week_no = $request->input('week_no');
        $agency_overview_id = $request->input('agency_overview_id');
        $agency_overview = EmploymentAgencyOverview::find($agency_overview_id);

        return $agency_overview;
    }
}
