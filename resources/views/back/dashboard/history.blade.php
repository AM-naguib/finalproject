@extends('back.layouts.app')
@section('content')

    <div class="row justify-content-lg-center py-4">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="table-responsive py-4">
                    <table class="table table-flush" id="datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Content</th>
                                <th>Type</th>
                                <th>Post Link</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($history) > 0)
                                @foreach ($history as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->content }}</td>
                                        <td>{{ $value->type }}</td>
                                        <td><a style="color: blue" href="{{ $value->post_link }}"
                                                target="_blank">Click Here</a>
                                        </td>
                                        <td>{{ $value->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
