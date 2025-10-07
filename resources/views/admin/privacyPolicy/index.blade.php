@extends('admin.layout.app')
@section('title', 'Privacy & Policy')
@section('content')

    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Privacy & Policy</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">


                                <table class="table" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Detail</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>

                                            <td title="{{ strip_tags(html_entity_decode($data->description)) }}">
                                                @if ($data && $data->description)
                                                    {!! Str::limit(strip_tags($data->description), 200, '...') !!}
                                                @else
                                                    <p>No description available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-4">

                                                    @if (Auth::guard('admin')->check() ||
                                                            ($sideMenuPermissions->has('Privacy & Policy') && $sideMenuPermissions['Privacy & Policy']->contains('edit')))
                                                        <a href="{{ url('/admin/privacy-policy-edit') }}"
                                                            class="btn btn-primary"><span class="fa fa-edit"></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#table_id_events').DataTable()
        })
    </script>

@endsection
