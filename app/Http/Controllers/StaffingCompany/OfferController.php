<?php

namespace App\Http\Controllers\StaffingCompany;

use PDF;
use Carbon\Carbon;
use App\Models\Offer;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\RetailOffer;
use App\Models\StaffingCompany\MultiService;
use App\Models\StaffingCompany\OfferEmailLog;
use App\Models\StaffingCompany\SiteMaintance;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\MultiServiceOffer;

class OfferController extends Controller
{

    private $offerTypes = [
        'handover' => \App\Models\Offer::class,
        'retail'   => \App\Models\StaffingCompany\RetailOffer::class,
        'site'     => \App\Models\StaffingCompany\SiteMaintance::class,
        'multi'    => \App\Models\StaffingCompany\MultiServiceOffer::class,
    ];
    /* =========================
     |  LIST PAGES (INDEX)
     ==========================*/

    // Handover list
    public function handIndex()
    {
        $offers = Offer::with('project.customer')->latest()->get();
        return view('StaffingCompany.Offer.HandTaking.index', compact('offers'));
    }

    // Retail list
    public function retailIndex()
    {
        $retailOffers = RetailOffer::with('project.customer')->latest()->get();
        return view('StaffingCompany.Offer.Retail.index', compact('retailOffers'));
    }

    // Site/Hut list
    public function siteIndex()
    {
        $siteMaintances = SiteMaintance::with('project.customer')->latest()->get();
        return view('StaffingCompany.Offer.SiteMaintance.index', compact('siteMaintances'));
    }

    // MultiService List
    public function multiServiceIndex()
    {
        $offers = MultiServiceOffer::latest()->paginate(20);
        return view('StaffingCompany.Offer.MultiService.index', compact('offers'));
    }

    /* ===============
     |  CREATE OFFER
     ================*/

    // Multi Service create
    public function multiServiceCreate()
    {
        $customers = Customer::orderBy('name')->get();
        $services  = MultiService::orderBy('name')->get(); // pass to view for dropdown
        return view('StaffingCompany.Offer.MultiService.create', compact('customers', 'services'));
    }

    // Retail Create
    public function createRetail()
    {
        $projects = StaffingProject::with('customer')->get();
        return view('StaffingCompany.Offer.Retail.create', compact('projects'));
    }

    // Site Maintenance creata
    public function createSite()
    {
        $projects = StaffingProject::with('customer')->get();
        return view('StaffingCompany.Offer.SiteMaintance.create', compact('projects'));
    }

    // Handover create
    public function create()
    {
        $projects = StaffingProject::with('customer')->get();
        return view('StaffingCompany.Offer.HandTaking.create', compact('projects'));
    }

    public function emailHistory($type, $id)
    {
        if (!isset($this->offerTypes[$type])) {
            abort(404);
        }

        $model = $this->offerTypes[$type];
        $offer = $model::findOrFail($id);

        $emailLogs = OfferEmailLog::where('offer_type', $type)
            ->where('offer_id', $id)
            ->orderBy('sent_at', 'desc')
            ->get();

        return view('StaffingCompany.Offer.history', compact('offer', 'emailLogs', 'type'));
    }



