<?php

namespace App\Http\Controllers\CompanyAdmin;

use DB;
use Session;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $materials = Material::all();
      return view('Company_Admin.material.index')->withMaterials($materials);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::select('name','id')->get();
        return view('Company_Admin.material.add')->withSuppliers($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'name' => 'required|string|max:255',
          'price' => 'required|integer',
          ]);

        $material = new Material;
        $material->name = $request->name;
        $material->price = $request->price;
        $material->save();

        foreach ($request->suppliers as $key => $supplier) {
          $material->suppliers()->attach($supplier);
        }

        Session::flash('success','Material added Successfuly');
        return redirect()->route('material.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        return view('Company_Admin.material.view')->withMaterial($material);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        $suppliers = Supplier::pluck('name','id');
        return view('Company_Admin.material.edit')->withSuppliers($suppliers)->withMaterial($material);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        $request->validate([
          'name' => 'required|max:255',
          'price' => 'required|max:255',
          ]);

        $material->name = $request->name;
        $material->price = $request->price;
        $material->save();

        $material->suppliers()->detach();

        foreach ($request->suppliers as $key => $supplier) {
          $material->suppliers()->attach($supplier);
        }

        Session::flash('success','Material info updated Successfuly');
        return redirect()->route('material.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {

    }

    public function deleteRecord(Material $material)
    {
      $material->suppliers()->detach();
      $material->delete();

      Session::flash('success','Material deleted Successfuly');
      return redirect()->route('material.index');
    }


}
