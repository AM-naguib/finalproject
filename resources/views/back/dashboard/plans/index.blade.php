@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <div class="dropdown py-3">
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
                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.plans.create') }}">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                      </svg>
                    Add Plan
                </a>

            </div>
        </div>
        <h1>All Plans</h1>

    </div>
    <div class="row justify-content-lg-center">
        <div id="message" class="col-12 col-lg-4 text-center">

        </div>
        <div class="col-12 mb-4">
            <div class="card">
                <div class="table-responsive py-4">
                    <table class="table table-flush" id="datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Discription</th>
                                <th>price</th>
                                <th>Currency</th>
                                <th>Features</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($plans) > 0)
                                @foreach ($plans as $plan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ $plan->description }}</td>
                                        <td>{{ $plan->price }}</td>
                                        <td>{{ $plan->currency }}</td>
                                        <td>{{ $plan->features }}</td>
                                        <td class="d-flex gap-3">
                                            <a href="{{route("admin.plans.edit",$plan->id)}}" class="btn btn-primary">Edit</a>
                                            <form action="{{route("admin.plans.destroy",$plan->id)}}" method="POST" id="forms">

                                                @csrf
                                                @method("delete")

                                            </form>
                                            <button class="btn btn-danger" id="formSubmit" type="submit">Delete</button>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = () => {
            let formSubmit = document.querySelectorAll('#formSubmit');
            let form = document.querySelectorAll('#forms');
            formSubmit.forEach((item,key) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    let formData = new FormData(form[key]);
                    let jsonData = {};
                    formData.forEach((value, key) => {
                        jsonData[key] = value;
                    })
                    let jsonString = JSON.stringify(jsonData);
                    let xhr = new XMLHttpRequest();
                    xhr.open("delete", form[key].action);
                    xhr.setRequestHeader("X-CSRF-TOKEN", form[key]._token.value);
                    xhr.setRequestHeader("Content-Type", "application/json");
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.send(jsonString);
                    xhr.onload = () => {
                        if (xhr.status == 200) {
                            item.parentElement.parentElement.remove();
                            let messageContaner = document.querySelector('#message');
                            let res = JSON.parse(xhr.responseText);
                            messageContaner.innerHTML = `<div class="alert alert-success">${res.message}</div>`

                        }
                    }
                })

            })

        }

    </script>
@endsection
