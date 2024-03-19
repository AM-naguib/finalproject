@extends('back.layouts.app')
@section("posts","show")
@section('content')
    <div class="py-4">
        <div class="dropdown">
            <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                New Task
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.fbpages.get') }}">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M13.09 3.294c1.924.95 3.422 1.69 5.472.692a1 1 0 0 1 1.438.9v9.54a1 1 0 0 1-.562.9c-2.981 1.45-5.382.24-7.25-.701a38.739 38.739 0 0 0-.622-.31c-1.033-.497-1.887-.812-2.756-.77-.76.036-1.672.357-2.81 1.396V21a1 1 0 1 1-2 0V4.971a1 1 0 0 1 .297-.71c1.522-1.506 2.967-2.185 4.417-2.255 1.407-.068 2.653.453 3.72.967.225.108.443.216.655.32Z" />
                    </svg>

                    Update Pages
                </a>


            </div>
        </div>
    </div>
    <div class="row justify-content-lg-center">
        <div class="h1">Add Post To Pages</div>
        @include("back.dashboard.inc.message")
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
                        @if (count($pages) > 0)
                        @foreach ($pages as $page )
                        <option value="{{$page->page_id}}">{{$page->name}}</option>

                        @endforeach
                        @endif
                    </select>
                </div>

                <button class="btn form-control bg-success text-white" type="submit">Send Post</button>
            </form>
        </div>
    </div>
@endsection
