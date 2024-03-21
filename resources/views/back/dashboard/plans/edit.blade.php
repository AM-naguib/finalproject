@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <h1>Edit Plan</h1>
    </div>
    <div class="row justify-content-lg-center">
        <div class="col-12 col-lg-4">
            <form action="{{ route('admin.plans.update',$plan->id) }}" class="form" method="post">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Plan Name" value="{{$plan->name}}">
                </div>
                <div class="mb-3">
                    <label for="Description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="Description" name="description"
                        placeholder="Enter Plan Description" value="{{$plan->description}}">
                </div>
                <div class="mb-3">
                    <label for="Price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="Price" name="price" placeholder="Enter Plan Price" value="{{$plan->price}}">
                </div>
                <div class="mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <input type="text" class="form-control" id="currency" name="currency"
                        placeholder="Enter Plan Currency Ex: USD" value="{{$plan->currency}}">
                </div>
                <div class="mb-3">
                    <label for="Features" class="form-label">Price</label>
                    <textarea name="features" id="" cols="30" rows="10" class="form-control "
                        placeholder="Enter Features
Ex: Full Access,Facebook Poster">{{$plan->features}}
</textarea>
                </div>
                <button class="btn form-control bg-success text-white" type="submit">Edit Plan</button>
            </form>
        </div>
    </div>

@endsection
