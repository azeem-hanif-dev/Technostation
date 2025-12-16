<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\EmployAgency;
use App\Helpers\Helper;
use App\Models\StaffingCompany\EmploymentAgencyDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class EmploymentAgencyController extends Controller
{
    public function index()
    {
        $agencies = EmployAgency::latest('id')->get();
        //use for loop in agencies and formate the phone in each
        foreach ($agencies as $agency){
            $agency->phone = _formatPhoneNumber($agency->phone ?? 'Null');
        }
        return view('StaffingCompany.Agency.index')->with('agencies',$agencies);
    }

    public function create()
    {
        $translations = __('Staffing_Company/Agency/crud');
        return view('StaffingCompany.Agency.create',compact('translations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $agency = new EmployAgency;
        $agency->email = $request->email;
        $agency->company_id = Auth::user()->company_id;
        $agency->name = $request->name;
        $agency->slug = Str::slug($request->name) . '-' . time();
        $agency->url = $request->agency_url;
        $agency->contact_person = $request->contact;
        $agency->phone = $request->phone;
        $agency->address = $request->address;
        $agency->city = $request->city;
        $agency->country = $request->country;
        $agency->notes = $request->notes;
        $agency->zipcode = $request->postcode;
        $agency->created_at = carbon::now();
        $agency->updated_at = carbon::now();
        $agency->save();

        if ($request->document_logs){
            foreach ($request->document_logs as $document){
                if ($document['file']){

                    $file = $document['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('employment_agency_docs', $fileName, 'public');

                    $agency->documents()->create([
                        'type' => $document['doc_type'],
                        'expiry_date' => $document['expiry'],
                        'file' => $fileName,
                    ]);
                }
            }
        }
  return response()->json([
            'status' => true,
            'message' => __('Staffing_Company/Agency/crud.agency_create')
        ]);
        // return response()->json([
        //     'message' => 'success',
        //     'status'  => 1
        // ], 200);
    }

    public function show($id)
    {
        return response()->json(EmployAgency::with(['company','documents'])->find($id));
    }

    public function view($id)
    {
        $agency = EmployAgency::with(['company','documents'])->find($id);
        $agency->phone = _formatPhoneNumber($agency->phone);
        $translations = __('Staffing_Company/Agency/crud');
        return view('StaffingCompany.Agency.show',compact('id','agency','translations'));
    }



    public function edit($id)
    {
        $agency = EmployAgency::with(['company','documents'])->find($id);
        $agency->documents->transform(function ($document) {
            $document->path = asset('storage/app/public/employment_agency_docs/'.$document->file);
            return $document;
        });
        $translations = __('Staffing_Company/Agency/crud');
        return view('StaffingCompany.Agency.update',compact('agency','translations'));
    }

    public function update(Request $request, $id)
    {
        $agency = EmployAgency::find($id);

        EmployAgency::where('id',$id)
            ->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_person' => $request->contact,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'url' => $request->agency_url,
            'notes' => $request->notes,
            'zipcode' => $request->postcode,
        ]);

        if ($request->document_logs){
            foreach ($request->document_logs as $document){

                if ($document['file']){

                    $file = $document['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('employment_agency_docs', $fileName, 'public');

                    $agency->documents()->create([
                        'type' => $document['doc_type'],
                        'expiry_date' => $document['expiry'] === 'null' ? NULL : $document['expiry'],
                        'file' => $fileName,
                    ]);
                }
            }
        }

        if ($request->document_update_logs){
            foreach ($request->document_update_logs as $document){

                $agency->documents()->where('id',$document['id'])->update([
                    'type' => $document['doc_type'],
                    'expiry_date' => $document['expiry'] === 'null' ? NULL : $document['expiry'],
                ]);
            }
        }

        if ($request->removedDocumentIds){
            EmploymentAgencyDocument::whereIn('id',$request->removedDocumentIds)->delete();
        }

       return response()->json([
            'status' => true,
            'message' => __('Staffing_Company/Agency/crud.agency_update')
        ]);
    }

    public function destroy($id)
    {
        EmployAgency::where('id',$id)->delete();

        return redirect()->back()->with('message', 'Agency Deleted Successfully!');
    }
}
