<!DOCTYPE html>
<html lang="en">

@include('partials.user.head')

<body>
    <!-- Loader -->
    <div class="content-wrapper">
        @include('partials.user.navbar')

        @yield('content')

    </div>
    @include('partials.user.footer')

    @include('partials.user.loading')

    @include('partials.user.script')
    
</body>

</html>