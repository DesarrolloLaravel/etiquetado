<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        @yield('contentheader_title', 'Page Header here')
        <small>@yield('contentheader_description')</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Inicio </a></li>
        @if(!isActiveRoute('admin/home'))
        	<li class="active"> {{ ucfirst(\Request::segment(2)) }} </li>
        @endif
    </ol>
</section>