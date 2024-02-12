@extends('pages.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card p-5">
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <span class="font-weight-bold">Name</span>
                            <p>{{ $data->name }}</p>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permission:</strong>
                                <div class="row mt-3">
                                    @foreach ($data_role_permissions as $value)
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <label>{{ $value->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
