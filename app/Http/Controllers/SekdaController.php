<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Surat;

use Auth;
use DataTables;
use URL;
use Helper;
use Image;

class SekdaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (request()->ajax()) {
            $surat = Surat::all();

            return Datatables::of($surat)
                ->addIndexColumn()
                ->editColumn('sifat_surat', function($row){
                    switch ($row->sifat_surat) {
                        case 'biasa' :
                            return '<span class="badge badge-primary">Biasa</span>';
                            break;
                        case 'segera' :
                            return '<span class="badge badge-danger">Segera</span>';
                            break;
                    }
                })
                ->editColumn('status', function($row){
                    switch ($row->status) {
                        case '1' :
                            return '<span class="badge badge-warning">Belum di Baca</span>';
                            break;
                        case '2' :
                            return '<span class="badge badge-secondary">Sudah Dibaca</span>';
                            break;
                    }
                })
                ->editColumn('tgl_surat', function ($row) {
                    return Carbon::parse($row->tgl_surat)->translatedFormat('d M Y');
                })
                ->editColumn('tgl_acara', function ($row) {
                    return Carbon::parse($row->tgl_acara)->translatedFormat('d M Y');
                })
                ->editColumn('update_at', function ($row) {
                    return Carbon::parse($row->update_at)->translatedFormat('d M Y');
                })
                ->editColumn('created_by', function($row){
                    return $row->userCreate->name;
                })
                ->addColumn('action', function ($row) {
                    ;
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action','sifat_surat','status'])
                ->make(true);
        }

        return view('sekda.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'jenis_surat' => 'required',
            'asal_surat' => 'required',
            'tgl_surat' => 'required',
            'perihal' => 'required',
            'tgl_acara' => 'required',
            'sifat_surat' => 'required',
            'surat' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->all());

        $surat = new Surat();
        $surat->jenis_surat = $request->jenis_surat;
        $surat->asal_surat = $request->asal_surat;
        $surat->tgl_surat = $request->tgl_surat;
        $surat->perihal = $request->perihal;
        $surat->tgl_acara = $request->tgl_acara;
        $surat->sifat_surat = $request->sifat_surat;
        $surat->surat = $request->surat;
        $surat->disposisi = $request->disposisi;
        $surat->created_by = Auth::user()->uuid;

        if ($image = $request->file('surat')) {
            $destinationPath = 'surat/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $surat->surat = "$profileImage";
        }

        $surat->save();

        toastr()->success('Surat Baru Terbuat', 'Success');
        return redirect()->route('surat.index');
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
        $surat = Surat::uuid($id);
        return view('sekda.edit', compact('surat'));
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
            'jenis_surat' => 'required',
            'asal_surat' => 'required',
            'tgl_surat' => 'required',
            'perihal' => 'required',
            'tgl_acara' => 'required',
            'sifat_surat' => 'required',
            'surat' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->all());

        $surat = Surat::uuid($id);
        $surat->jenis_surat = $request->jenis_surat;
        $surat->asal_surat = $request->asal_surat;
        $surat->tgl_surat = $request->tgl_surat;
        $surat->perihal = $request->perihal;
        $surat->tgl_acara = $request->tgl_acara;
        $surat->sifat_surat = $request->sifat_surat;
        $surat->file_surat = $request->file_surat;
        $surat->disposisi = $request->disposisi;
        $surat->edited_by = Auth::user()->uuid;
        $surat->save();

        toastr()->success('Surat Baru Di Edit', 'Success');
        return redirect()->route('surat.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $surat = Surat::uuid($id);
        $surat->delete();
        toastr()->success('Surat Berhasil di Hapus', 'Success');
        return redirect()->route('surat.index');
    }
}
