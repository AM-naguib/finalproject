<!DOCTYPE html>
<html lang="en">

<head>
    @include('back.layouts.head')
</head>

<body>
    {{-- mobile nav --}}
    @include('back.layouts.mobile-nav')
    {{-- sidenav --}}
    @include('back.layouts.sidenav')

    <main class="content">

        {{-- nav --}}
        @include('back.layouts.nav')

        @yield('content')

        {{-- footer --}}
        @include('back.layouts.footer')

    </main>
    {{-- scripts --}}
    @include('back.layouts.scripts')
</body>

</html>
