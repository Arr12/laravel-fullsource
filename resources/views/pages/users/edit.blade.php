@extends('pages.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="text-red-500">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('users.update', $data->uuid) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputUsername">Username</label>
                                <input type="text" class="form-control" id="exampleInputUsername" name="username"
                                    value="{{ $data->username }}" placeholder="Username" required />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputName">Name</label>
                                <input type="text" class="form-control" id="exampleInputName" name="name"
                                    value="{{ $data->name }}" placeholder="Name" required />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" name="email"
                                    value="{{ $data->email }}" placeholder="Enter email" required />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                                    value="" placeholder="Password" />
                            </div>
                            <div class="form-group">
                                <img class="img-fluid img-thumbnail" src="{{ $data->photo }}"
                                    onerror="this.src='/images/blank-profile.png'" alt="{{ $data->name }}"
                                    width="200px" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="photos"
                                            id="exampleInputFile" />
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Roles</label>
                                <select class="form-control select2" style="width: 100%; height: 50px;" name="roles">
                                    <option selected="selected">Select Roles</option>
                                    @foreach ($role as $r)
                                        <option value="{{ $r->name }}"
                                            {{ isset($role_user[0]) ? (trim($role_user[0]) == $r->name ? 'selected' : '') : '' }}>
                                            {{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1" name='verified'
                                    {{ $data->email_verified_at ? 'checked disabled' : '' }} />
                                <label class="form-check-label" for="exampleCheck1">Is Verified</label>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
