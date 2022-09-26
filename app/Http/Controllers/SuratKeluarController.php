<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SuratKeluar;

use Auth;
use DataTables;
use URL;
use Helper;
use Image;
use Response;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $surat_keluar = SuratKeluar::all();
        if (request()->ajax()) {
            $data = SuratKeluar::get();
            $user = Auth::user();
            $roles = $user->getRoleNames();
            if ($roles[0] == "sekcam") {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('tgl_surat', function ($row) {
                    if ($row->tgl_surat == null){
                        return Carbon::make(null);
                    }
                    return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                })
                ->addColumn('action', function ($row) {
                    ;
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
        }
        elseif ($roles[0] == "tamu") {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('tgl_surat', function ($row) {
                    if ($row->tgl_surat == null){
                        return Carbon::make(null);
                    }
                    return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                })
                ->addColumn('action', function ($row) {
                    ;
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
        }
        return Datatables::of($myDate)
                ->addIndexColumn()
                ->editColumn('tgl_surat', function ($row) {
                    if ($row->tgl_surat == null){
                        return Carbon::make(null);
                    }
                    return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '
                            <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('suratkeluar.edit', $row->uuid) . '"><i class="fal fa-edit"></i></a>
                            <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="' . URL::route('suratkeluar.destroy', $row->uuid) . '" data-id="' . $row->uuid . '" data-token="' . csrf_token() . '" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
    }

        return view('suratkeluar.index');
    }

    public function filter (Request $request)
    {
        $myDate = SuratKeluar::whereBetween('tgl_surat', array($request->get('start_date'), $request->get('end_date')))->get();
        $user = Auth::user();
        $roles = $user->getRoleNames();
        if (request()->ajax()) {
            
            if ($roles[0] == "sekcam") {
                return Datatables::of($myDate)
                    ->addIndexColumn()
                    ->editColumn('tgl_surat', function ($row) {
                        if ($row->tgl_surat == null){
                            return Carbon::make(null);
                        }
                        return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                    })
                    ->addColumn('action', function ($row) {
                        ;
                    })
                    ->removeColumn('id')
                    ->removeColumn('uuid')
                    ->rawColumns(['action'])
                    ->make(true);
            }
            elseif ($roles[0] == "tamu") {
                return Datatables::of($myDate)
                    ->addIndexColumn()
                    ->editColumn('tgl_surat', function ($row) {
                        if ($row->tgl_surat == null){
                            return Carbon::make(null);
                        }
                        return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                    })
                    ->addColumn('action', function ($row) {
                        ;
                    })
                    ->removeColumn('id')
                    ->removeColumn('uuid')
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return Datatables::of($myDate)
                ->addIndexColumn()
                ->editColumn('tgl_surat', function ($row) {
                    if ($row->tgl_surat == null){
                        return Carbon::make(null);
                    }
                    return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '
                            <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('suratkeluar.edit', $row->uuid) . '"><i class="fal fa-edit"></i></a>
                            <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="' . URL::route('suratkeluar.destroy', $row->uuid) . '" data-id="' . $row->uuid . '" data-token="' . csrf_token() . '" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
            }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suratkeluar.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'perihal' => 'required',
            'tgl_surat' => 'required',
            'tujuan' => 'required',
            'keterangan' => 'required',
            'surat' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->photo);

        $surat_keluar = new SuratKeluar();
        $surat_keluar->perihal = $request->perihal;
        $surat_keluar->tgl_surat = $request->tgl_surat;
        $surat_keluar->tujuan = $request->tujuan;
        $surat_keluar->keterangan = $request->keterangan;
        $surat_keluar->surat = $request->surat;

        if ($image = $request->file('surat')) {
            $destinationPath = 'surat/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $surat_keluar->surat = "$profileImage";
        }

        $surat_keluar->save();

        toastr()->success('New Surat Keluar Added', 'Success');
        return redirect()->route('suratkeluar.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $surat_keluar = Suratkeluar::uuid($id);
        return view('suratkeluar.edit', compact('surat_keluar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'perihal' => 'required',
            'tgl_surat' => 'required',
            'tujuan' => 'required',
            'keterangan' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->photo);

        $surat_keluar = Suratkeluar::uuid($id);
        if($request->hasFile('surat')){

            // user intends to replace the current image for the category.  
            // delete existing (if set)
        
            if($oldImage = $surat_keluar->surat) {
        
                unlink(public_path('surat/') . $oldImage);
            }
        
            // save the new image
            $image = $request->file('surat');
            $destinationPath = 'surat/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $surat_keluar->surat = "$profileImage";
        }
        $surat_keluar->perihal = $request->perihal;
        $surat_keluar->tgl_surat = $request->tgl_surat;
        $surat_keluar->tujuan = $request->tujuan;
        $surat_keluar->keterangan = $request->keterangan;

        $surat_keluar->save();

        toastr()->success('Surat Keluar Edited', 'Success');
        return redirect()->route('suratkeluar.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $surat_keluar = SuratKeluar::uuid($id);
        $surat_keluar->delete();

        toastr()->success('Surat Keluar Deleted', 'Success');
        return redirect()->route('suratkeluar.index');
    }
}
