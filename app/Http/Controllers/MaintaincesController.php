<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Maintaince;
use Illuminate\Http\Request;

use App\Http\Requests;

class MaintaincesController extends Controller
{
    //
    public function create($id)
    {
        $asset=Asset::find($id);
        $data = ['asset' => $asset];
        return view('admin.assets.application', $data);
    }

    public function store(Request $request,$id)
    {
        $asset=Asset::find($id);
        $asset->maintainces()->create([
            'v_id'=>$asset->warranty,
            'status'=>'申請中',
            'method'=>null,
            'remark'=>null,
        ]);
        $maintainces=Maintaince::orderBy('created_at', 'DESC')->first();
        $maintainces->applications()->create([
            'user_id'=>$id,
            'problem'=>$request->problem,
            'date'=>NULL
        ]);
        return redirect()->route('admin.assets.index');
    }



    public function index()
    {
        $asset=Asset::orderBy('created_at', 'DESC')->get();
        $category=Category::orderBy('created_at' ,'DESC') ->get();
        $data=['assets'=>$asset
            ,'categories'=>$category
        ];
        return view('admin.assets.index', $data);
    }


    public function edit($id)
    {
        $category=Category::orderBy('created_at' ,'DESC') ->get();
        $asset=Asset::find($id);
        $data = ['asset' => $asset,'categories'=>$category];

        return view('admin.assets.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $asset=Asset::find($id);
        $asset->update($request->all());

        return redirect()->route('admin.assets.index');
    }

    public function destroy($id)
    {
        Asset::destroy($id);
        return redirect()->route('admin.assets.index');
    }

    public function data($id)
    {
        $category=Category::orderBy('created_at' ,'DESC') ->get();
        $asset=Asset::find($id);
        $data = ['asset' => $asset,'categories'=>$category];

        return view('admin.assets.show', $data);
    }

    public function Search(Request $request)
    {
        $Search =$request->input('Search');
        $asset = Asset::orderBy('created_at', 'DESC')
            ->where('name', 'like','%'.$Search.'%')
            ->get();
        $category=Category::orderBy('created_at' ,'DESC') ->get();
        $data=['assets'=>$asset,'categories'=>$category];
        return view('admin.assets.index' ,$data);
    }




}
