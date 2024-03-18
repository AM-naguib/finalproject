@extends('back.layouts.app')
@section("posts","show")
@section('content')

    <div class="row justify-content-lg-center py-4">
        <div class="h1">Add Post To Twitter</div>
        <div class="col-12 mb-4">
            <form action="{{route("admin.posts.pages-send-post")}}" method="post">

                @csrf
                <div class="mb-3">
                    <label for="textarea">Post</label>
                    <textarea class="form-control" placeholder="Enter your message..." id="textarea" name="content" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="select-page">Select Page</label>
                    <select class="form-select" name="pages[]" multiple aria-label="multiple select example" id="select-page" style="height: 200px" >
                        @if (count($groups) > 0)
                        @foreach ($groups as $group )
                        <option value="{{$group->group_id}}">{{$group->name}}</option>

                        @endforeach
                        @endif
                    </select>
                </div>
                <button class="btn btn-info mb-3" type="button">Draft</button>
                <button class="btn form-control bg-success text-white" type="submit">Send Post</button>
            </form>
        </div>
    </div>
@endsection
