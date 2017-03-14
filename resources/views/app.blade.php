<!DOCTYPE html>
<html>

@include('partials.htmlheader')

<body class="skin-blue sidebar-mini">
<div id="notifications"></div>
<div class="wrapper">

    @include('partials.mainheader')

    @include('partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('main-content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('partials.footer')

</div><!-- ./wrapper -->

@include('partials.scripts')

</body>
</html>