    // mail method
    public function sendMail($type, $id, $mode = 'initial')
    {
        if (!isset($this->offerTypes[$type])) {
            abort(404);
        }

        $model = $this->offerTypes[$type];

        $offer = $model::with('project.customer')->findOrFail($id);

        // Detect correct PDF view
        switch ($type) {
            case 'retail':
                $view = 'StaffingCompany.Offer.Retail.pdf';
                break;

            case 'site':
                $view = 'StaffingCompany.Offer.SiteMaintance.pdf';
                break;

            case 'multi':
                $view = 'StaffingCompany.Offer.MultiService.pdf';
                break;

            default:
                $view = 'StaffingCompany.Offer.HandTaking.pdf';
                break;
        }

        /* -------------------------
       ADD HARDCODED COMPANY INFO
    -------------------------- */
        $hardcoded = [
            'company_name'     => 'Easy Clean Up BV',
            'company_address'  => "Kollenbergweg 78\n1101 AV Amsterdam Z.O.",
            'company_phone'    => '020 – 691 61 15',
            'company_website'  => 'www.easycleanup.nl',
            'company_email'    => 'info@easycleanup.nl',
            'iban'             => 'NL19 INGB 0656 3018 72',
            'kvk'              => '34208235',
            'btw'              => 'NL.8133.28.378.B01',
            'recipient_name'   => $offer->project->customer->name ?? 'Customer',
            'recipient_attention' => '',
            'recipient_email'  => $offer->project->customer->email ?? '',
        ];

        // Grouped scope items
        $groupedItems = [];
        if (!empty($offer->scope)) {
            $groupedItems = collect($offer->scope)
                ->groupBy('name')
                ->map(function ($g) {
                    return [
                        'name' => $g->first()['name'],
                        'descriptions' => collect($g)->flatMap(function ($x) {
                            return isset($x['descriptions']) ? $x['descriptions'] : [];
                        })->all()
                    ];
                })
                ->values()
                ->all();
        }

        /* -------------------------
       LOAD PDF (with hardcoded)
    -------------------------- */
        $pdf = \PDF::loadView($view, [
            'offer'        => $offer,
            'groupedItems' => $groupedItems,
            'hardcoded'    => $hardcoded
        ]);

        // Save PDF
        $fileName = "{$type}_offer_{$offer->id}_" . time() . ".pdf";
        $path = storage_path("app/public/sent_offers/" . $fileName);
        $pdf->save($path);

        // Log email
        OfferEmailLog::create([
            'offer_id'   => $offer->id,
            'offer_type' => $type,
            'email_type' => $mode,
            'to_email'   => $offer->project->customer->email ?? ($offer->customer->email ?? null),
            'subject'    => $offer->subject ?? $offer->title,
            'pdf_path'   => "sent_offers/" . $fileName,
            'sent_at'    => now(),
        ]);

        // Update first send
        if ($mode === 'initial') {
            $offer->update([
                'sent_at'       => now(),
                'reminder_sent' => false,
            ]);
        }

        // Gmail compose redirect
        $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1"
            . "&to=" . urlencode($offer->project->customer->email ?? ($offer->customer->email ?? null))
            . "&su=" . urlencode($offer->subject ?? $offer->title)
            . "&body=" . urlencode("Please find your offer attached.");

        return redirect()->away($gmailUrl);
    }


    public function acceptOffer($type, $id, Request $request)
    {
        if (!isset($this->offerTypes[$type])) {
            abort(404, "Offer type not found");
        }

        $model = $this->offerTypes[$type];
        $offer = $model::findOrFail($id);

        // Update the offer status based on the input from the form (either 'pending' or 'accepted')
        $status = $request->input('status'); // Get the selected status from the form
        $offer->update(['status' => $status]); // Update status to either 'pending' or 'accepted'

        return back()->with('success', "$type offer marked as $status.");
    }





