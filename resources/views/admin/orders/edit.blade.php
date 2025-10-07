@extends('admin.layout.app')
@section('title', 'Edit Order')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <a class="btn btn-primary mb-3" href="{{ url('admin/orders') }}">Back</a>

            <form id="edit_order" action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <h4 class="text-center my-4">Edit Order</h4>
                            <div class="row mx-0 px-4">

                                <!-- Order No -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Order No <span style="color:red">*</span></label>
                                        <input type="text" name="order_no"
                                            class="form-control @error('order_no') is-invalid @enderror"
                                            value="{{ old('order_no', $order->order_no) }}" required>
                                        @error('order_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Table No -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Table No <span style="color:red">*</span></label>
                                        <input type="text" name="table_no"
                                            class="form-control @error('table_no') is-invalid @enderror"
                                            value="{{ old('table_no', $order->table_no) }}" required>
                                        @error('table_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Section -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Section <span style="color:red">*</span></label>
                                        <select name="section"
                                            class="form-control @error('section') is-invalid @enderror" required>
                                            <option disabled>-- Select Section --</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->slug }}"
                                                    {{ old('section', $order->section) == $section->slug ? 'selected' : '' }}>
                                                    {{ ucfirst($section->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Items -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Items <span style="color:red">*</span></label>
                                        <textarea name="items" rows="3"
                                            class="form-control @error('items') is-invalid @enderror"
                                            required>{{ old('items', $order->items) }}</textarea>
                                        @error('items')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Description (Optional)</label>
                                        <textarea name="description" rows="3"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Additional notes...">{{ old('description', $order->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Order Preview -->
                                <div class="col-12 mb-3">
                                    <h6>Preview</h6>
                                    <div id="order_preview" class="border rounded p-3 bg-light">
                                        <strong>Order No:</strong> {{ $order->order_no }}<br>
                                        <strong>Table:</strong> {{ $order->table_no }}<br>
                                        <strong>Section:</strong> {{ ucfirst($order->section) }}<br>
                                        <strong>Items:</strong> {{ $order->items }}<br>
                                        <strong>Description:</strong> {{ $order->description ?? '-' }}
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" id="sendForPrep" class="btn btn-success">Send for Preparation</button>
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
$(document).ready(function () {
    // 🔹 Live Preview Update
    $('input, textarea, select').on('input change', function () {
        const orderNo = $('[name="order_no"]').val() || '-';
        const tableNo = $('[name="table_no"]').val() || '-';
        const section = $('[name="section"] option:selected').text() || '-';
        const items = $('[name="items"]').val() || '-';
        const description = $('[name="description"]').val() || '-';

        $('#order_preview').html(`
            <strong>Order No:</strong> ${orderNo}<br>
            <strong>Table:</strong> ${tableNo}<br>
            <strong>Section:</strong> ${section}<br>
            <strong>Items:</strong> ${items}<br>
            <strong>Description:</strong> ${description}
        `);
    });

    // 🔹 Print Order (Local Demo)
    $('#sendForPrep').on('click', function () {
        const section = $('[name="section"]').val();
        const orderData = $('#order_preview').html();

        if (!section) {
            alert('Please select a section before sending.');
            return;
        }

        if (section === 'kitchen') {
            printKitchenOrder(orderData);
        } else if (section === 'cold_bar') {
            printColdBarOrder(orderData);
        }

        alert('Order sent for preparation!');
    });

    // 🔹 Simulated Print Functions (for demo)
    function printKitchenOrder(orderData) {
        const w = window.open('', '', 'height=400,width=300');
        w.document.write('<h3>🧾 Kitchen Order</h3>');
        w.document.write('<hr>');
        w.document.write(orderData);
        w.document.write('<hr><p>Printed from POS System</p>');
        w.print();
        w.close();
    }

    function printColdBarOrder(orderData) {
        const w = window.open('', '', 'height=400,width=300');
        w.document.write('<h3>🥶 Cold Bar Order</h3>');
        w.document.write('<hr>');
        w.document.write(orderData);
        w.document.write('<hr><p>Printed from POS System</p>');
        w.print();
        w.close();
    }
});
</script>
@endsection
