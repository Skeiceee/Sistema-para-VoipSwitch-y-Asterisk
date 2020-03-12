@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-user-circle"></i><span class="font-weight-bold ml-2">Cuentas</span></div>
                        <div>
                            <a id="add_account" href="{{ route('accounts.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nueva cuenta.">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="documents" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theader-danger">
                                    <tr>
                                        <th>Titulo</th>
                                        <th>Descripción</th>
                                        <th>Usuario</th>
                                        <th>Contraseña</th>
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
<script> 
    var SITEURL = '{{ URL::to('').'/' }}'
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        let documentsTable = $('#documents').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            language:{ url: SITEURL + 'datatables/spanish' },
            ajax: { url: SITEURL + '/cuentas', type: 'GET' },
            columns: [
                { data: 'title', name: 'title' },
                {
                    data: 'description', 
                    name: 'description', 
                    render: function(data){
                        res = data.substring(0, 35);
                        if (data.length !== res.length) {
                            res += '...'
                            res = '<div data-placement="left" title="' + data + '">' + res + '</div>'
                        }
                        return res
                    }
                },
                { data: 'username', name: 'username' },
                { data: 'password', name: 'password'},
            ],
            drawCallback: function(settings, json) {
                $('[title]').tooltip();
            },
        })
        $('body').on('click', '.download', function () {
            var file_id = $(this).data('id')
            window.location = SITEURL + 'documentos/download/'+ file_id
        }); 

    })

    let addAccount = $('#add_account');
    addAccount.tooltip()

</script>
@endpush