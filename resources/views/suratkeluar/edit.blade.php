@extends('layouts.page')

@section('title', 'Surat Keluar Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/dropzone/dropzone.css')}}">
<link rel="stylesheet" media="screen, print"
    href="{{asset('css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>Edit <span class="fw-300"><i>{{$surat_keluar->perihal}}</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('suratkeluar.index')}}"><i class="fal fa-arrow-alt-left">
                        </i>
                        <span class="nav-link-text">Back</span>
                    </a>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="panel-tag">
                        Form with <code>*</code> can not be empty.
                    </div>
                    {!! Form::open(['route' => ['suratkeluar.update',$surat_keluar->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('perihal','Perihal',['class' => 'required form-label'])}}
                        {{ Form::textarea('perihal', $surat_keluar->perihal,['placeholder' => 'Perihal','class' => 'form-control '.($errors->has('perihal') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('perihal'))
                        <div class="invalid-feedback">{{ $errors->first('perihal') }}</div>
                        @endif
                    </div>    
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('tgl_surat','Tanggal Surat',['class' => 'required form-label'])}}
                        {{ Form::text('tgl_surat', $surat_keluar->tgl_surat,['placeholder' => 'Tanggal Surat','class' => 'tgl_surat form-control '.($errors->has('tgl_surat') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('tgl_surat'))
                        <div class="invalid-feedback">{{ $errors->first('tgl_surat') }}</div>
                        @endif
                    </div>                
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('tujuan','Tujuan',['class' => 'required form-label'])}}
                        {{ Form::text('tujuan',$surat_keluar->tujuan,['placeholder' => 'Tujuan','class' => 'form-control '.($errors->has('tujuan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('tujuan'))
                        <div class="invalid-feedback">{{ $errors->first('tujuan') }}</div>
                        @endif
                    </div>    
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('keterangan','Keterangan',['class' => 'required form-label'])}}
                        {{ Form::textarea('keterangan',$surat_keluar->keterangan,['placeholder' => 'Keterangan','class' => 'form-control '.($errors->has('keterangan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('keterangan'))
                        <div class="invalid-feedback">{{ $errors->first('keterangan') }}</div>
                        @endif
                    </div>  
                <div
                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                    <button class="btn btn-primary ml-auto" type="submit">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script src="{{asset('js/formplugins/dropzone/dropzone.js')}}"></script>
<script src="{{asset('js/formplugins/inputmask/inputmask.bundle.js')}}"></script>
<script src="{{asset('js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>
<script>
    $(document).ready(function(){
        $('#jenis_surat').select2();
        $('#sifat_surat').select2();
        $(':input').inputmask();

        $('.tgl_surat').datepicker({
            orientation: "bottom left",
            format:'yyyy-mm-dd', // Notice the Extra space at the beginning
            todayHighlight:'TRUE',
            autoclose: true,
            todayBtn: "linked",
            clearBtn: true,
        });
        $('.tgl_acara').datepicker({
            orientation: "bottom left",
            format:'yyyy-mm-dd', // Notice the Extra space at the beginning
            todayHighlight:'TRUE',
            autoclose: true,
            todayBtn: "linked",
            clearBtn: true,
        });
        $('.tgl_sampai').datepicker({
            orientation: "bottom left",
            format:'yyyy-mm-dd', // Notice the Extra space at the beginning
            todayHighlight:'TRUE',
            autoclose: true,
            todayBtn: "linked",
            clearBtn: true,
        });

        $('#surat').change(function(){
            
            let reader = new FileReader();
         
            reader.onload = (e) => { 
         
              $('#preview-image-before-upload').attr('src', e.target.result); 
            }
         
            reader.readAsDataURL(this.files[0]); 
           
           });
        
        // Generate a password string
        function randString(){
            var chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNP123456789";
            var string_length = 8;
            var randomstring = '';
            for (var i = 0; i < string_length; i++) {
                var rnum = Math.floor(Math.random() * chars.length);
                randomstring += chars.substring(rnum, rnum + 1);
            }
            return randomstring;
        }
        
        // Create a new password
        $(".getNewPass").click(function(){
            var field = $('#password').closest('div').find('input[name="password"]');
            field.val(randString(field));
        });
        
    });
</script>
@endsection