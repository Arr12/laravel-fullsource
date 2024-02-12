@extends('pages.layouts.app')
@push('after-script')
    <script>
        $(document).ready(function() {
            // $("#example1").DataTable({
            //     "responsive": true,
            //     "lengthChange": false,
            //     "autoWidth": false,
            //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            loadData();
        });

        function loadData() {
            $("#serverside").DataTable({
                processing: true,
                pagination: true,
                responsive: false,
                serverSide: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: "{{ route('users.serverside') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'is_verified',
                        name: 'is_verified'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        }
    </script>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="text-red-500">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">DataTable with default features</h3>
                        <a class="btn btn-primary" href="{{ route('users.create') }}">
                            <i class="fas fa-plus"></i> Tambah
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        <table id="serverside" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Verified</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $d->photo }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->username }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->email_verified_at }}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{ route('users.edit', $d->uuid) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger" id="delete"
                                                href="{{ route('users.destroy', $d->uuid) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
@endsection
