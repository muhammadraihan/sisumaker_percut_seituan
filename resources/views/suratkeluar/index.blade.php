@extends('layouts.page')

@section('title', 'Surat keluar Management')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">
<link rel="stylesheet" media="screen, print" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" media="screen, print"
    href="{{asset('css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <meta http-equiv="refresh" content="120" > 
@endsection

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-users'></i> Module: <span class='fw-300'>Surat Keluar </span>
        <small>
            Module for manage Surat Keluar .
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>
                    Surat Keluar  <span class="fw-300"><i>List</i></span>
                </h2>
                <div class="panel-toolbar">
                    @hasrole('superadmin')
                    <a class="nav-link active" href="{{route('suratkeluar.create')}}"><i class="fal fa-plus-circle">
                        </i>
                        <span class="nav-link-text">Add New</span>
                    </a>
                    @endhasrole
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-3">
                            <label>Dari tanggal</label>
                            <input type="text" class="form-control js-bg-target" placeholder="Tahun" id="start_date"
                                name="start_date" autocomplete="off">
                        </div>
                        <div id="" class="form-group col-md-3 mb-3">
                            <label>Sampai tanggal</label>
                            <input type="text" class="form-control js-bg-target" placeholder="Bulan" id="end_date"
                                name="end_date" autocomplete="off">
                        </div>
                        <div id="" class="form-group col-md-5 mb-3">
                            <button type="button" name="filter" id="filter" class="btn btn-primary"
                                disabled="disabled">Filter</button>
                            <button type="button" name="resetFilter" id="resetFilter" class="btn btn-primary"
                                disabled="disabled">Reset</button>
                        </div>
                    <!-- datatable start -->
                    <table id="datatable" class="table table-bordered table-hover table-striped w-100">
        <thead>
            <tr>
                <th>No</th>
                <th>Perihal</th>
                <th>Tanggal Surat</th>
                <th>Tujuan</th>
                <th>Keterangan</th>
                <th width="120px">Action</th>
                </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="" method="POST" class="delete-form">
    {{ csrf_field() }}
    <!-- Delete modal center -->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Confirmation
                        <small class="m-0 text-muted">
                        </small>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure want to delete data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary remove-data-from-delete-form"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Delete Data</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script src="{{asset('js/datagrid/datatables/datatables.bundle.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="{{asset('js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

<script>
    $(document).ready(function(){
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
     
     
       var table = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "pageLength" : 50,
            "order": [[ 0, "asc" ]],
            "ajax":{
                url:'{{route('suratkeluar.index')}}',
                type : "GET",
                dataType: 'json',
                error: function(data){
                    console.log(data);
                    }
            },
            "dom": 'Bfrtip',
            "buttons": [
                'excel', 'print'
            ],
            "columns": [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'perihal', name: 'perihal'},
            {data: 'tgl_surat', name: 'tgl_surat'},
            {data: 'tujuan', name: 'tujuan'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#start_date').datepicker({
            orientation: "bottom left",
            format:'yyyy-mm-dd', // Notice the Extra space at the beginning
            todayHighlight:'TRUE',
            autoclose: true,
            todayBtn: "linked",
            clearBtn: true,
        });
        $('#end_date').datepicker({
            orientation: "bottom left",
            format:'yyyy-mm-dd', // Notice the Extra space at the beginning
            todayHighlight:'TRUE',
            autoclose: true,
            todayBtn: "linked",
            clearBtn: true,
        });

        $('.form-control').change(function(e){
            $('#filter').attr('disabled',false);
            $('#resetFilter').attr('disabled',false);
        });

        
        $('#filter').click(function (e){
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            $('#datatable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "searching": false,
            "pageLength" : 50,
            "order": [[ 0, "asc" ]],
            "ajax":{
                url:'{{route('get.filters')}}',
                type : "GET",
                data: {
                       start_date: start_date,
                       end_date: end_date,
                    },
                dataType: 'json',
                error: function(data){
                    console.log(data);
                    }
            },
            "dom": 'Bfrtip',
            "buttons": [
                'excel', 'print'
            ],
            "columns": [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'perihal', name: 'perihal'},
            {data: 'tgl_surat', name: 'tgl_surat'},
            {data: 'tujuan', name: 'tujuan'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});

    $('#resetFilter').click(function(e){
            $('#start_date').val('').datepicker("update");
            $('#end_date').val('').datepicker("update");
            $('#filter').attr('disabled',true);
            $('#resetFilter').attr('disabled',true);
            
            $('#datatable').DataTable({
                "destroy" : true,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "searching": false,
                "pageLength" : 50,
            "order": [[ 0, "asc" ]],
            "ajax":{
                url:'{{route('suratkeluar.index')}}',
                type : "GET",
                dataType: 'json',
                error: function(data){
                    console.log(data);
                    }
            },
            "dom": 'Bfrtip',
            "buttons": [
                'excel', 'print'
            ],
            "columns": [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'perihal', name: 'perihal'},
            {data: 'tgl_surat', name: 'tgl_surat'},
            {data: 'tujuan', name: 'tujuan'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
    // Delete Data
    $('#datatable').on('click', '.delete-btn[data-url]', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            var token = $(this).attr('data-token');
            console.log(id,url,token);
            
            $(".delete-form").attr("action",url);
            $('body').find('.delete-form').append('<input name="_token" type="hidden" value="'+ token +'">');
            $('body').find('.delete-form').append('<input name="_method" type="hidden" value="DELETE">');
            $('body').find('.delete-form').append('<input name="id" type="hidden" value="'+ id +'">');
        });

        // Clear Data When Modal Close
        $('.remove-data-from-delete-form').on('click',function() {
            $('body').find('.delete-form').find("input").remove();
        });
    });
</script>
@endsection
