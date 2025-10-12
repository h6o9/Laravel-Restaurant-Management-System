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
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">Create New Order</h4>

                            <div class="row mx-0 px-4">

                                <!-- Order Number -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="order_no">Order No <span style="color:red">*</span></label>
                                        <input type="text" name="order_no" id="order_no"
                                            class="form-control @error('order_no') is-invalid @enderror"
                                            placeholder="Enter Order Number" required>
                                        @error('order_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Table Number -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="table_no">Table No <span style="color:red">*</span></label>
                                        <input type="text" name="table_no" id="table_no"
                                            class="form-control @error('table_no') is-invalid @enderror"
                                            placeholder="Enter Table Number" required>
                                        @error('table_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Section Selector -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="section">Select Section <span style="color:red">*</span></label>

                                        @if($sections->isNotEmpty())
                                            <select name="section" id="section"
                                                class="form-control @error('section') is-invalid @enderror" required>
                                                <option value="" disabled selected>-- Select Section --</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->section }}">
                                                        {{ ucfirst($section->section) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="alert alert-warning py-2 mb-0">
                                                <strong>⚠ Please add printer device first.</strong>
                                            </div>
                                        @endif

                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                               <!-- Items Multi Select -->
<div class="col-sm-12">
    <div class="form-group">
        <label for="items">Select Items <span style="color:red">*</span></label>
        <select id="items" name="items[]" class="form-control" multiple required>
            @foreach($menuItems as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Quantity Fields (Dynamic) -->
<div id="quantity_fields" class="col-sm-12">
</div>


                                <!-- Description -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description (Optional)</label>
                                        <textarea name="description" id="description" rows="3"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Additional notes..."></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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

    // Handle item selection & quantity input
    $('#items').on('change', function() {
        const selected = $(this).val() || [];
        const container = $('#quantity_inputs');
        container.empty();

        selected.forEach(item => {
            container.append(`
                <div class="d-flex align-items-center mb-2">
                    <label class="mr-2 mb-0" style="width:100px">${item}:</label>
                    <input type="number" name="quantities[${item}]" min="1" value="1" class="form-control w-25 item-qty" required>
                </div>
            `);
        });

        updatePreview();
    });

    // Live preview for all fields
    $('input, textarea, select').on('input change', function() {
        updatePreview();
    });

    function updatePreview() {
        const orderNo = $('#order_no').val() || '-';
        const tableNo = $('#table_no').val() || '-';
        const section = $('#section option:selected').text() || '-';
        const description = $('#description').val() || '-';
        const now = new Date().toLocaleString();

        let itemsPreview = '';
        $('#quantity_inputs .d-flex').each(function() {
            const name = $(this).find('label').text().replace(':', '');
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
