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
                            placeholder="Enter Site Name" value="">
                    </div>
                    <div class="mb-3">
                        <label for="site_link" class="form-label">Site Link</label>
                        <input type="url" class="form-control" id="site_link" name="site_link"
                            placeholder="Enter Site Link" value="">
                    </div>
                    <div class="mb-3">
                        <label for="post_link_selector" class="form-label">Post Link Selector</label>
                        <input type="text" class="form-control" id="post_link_selector" name="post_link_selector"
                            placeholder="Enter Post Link Selector" value="">
                    </div>
                    <div class="mb-3">
                        <label for="post_title_selector" class="form-label">Post Title Selector</label>
                        <input type="text" class="form-control" id="post_title_selector" name="post_title_selector"
                            placeholder="Enter Post Title Selector" value="">
                    </div>
                    <button type="submit" class="btn form-control  bg-success text-white">Add Site</button>
                </form>
            </div>
        </div>
        {{--
            <script>
                let form = document.querySelector('#form');
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    let formData = new FormData(form);
                    let jsonData = {};
                    formData.forEach((value, key) => {
                        jsonData[key] = value;
                    });
                    let jsonString = JSON.stringify(jsonData);
                    let xhr = new XMLHttpRequest();
                    xhr.open(form.method, form.action);
                    xhr.setRequestHeader("X-CSRF-TOKEN", form._token.value);
                    xhr.setRequestHeader("Content-Type", "application/json");
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.onload = () => {
                        let messageContaner = document.querySelector('#message');
                        let res = JSON.parse(xhr.responseText);
                        const errorsArray = [];
                        if (xhr.status == 200) {
                            messageContaner.innerHTML = `<div class="alert alert-success">${res.message}</div>`
                            form.reset();
                        } else {
                            className = "alert-danger";
                            Object.keys(res.errors).forEach(key => {
                                res.errors[key].forEach(error => {
                                    errorsArray.push(error);

                                });
                            });
                            let htmlErrors = "<ul>";
                            errorsArray.forEach(item => {
                                htmlErrors += `<li>${item}</li>`;
                            });
                            htmlErrors += "</ul>";
                            messageContaner.innerHTML = `<div class="alert alert-danger">${htmlErrors}</div>`
                        }

                    }
                    xhr.send(jsonString);


                });
            </script> --}}


    </div>
    </div>
@endsection
