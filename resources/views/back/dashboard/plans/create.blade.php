@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <h1>Create Plan</h1>
    </div>
    <div class="row justify-content-lg-center">
        @include('back.dashboard.inc.message')
        <div class="col-12 col-lg-4">
            <div id="message">
                <div class="alert">

                </div>
            </div>
            <form action="{{ route('admin.plans.store') }}" class="form" id="form" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Plan Name"
                        value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label for="Description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="Description" name="description"
                        placeholder="Enter Plan Description" value="{{ old('description') }}">
                </div>
                <div class="mb-3">
                    <label for="Price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="Price" name="price" placeholder="Enter Plan Price"
                        value="{{ old('price') }}">
                </div>
                <div class="mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <input type="text" class="form-control" id="currency" name="currency"
                        placeholder="Enter Plan Currency Ex: USD" value="{{ old('currency') }}">
                </div>
                <div class="mb-3">
                    <label for="Features" class="form-label">Features</label>
                    <textarea name="features" id="" cols="30" rows="10" class="form-control "
                        placeholder="Enter Features
Ex: Full Access,Facebook Poster">
{{ old('features') }}</textarea>
                </div>
                <button type="submit" class="btn form-control bg-success text-white">Create Plan</button>
            </form>

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
            </script>


        </div>
    </div>
@endsection
