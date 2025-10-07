@extends('admin.layout.app')
@section('title', 'Printers')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Printers</h4>
                    <a href="{{ route('printer.create') }}" class="btn btn-primary ml-auto">Add Printer</a>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped" id="printer_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Connector Value</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($printers as $printer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $printer->name }}</td>
                                    <td>{{ ucfirst($printer->type) }}</td>
                                    <td>{{ $printer->connector_value }}</td>
                                    <td>{{ $printer->section ?? '—' }}</td>
                                    <td>
                                        <label class="custom-switch">
                                            <input type="checkbox" class="custom-switch-input toggle-status"
                                                data-id="{{ $printer->id }}"
                                                {{ $printer->is_active ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">
                                                {{ $printer->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </td>
                                    <td>{{ $printer->created_at ? $printer->created_at->format('d M Y') : '—' }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('printer.edit', $printer->id) }}"
                                                class="btn btn-sm btn-primary mr-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('printer.destroy', $printer->id) }}"
                                                  method="POST" id="delete-form-{{ $printer->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="btn btn-sm btn-danger show_confirm"
                                                    data-form="delete-form-{{ $printer->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {

    // ✅ DataTable Initialization
    $('#printer_table').DataTable();

    // ✅ Delete Confirmation
    $(document).on('click', '.show_confirm', function(e) {
        e.preventDefault();
        let formId = $(this).data('form');
        let form = $('#' + formId);

        Swal.fire({
            title: 'Are you sure?',
            text: "This printer will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // ✅ Toggle Status
    $('.toggle-status').on('change', function() {
        let printerId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;
        let $description = $(this).closest('.custom-switch').find('.custom-switch-description');

        $.ajax({
            url: "{{ route('admin.printer.toggleStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: printerId,
                is_active: status
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $description.text(status ? 'Active' : 'Inactive');
                } else {
                    toastr.error(response.message || 'Failed to update status.');
                }
            },
            error: function() {
                toastr.error('Something went wrong.');
            }
        });
    });

});
</script>
@endsection
