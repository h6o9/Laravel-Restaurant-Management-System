@extends('admin.layout.app')
@section('title', 'Create Printer')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ url('admin/printers') }}">Back</a>
                <form id="add_printer" action="{{ route('printers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Create Printer</h4>
                                <div class="row mx-0 px-4">

                                    <!-- Printer Name -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="name">Printer Name <span style="color:red">*</span></label>
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" required placeholder="Enter printer name">
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
                                                <option value="" disabled selected>-- Select Type --</option>
                                                <option value="windows" {{ old('type') == 'windows' ? 'selected' : '' }}>Windows</option>
                                                <option value="network" {{ old('type') == 'network' ? 'selected' : '' }}>Network</option>
                                                <option value="serial" {{ old('type') == 'serial' ? 'selected' : '' }}>Serial</option>
                                                <option value="linux_usb" {{ old('type') == 'linux_usb' ? 'selected' : '' }}>Linux USB</option>
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
                                                value="{{ old('connector_value') }}" required
                                                placeholder="e.g. Kitchen_Printer or 192.168.1.50">
                                            @error('connector_value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Section -->
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="section">Section (Optional)</label>
                                            <input type="text" name="section" id="section"
                                                class="form-control @error('section') is-invalid @enderror"
                                                value="{{ old('section') }}" placeholder="Enter section name e.g. Kitchen">
                                            @error('section')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-sm-12 pl-sm-0 pr-sm-3">
                                        <div class="form-group">
                                            <label for="description">Description (Optional)</label>
                                            <textarea name="description" id="description" rows="3"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="Add printer details here...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="card-footer text-center row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-bg" id="submit">Save Printer</button>
                                        </div>
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
    @if (session('success'))
        <script>
            toastr.success(@json(session('success')));
        </script>
    @endif
@endsection
