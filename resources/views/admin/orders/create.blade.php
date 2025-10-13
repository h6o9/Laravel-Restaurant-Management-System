@extends('admin.layout.app')

@section('title', 'Create Order')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <a class="btn btn-primary mb-3" href="{{ url('admin/orders') }}">Back</a>

            <form id="create_order" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <h4 class="text-center my-4">🧾 Create New Order</h4>

                            <div class="row mx-0 px-4">

                                <!-- Order No -->
                                <!-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="order_no">Order No <span style="color:red">*</span></label>
                                        <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Enter Order Number" required>
                                    </div>
                                </div> -->

                                <!-- Table No -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="table_no">Table No <span style="color:red">*</span></label>
                                        <input type="text" name="table_no" id="table_no" class="form-control" placeholder="Enter Table Number" required>
                                    </div>
                                </div>

                                <!-- Section -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="section">Select Section <span style="color:red">*</span></label>
                                        <select name="section" id="section" class="form-control" required>
                                            <option value="" disabled selected>-- Select Section --</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->section }}">{{ ucfirst($section->section) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Items Searchable Dropdown -->
                                <div class="col-sm-12">
                                    <div class="form-group position-relative">
                                        <label>Select Items <span style="color:red">*</span></label>
                                        <input type="text" id="item_search" class="form-control" placeholder="Search & click to add item">
                                        <ul id="item_list" class="list-group position-absolute w-100 shadow-sm" style="z-index:10; display:none; max-height:200px; overflow-y:auto;">
                                            @foreach($menuItems as $item)
                                                <li class="list-group-item list-item" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                    {{ $item->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- Selected Items List -->
                                <div class="col-sm-12 mb-3">
                                    <ul id="selected_items" class="list-group"></ul>
                                </div>

                                <!-- Description -->
                                <div class="col-sm-12 mb-3">
                                    <label>Description (Optional)</label>
                                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Additional notes..."></textarea>
                                </div>

                                <!-- Preview -->
                                <div class="col-12 mb-3">
                                    <h6>Preview</h6>
                                    <div id="order_preview" class="border rounded p-3 bg-light">
                                        <small>No data yet. Fill fields to preview order.</small>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success">Send for Preparation</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    const userType = "{{ auth()->user()->type ?? 'N/A' }}";

    // Show item list on focus
    $('#item_search').on('focus input', function() {
        const search = $(this).val().toLowerCase();
        $('#item_list').show();
        $('#item_list li').each(function() {
            const name = $(this).text().toLowerCase();
            $(this).toggle(name.includes(search));
        });
    });

    // Hide list when clicked outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#item_search, #item_list').length) {
            $('#item_list').hide();
        }
    });

    // Add item to selected list
    $('#item_list').on('click', '.list-item', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        if ($(`#selected_items li[data-id="${id}"]`).length === 0) {
            $('#selected_items').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${id}">
                    <span>${name}</span>
                    <div class="d-flex align-items-center">
                        <input type="number" name="quantities[${id}]" min="1" value="1"
                            class="form-control form-control-sm w-25 mr-2 qty-input" required>
                        <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                    </div>
                </li>
            `);
        }

        $('#item_search').val('');
        $('#item_list').hide();
        updatePreview();
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('li').remove();
        updatePreview();
    });

    // Update preview on any input change
    $(document).on('input change', 'input, textarea, select', function() {
        updatePreview();
    });

    function updatePreview() {
        const orderNo = $('#order_no').val() || '-';
        const tableNo = $('#table_no').val() || '-';
        const section = $('#section option:selected').text() || '-';
        const description = $('#description').val() || '-';
        const now = new Date().toLocaleString();

        let itemsPreview = '';
        $('#selected_items li').each(function() {
            const name = $(this).find('span').text();
            const qty = $(this).find('input').val();
            itemsPreview += `<li>${name} — Qty: ${qty}</li>`;
        });

        if (!itemsPreview) itemsPreview = '<li>No items selected</li>';

        $('#order_preview').html(`
            <strong>Order No:</strong> ${orderNo}<br>
            <strong>Table:</strong> ${tableNo}<br>
            <strong>Section:</strong> ${section}<br>
            <strong>User Type:</strong> ${userType}<br>
            <strong>Date & Time:</strong> ${now}<br>
            <strong>Items:</strong>
            <ul>${itemsPreview}</ul>
            <strong>Description:</strong> ${description}
        `);
    }
});
</script>
@endsection
