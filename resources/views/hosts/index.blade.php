@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-network-wired"></i><span class="font-weight-bold ml-2">Hosts</span></div>
                        <div class="float-right" style="width: 200px">
                            <div class="input-group">
                                <select class="form-control form-control-sm" id="subnet">
                                    @foreach ($subredes as $subred)
                                        <option value="{{ $subred->id }}">{{ $subred->ip }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" id="filtrar"><i class="fas fa-filter"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    @include('Common.status')
                    <div class="card">
                        <div class="container-fluid table-responsive card-body">
                            <table id="Hosts" class="table table-bordered table-hover table-striped dt-responsive display nowrap" cellspacing="0" width="100%">
                                <thead class = "theade-danger">
                                <tr>
                                    <th>Dirección IP</th>
                                    <th class="no-sort" width=10>Acciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script>$("a").tooltip()</script>
<script>
    var SITEURL = '{{ URL::to('').'/' }}'
    $(document).ready(function(){
    let token = $('meta[name=csrf_token]').attr("content")
    var table = $('#Hosts').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        deferLoading: 0,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: "[]", type: 'GET' },
        columns:[
            {data: 'ip', name: 'ip'},
            {data: 'btn', orderable: false, searchable: false},
        ]
    });

    $('#filtrar').click(function(){
        let subnet = $('#subnet');
        if(subnet.val() === ''){

        }else{
            let url = SITEURL + '/hosts?subred=';
            if(subnet.val()){
                url = url + subnet.val();
            }
            url = url.replace(/ /gi, '%20')
            table.ajax.url(url).load();
        }
    })

    });
</script>
@endpush