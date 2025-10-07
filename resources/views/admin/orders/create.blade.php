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
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
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
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
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

                                <!-- Section Selector (Dynamic from DB) -->
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group">
                                        <label for="section">Select Section <span style="color:red">*</span></label>
                                        <select name="section" id="section"
                                            class="form-control @error('section') is-invalid @enderror" required>
                                            <option value="" disabled selected>-- Select Section --</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->slug }}">{{ ucfirst($section->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Items -->
                                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group">
                                        <label for="items">Items <span style="color:red">*</span></label>
                                        <textarea name="items" id="items" rows="3"
                                            class="form-control @error('items') is-invalid @enderror"
                                            placeholder="Enter item details..." required></textarea>
                                        @error('items')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description (Optional)</label>
                                        <textarea name="description" id="description" rows="3"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Additional notes or custom instructions..."></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Order Preview -->
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
    // Live Preview
    $('input, textarea, select').on('input change', function() {
        const orderNo = $('#order_no').val() || '-';
        const tableNo = $('#table_no').val() || '-';
        const section = $('#section option:selected').text() || '-';
        const items = $('#items').val() || '-';
        const description = $('#description').val() || '-';

        $('#order_preview').html(`
            <strong>Order No:</strong> ${orderNo}<br>
            <strong>Table:</strong> ${tableNo}<br>
            <strong>Section:</strong> ${section}<br>
            <strong>Items:</strong> ${items}<br>
            <strong>Description:</strong> ${description}
        `);
    });
});
</script>
@endsection
