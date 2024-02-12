@extends('pages.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card p-5">
                    <div class="row">
                        <img class="img-fluid" onerror="this.src='/images/blank-profile.png'"
                            style="height: 520px;object-fit:contain;" src="{{ $data->photo }}" alt="{{ $data->name }}"
                            width="100%" height="520" />
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <span class="font-weight-bold">Username</span>
                            <p>{{ $data->username }}</p>
                        </div>
                        <div class="col-md-12">
                            <span class="font-weight-bold">Name</span>
                            <p>{{ $data->name }}</p>
                        </div>
                        <div class="col-md-12">
                            <span class="font-weight-bold">Email</span>
                            <p>{{ $data->email }}</p>
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
