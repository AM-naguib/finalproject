@if (session('success'))
<div class="col-12 col-md-6 col-xl-4 mx-auto">
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
</div>
@endif

@if ($errors->any())
<div class="col-12 col-md-6 col-xl-4 mx-auto">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
