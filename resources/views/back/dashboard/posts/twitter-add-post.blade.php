@extends('back.layouts.app')
@section("posts","show")
@section('content')

    <div class="row justify-content-lg-center py-4">
        <div class="h1">Add Post To Twitter</div>
        @include('back.dashboard.inc.message')
        <div class="col-12 mb-4">
            <form action="{{route("admin.posts.twitter-send-post")}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="textarea">Post</label>
                    <textarea class="form-control" placeholder="Enter your message..." id="textarea" name="content" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="select-page">Select Page</label>
                    <select class="form-select" name="accounts[]" multiple aria-label="multiple select example" id="select-page" style="height: 200px" >
                        @if (count($accounts) > 0)
                        @foreach ($accounts as $account )
                        <option value="{{$account->id}}">{{$account->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Add image</label>
                    <input class="form-control" type="file" id="formFile" name="image">
                </div>
                <button class="btn form-control bg-success text-white" type="submit">Send Post</button>
            </form>
        </div>
    </div>
@endsection
