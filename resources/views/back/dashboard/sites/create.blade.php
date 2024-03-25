@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <h1>Add Scraping Site</h1>
    </div>
    @include('back.dashboard.inc.message')
    <div class="row justify-content-lg-center">
        <div class="col-12 col-lg-4">
            <div id="message">
                <div class="alert">

                </div>
            </div>
            <div class="col-12">

                <form action="{{ route('admin.sites.store') }}" class="form" id="form" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name"
                            placeholder="Enter Site Name" value="{{old('site_name')}}">
                    </div>
                    <div class="mb-3">
                        <label for="site_link" class="form-label">Site Link</label>
                        <input type="url" class="form-control" id="site_link" name="site_link"
                            placeholder="Enter Site Link" value="{{old('site_link')}}">
                    </div>
                    <div class="mb-3">
                        <label for="post_link_selector" class="form-label">Post Link Selector</label>
                        <input type="text" class="form-control" id="post_link_selector" name="post_link_selector"
                            placeholder="Enter Post Link Selector" value="{{old('post_link_selector')}}">
                    </div>
                    <div class="mb-3">
                        <label for="post_title_selector" class="form-label">Post Title Selector</label>
                        <input type="text" class="form-control" id="post_title_selector" name="post_title_selector"
                            placeholder="Enter Post Title Selector" value="{{old('post_title_selector')}}">
                    </div>
                    <button class="btn btn-primary" type="click" id="selectorCheck" data-bs-toggle="modal"
                    data-bs-target="#modal-default">Check Selector</button>

                    <div class="my-3">
                        <label for="">Select Pages For Publish</label>
                        <select name="pages[]" id="" class="form-select" multiple>
                            @if (count($pages) > 0)
                            @foreach ($pages as $page)
                            <option value="{{$page->id}}">{{$page->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn form-control  bg-success text-white">Add Site</button>
                </form>
            </div>
        </div>


            <div class="modal fade" id="modal-default" tabindex="-1" aria-labelledby="modal-default" style="display: none;"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="h6 modal-title">Extracted Data</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal-body">

                        </div>
                        <div class="modal-footer"> <button
                                type="button" class="btn btn-link text-gray-600 ms-auto"
                                data-bs-dismiss="modal">Close</button></div>
                    </div>
                </div>
            </div>

        <script>
            let selectorCheck = document.querySelector('#selectorCheck');
            selectorCheck.addEventListener("click", (e) => {
                e.preventDefault();

                let siteLink = document.querySelector('#site_link');
                let postLink = document.querySelector('#post_link_selector');
                let postTitle = document.querySelector('#post_title_selector');
                postLink = encodeURIComponent(postLink.value);
                postTitle = encodeURIComponent(postTitle.value);
                $url =
                    `http://127.0.0.1:1120/get?post_url=${postLink}&post_title=${postTitle}&site_url=${siteLink.value}`;

                let xhr = new XMLHttpRequest();
                xhr.open("GET", $url)
                xhr.send();
                xhr.onload = () => {
                    let res = JSON.parse(xhr.responseText);
                    let modal = document.querySelector('#modal-body');
                    for(let i = 1; i< res.length; i++){
                        if(i < 4){
                            if(res[i].url == null || res[i].title == null){
                                modal.innerHTML = "Selector Not Found"
                            }else{
                                if(i == 1){
                                modal.innerHTML = ""

                                }
                                modal.innerHTML += `
                                <p class="mb-1">Title:</p>
                                <p class="mb-1">${res[i].title}</p>
                                <p class="mb-1">Link:</p>
                                <p class="mb-1">${res[i].url}</p>
                                `
                            }
                        }
                    }
                }

            })
        </script>

    </div>
    </div>
@endsection