    /* =========================
     |  STORE + PDF (Handover)
     ==========================*/

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'        => 'required|date',
            'subject'     => 'required|string|max:255',
            'project_id'  => 'required|exists:staffing_projects,id',
            'scope'       => 'required|string',
            // 'total_price' => 'required|numeric',
            'notes'       => 'nullable|string',
            'title'       => 'required|string|max:255',
        ]);

        $date         = Carbon::now()->format('Ymd');
        $letters      = ['K', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'];
        $randomLetter = $letters[array_rand($letters)];
        $slug         = $date . $randomLetter;

        $scope = json_decode($data['scope'], true);

        $offer = Offer::create([
            'date'          => $data['date'],
            'subject'       => $data['subject'],
            'title'         => $data['title'],
            'project_id'    => $data['project_id'],
            'our_reference' => $slug,
            'notes'         => $data['notes'] ?? null,
            'scope'         => $scope,
            // 'total_price'   => $data['total_price'],
        ]);

        return redirect()->route('offers.index', $offer->id)->with('success', 'handover_create_success')->with('download_offer_id', $offer->id);
    }

    public function downloadPDF($id)
    {
        $offer = Offer::with('project')->findOrFail($id);

        $hardcoded = [
            'company_name'     => 'Easy Clean Up BV',
            'company_address'  => "Kollenbergweg 78\n1101 AV Amsterdam Z.O.",
            'company_phone'    => '020 – 691 61 15',
            'company_website'  => 'www.easycleanup.nl',
            'company_email'    => 'info@easycleanup.nl',
            'iban'             => 'NL19 INGB 0656 3018 72',
            'kvk'              => '34208235',
            'btw'              => 'NL.8133.28.378.B01',
            'recipient_name'   => 'Visser & Smit Bouw B.V.',
            'recipient_attention' => 'dhr. S. Bokx',
            'recipient_email'  => 's.bokx@visserensmitbouw.nl',
        ];

        $items = $offer->scope ?? [];

        $groupedItems = collect($items)->groupBy('name')->map(function ($group) {
            return [
                'name'         => $group->first()['name'],
                'descriptions' => $group->flatMap(fn($g) => $g['descriptions'] ?? [])->all(),
            ];
        })->values()->all();

        $pdf = PDF::loadView('StaffingCompany.Offer.HandTaking.pdf', compact('offer', 'hardcoded', 'groupedItems'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('offer_' . $offer->id . '.pdf');
    }

    /* =========================
     |  STORE + PDF (Retail)
     ==========================*/

    public function storeRetail(Request $request)
    {
        $offer = RetailOffer::create($request->all());
        return redirect()->route('retail-offers.index', $offer->id)->with('success', 'retail_create_success')->with('download_offer_id', $offer->id);
    }

    public function downloadRetailPDF($id)
    {
        $offer = RetailOffer::with('project')->findOrFail($id);

        $hardcoded = [
            'company_name'     => 'Easy Clean Up BV',
            'company_address'  => "Kollenbergweg 78\n1101 AV Amsterdam Z.O.",
            'company_phone'    => '020 – 691 61 15',
            'company_website'  => 'www.easycleanup.nl',
            'company_email'    => 'info@easycleanup.nl',
            'iban'             => 'NL19 INGB 0656 3018 72',
            'kvk'              => '34208235',
            'btw'              => 'NL.8133.28.378.B01',
            'recipient_name'   => 'Offerte Verkeersregelaars en Bouwhulp',
            'recipient_attention' => 'dhr. S. Bokx',
            'recipient_email'  => 's.bokx@visserensmitbouw.nl',
        ];

        $pdf = PDF::loadView('StaffingCompany.Offer.Retail.pdf', compact('offer', 'hardcoded'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('retail_offer_' . $offer->id . '.pdf');
    }

    /* =========================
     |  STORE + PDF (Site/Hut)
     ==========================*/

    public function site_maintance_store(Request $request)
    {
        $request['scope'] = $request->filled('scope') ? json_decode($request->scope, true) : [];
        $request['our_reference'] = now()->format('Ymd') . chr(rand(65, 90));

        $offer = SiteMaintance::create($request->all());
        return redirect()->route('site-offers.index', $offer->id)->with('success', 'site_create_success')->with('download_offer_id', $offer->id);
    }

    public function downloadSitePDF($id)
    {
        $offer = SiteMaintance::with('project')->findOrFail($id);

        $hardcoded = [
            'company_name'     => 'Easy Clean Up BV',
            'company_address'  => "Kollenbergweg 78\n1101 AV Amsterdam Z.O.",
            'company_phone'    => '020 – 691 61 15',
            'company_website'  => 'www.easycleanup.nl',
            'company_email'    => 'info@easycleanup.nl',
            'iban'             => 'NL19 INGB 0656 3018 72',
            'kvk'              => '34208235',
            'btw'              => 'NL.8133.28.378.B01',
            'recipient_name'   => 'Offerte Verkeersregelaars en Bouwhulp',
            'recipient_attention' => 'dhr. S. Bokx',
            'recipient_email'  => 's.bokx@visserensmitbouw.nl',
        ];

        $items = $offer->scope ?? [];

        $groupedItems = collect($items)->groupBy('name')->map(function ($group) {
            return [
                'name'         => $group->first()['name'],
                'descriptions' => $group->flatMap(fn($g) => $g['descriptions'] ?? [])->all(),
            ];
        })->values()->all();

        $pdf = PDF::loadView('StaffingCompany.Offer.SiteMaintance.pdf', compact('offer', 'hardcoded', 'groupedItems'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('site_offer_' . $offer->id . '.pdf');
    }


    /*============================
    |  STORE + PDF (Multi Service)
    =============================*/

    public function multiServiceStore(Request $request)
    {
        $data = $request->validate([
            'date'           => ['required', 'date'],
            'title'          => ['required', 'string', 'max:255'],
            'customer_id'    => ['required', 'exists:customers,id'],

            'services_json'  => ['nullable', 'string'],
            'scope'          => ['nullable', 'string'],

            'total_price'    => ['required', 'numeric', 'min:0'],
            'notes'          => ['nullable', 'string'],
        ]);

        $offer = DB::transaction(function () use ($data) {

            if (!empty($data['services_json'])) {
                $decoded = json_decode($data['services_json'], true) ?: [];

                $servicesPayload = collect($decoded)->map(function ($row) {
                    return [
                        'service_id' => isset($row['service_id']) ? (int)$row['service_id'] : null,
                        'name'       => (string)($row['name']   ?? ''),
                        'price'      => isset($row['price']) ? (float)$row['price'] : 0.0,
                    ];
                })->all();
            } else {
                $servicesPayload = [];
                $scope = json_decode($data['scope'] ?? '[]', true) ?: [];

                foreach ($scope as $row) {
                    $name = trim((string)($row['name'] ?? ''));
                    if ($name === '') {
                        continue;
                    }
                    $price = 0;
                    if (
                        !empty($row['descriptions'][0]) &&
                        preg_match('/([\d]+(?:[.,]\d{1,2})?)/', $row['descriptions'][0], $m)
                    ) {
                        $price = (float) str_replace(',', '.', $m[1]);
                    }
                    $service = MultiService::firstOrCreate(['name' => $name]);

                    $servicesPayload[] = [
                        'service_id' => $service->id,
                        'name'       => $service->name,
                        'price'      => $price,
                    ];
                }
            }
            return MultiServiceOffer::create([
                'date'         => $data['date'],
                'title'        => $data['title'],
                'customer_id'  => $data['customer_id'],
                'services'     => $servicesPayload,
                'total_price'  => $data['total_price'],
                'notes'        => $data['notes'] ?? null,
            ]);
        });
        return redirect()->route('multiservice-offers.index')
            ->with('success', 'multi_create_success')->with('download_offer_id', $offer->id);
    }

    public function downloadMultiPDF($id)
    {
        $offer = MultiServiceOffer::with('customer')->findOrFail($id);

        $hardcoded = [
            'company_name'     => 'Easy Clean Up BV',
            'company_address'  => "Kollenbergweg 78\n1101 AV Amsterdam Z.O.",
            'company_phone'    => '020 – 691 61 15',
            'company_website'  => 'www.easycleanup.nl',
            'company_email'    => 'info@easycleanup.nl',
            'iban'             => 'NL19 INGB 0656 3018 72',
            'kvk'              => '34208235',
            'btw'              => 'NL.8133.28.378.B01',
            'recipient_name'   => 'Offerte Verkeersregelaars en Bouwhulp',
            'recipient_attention' => 'dhr. S. Bokx',
            'recipient_email'  => 's.bokx@visserensmitbouw.nl',
        ];

        $pdf = PDF::loadView('StaffingCompany.Offer.MultiService.pdf', compact('offer', 'hardcoded'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('multiservice_offers_' . $offer->id . '.pdf');
    }


    // Store Service
    public function storeService(Request $request)
    {
        //    dd($request->all());
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:multi_services,name'],
        ]);

        // Create the service
        $service = MultiService::create([
            'name' => $data['name'],
        ]);

        // Return JSON if requested (e.g. AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'service' => $service], 201);
        }

        // Otherwise, redirect back with success message
        return redirect()->back()->with('success', 'Service added successfully!');
    }


    /* =========================
     |  DELETE (single set)
     ==========================*/

    // Delete Handover (Normal) Offer
    public function destroy($id)
    {
        Offer::findOrFail($id)->delete();
        return redirect()->route('offers.index')->with('success', 'handover_delete_success');
    }

    // Delete Retail Offer
    public function destroyRetail($id)
    {
        RetailOffer::findOrFail($id)->delete();
        return redirect()->route('retail-offers.index')->with('success', 'retail_delete_success');
    }

    // Delete Site Maintenance Offer
    public function destroySite($id)
    {
        SiteMaintance::findOrFail($id)->delete();
        return redirect()->route('site-offers.index')->with('success', 'site_delete_success');
    }

    //Delete MultiServices Offers
    public function destroyMultiService($id)
    {
        MultiServiceOffer::findOrFail($id)->delete();
        return redirect()->route('multiservice-offers.index')->with('success', 'multi_delete_success');
    }
}
