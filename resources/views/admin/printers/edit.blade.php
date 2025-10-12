@extends('admin.layout.app')
@section('title', 'Edit Printer')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url('admin/printers') }}">Back</a>
                <form action="{{ route('printers.update', $printer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Printer</h4>
                                <div class="row mx-0 px-4">

                                    <!-- Printer Name -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="name">Printer Name <span style="color:red">*</span></label>
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $printer->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Type -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="type">Printer Type <span style="color:red">*</span></label>
                                            <select name="type" id="type"
                                                class="form-control @error('type') is-invalid @enderror" required>
                                                <option value="" disabled>-- Select Type --</option>
                                                <option value="windows" {{ old('type', $printer->type) == 'windows' ? 'selected' : '' }}>Windows</option>
                                                <option value="network" {{ old('type', $printer->type) == 'network' ? 'selected' : '' }}>Network</option>
                                                <option value="serial" {{ old('type', $printer->type) == 'serial' ? 'selected' : '' }}>Serial</option>
                                                <option value="linux_usb" {{ old('type', $printer->type) == 'linux_usb' ? 'selected' : '' }}>Linux USB</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Connector Value -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="connector_value">Connector Value <span style="color:red">*</span></label>
                                            <input type="text" name="connector_value" id="connector_value"
                                                class="form-control @error('connector_value') is-invalid @enderror"
                                                value="{{ old('connector_value', $printer->connector_value) }}" required>
                                            @error('connector_value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Section -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="section">Section <span style="color:red">*</span></label>
                                            <input type="text" name="section" id="section"
                                                class="form-control @error('section') is-invalid @enderror"
                                                value="{{ old('section', $printer->section) }}">
                                            @error('section')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    

                                </div>

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Hide validation errors on focus
            $('input, select').on('focus', function() {
                const $feedback = $(this).siblings('.invalid-feedback');
                if ($feedback.length) {
                    $feedback.hide();
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
