@extends('backend.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('backend.role.create') }}" class="btn btn-primary">Add Role</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom w-100" id="users-table">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Name</th>
                                <th width="180" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- dynamic data will come from DataTables --}}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts-bottom')
<script>
$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        }
    });

    if (!$.fn.DataTable.isDataTable('#users-table')) {
        $('#users-table').DataTable({
            order: [],
            processing: true,
            serverSide: true,
            responsive: true,
            pagingType: "full_numbers",
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
            ajax: "{{ route('backend.role.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                { data: 'name', name: 'name', className: "text-start" },
                { data: 'action', name: 'action', className: "text-end", orderable: false, searchable: false }
            ]
        });
    }

});
function edit(id) {
    let url = "{{ route('backend.role.edit', ':id') }}";
    url = url.replace(':id', id);

    window.location.href = url;
}
</script>
@endpush