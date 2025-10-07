@extends('admin.layout.app')
@section('title', 'Orders')

@section('content')
<div class="main-content" style="min-height: 562px;">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Orders List</h4>
                        </div>
                        <div class="card-body table-striped table-bordered table-responsive">
                            <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Create Order</a>
                            <table class="table" id="orders_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Table No</th>
                                        <th>Section</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Items</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->table_no }}</td>
                                            <td>{{ $order->section->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($order->status == 'ready')
                                                    <span class="badge badge-success">Ready</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->description ?? '—' }}</td>
                                            <td>
                                                @foreach($order->items as $item)
                                                    <div>- {{ $item['name'] }} x {{ $item['quantity'] }}</div>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary mr-1">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button class="btn btn-sm btn-danger show_delete_order" data-form="delete-form-{{ $order->id }}">
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
            </div>
        </div>
    </section>
</div>
@endsection


@section('js')
<script>
$(document).ready(function () {

    // ✅ Initialize DataTable
    if ($.fn.DataTable.isDataTable('#orders_table')) {
        $('#orders_table').DataTable().destroy();
    }
    $('#orders_table').DataTable();

    // ✅ SweetAlert delete with reason input
    $(document).on('click', '.show_delete_order', function (e) {
        e.preventDefault();
        let formId = $(this).data('form');
        let form = document.getElementById(formId);

        Swal.fire({
            title: 'Delete this order?',
            text: "This action cannot be undone.",
            input: 'text',
            inputLabel: 'Reason for deletion',
            inputPlaceholder: 'Enter reason...',
            inputValidator: (value) => {
                if (!value) {
                    return 'Please enter a reason!';
                }
            },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        reason: result.value
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message || 'Order deleted successfully.',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => location.reload());
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to delete order.', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endsection
