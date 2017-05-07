<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <!-- Optionally, you can add icons to the links -->
            @if(\Auth::user()->users_role == "administracion")
                <li class="{{ isActiveRoute('admin/home') }}"><a href="{{ url('/admin/home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
                <!--<li class="{{ isActiveRoute('admin/recepcion') }}"><a href="{{ url('/admin/recepcion') }}"><i class='fa fa-sign-out'></i> <span>Recepci&oacute;n Materia Prima</span></a></li>-->
                <li class="treeview {{ areActiveRoutes(['admin/lote/create','admin/lote']) }}">
                    <a href="#"><i class='fa fa-folder'></i> <span>Lotes</span><i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('/admin/lote/create') }}"><i class='fa fa-circle-o'></i>Crear</a></li>
                        <li><a href="{{ url('/admin/lote') }}"><i class='fa fa-circle-o'></i>Listar</a></li>
                        <li><a href="{{ url('/admin/etiqueta_mp/create') }}"><i class='fa fa-circle-o'></i>Etiqueta Pallet</a></li>
                        <li><a href="{{ url('/admin/etiqueta_mp') }}"><i class='fa fa-circle-o'></i>Recepci&oacute;n Pallet</a></li>
                    </ul>
                </li>
                <li class="treeview {{ areActiveRoutes(['admin/ordenproduccion']) }}">
                    <a href="#"><i class='fa fa-gear '></i> <span>Ordenes</span><i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('/admin/ordenproduccion') }}"><i class='fa fa-circle-o'></i>Producci&oacute;n</a></li>
                         <li><a href="{{ url('/admin/ordentrabajo') }}"><i class='fa fa-circle-o'></i>Trabajo</a></li>
                    </ul>
                </li>
                <li class="treeview {{ areActiveRoutes(['admin/etiqueta/create','admin/etiqueta', 'admin/nordic/create', 'admin/etiqueta/all']) }}">
                    <a href="#"><i class='fa fa-list-alt'></i> <span>Etiqueta</span><i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('admin/etiqueta/create') }}"><i class='fa fa-circle-o'></i>Imprimir - Planta</a></li>
                        <li><a href="{{ url('admin/nordic/create') }}"><i class='fa fa-circle-o'></i>Imprimir - Nordic</a></li>
                        <li><a href="{{ url('admin/etiqueta') }}"><i class='fa fa-circle-o'></i>Monitor de Cajas</a></li>
                        <li><a href="{{ url('admin/etiqueta/all') }}"><i class='fa fa-circle-o'></i>Todas las Etiquetas</a></li>
                    </ul>
                </li>
                <li class="{{ isActiveRoute('admin/caja') }}"><a href="{{ url('/admin/caja') }}"><i class='fa fa-cubes'></i> <span>Stock</span></a></li>
                <li class="{{ isActiveRoute('admin/despacho') }}"><a href="{{ url('/admin/despacho') }}"><i class='fa fa-cubes'></i> <span>Despacho</span></a></li>
                <li class="treeview {{ areActiveRoutes(['admin/procesador','admin/elaborador','admin/producto','admin/formato','admin/user', 'admin/calibre', 'admin/calidad','admin/especie','admin/unidad_medida']) }}">
                    <a href="#"><i class='glyphicon glyphicon-tasks'></i> <span>Mantenedores</span><i class="fa fa-angle-right pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="{{ isActiveRoute('admin/user') }}"><a href="{{ url('/admin/user') }}"><i class='glyphicon glyphicon-user'></i> <span>Usuario</span></a></li>
                        <li class="{{ isActiveRoute('admin/procesador') }}"><a href="{{ url('/admin/procesador') }}"><i class='fa fa-wrench'></i> <span>Procesadora</span></a></li>
                        <li class="{{ isActiveRoute('admin/elaborador') }}"><a href="{{ url('/admin/elaborador') }}"><i class='fa fa-wrench'></i> <span>Elaborador</span></a></li>
                        <li class="{{ isActiveRoute('admin/productor') }}"><a href="{{ url('/admin/productor') }}"><i class='fa fa-wrench'></i> <span>Productor</span></a></li>
                        <li class="{{ isActiveRoute('admin/cliente') }}"><a href="{{ url('/admin/cliente') }}"><i class='fa fa-wrench'></i> <span>Cliente</span></a></li>
                        <li class="{{ isActiveRoute('admin/envase') }}"><a href="{{ url('/admin/envase') }}"><i class='fa fa-wrench'></i> <span>Envase</span></a></li>
                        <li class="{{ isActiveRoute('admin/envaseDos') }}"><a href="{{ url('/admin/envaseDos') }}"><i class='fa fa-wrench'></i> <span>Envase Dos</span></a></li>
                        <li class="{{ isActiveRoute('admin/producto') }}"><a href="{{ url('/admin/producto') }}"><i class='fa fa-cube'></i> <span>Producto</span></a></li>
                        <li class="{{ isActiveRoute('admin/unidad_medida') }}"><a href="{{ url('/admin/unidad_medida') }}"><i class='fa fa-cube'></i> <span>Unidad Medida</span></a></li>
                        <li class="{{ isActiveRoute('admin/calibre') }}"><a href="{{ url('/admin/calibre') }}"><i class='fa fa-cube'></i> <span>Calibre</span></a></li>
                        <li class="{{ isActiveRoute('admin/formato') }}"><a href="{{ url('/admin/formato') }}"><i class='fa fa-cube'></i> <span>Formato</span></a></li>
                        <li class="{{ isActiveRoute('admin/calidad') }}"><a href="{{ url('/admin/calidad') }}"><i class='fa fa-cube'></i> <span>Calidad</span></a></li>
                        <li class="{{ isActiveRoute('admin/especie') }}"><a href="{{ url('/admin/especie') }}"><i class='fa fa-cube'></i> <span>Especie</span></a></li>
                        <li class="{{ isActiveRoute('admin/variante') }}"><a href="{{ url('/admin/variante') }}"><i class='fa fa-cube'></i> <span>Variante</span></a></li>
                        <li class="{{ isActiveRoute('admin/varianteDos') }}"><a href="{{ url('/admin/varianteDos') }}"><i class='fa fa-cube'></i> <span>Variante Secundaria</span></a></li>
                        <li class="{{ isActiveRoute('admin/condicion') }}"><a href="{{ url('/admin/condicion') }}"><i class='fa fa-cube'></i> <span>Condición</span></a></li>
                        <li class="{{ isActiveRoute('admin/trim') }}"><a href="{{ url('/admin/trim') }}"><i class='fa fa-cube'></i> <span>Trim</span></a></li>
                    </ul>
                </li>    
            @elseif(\Auth::user()->users_role == "recepcion")
            <li class="{{ isActiveRoute('recepcion/home') }}"><a href="{{ url('/recepcion/home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
            <li class="treeview {{ areActiveRoutes(['recepcion/lote/create','recepcion/lote']) }}">
                <a href="#"><i class='fa fa-folder'></i> <span>Lotes</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/recepcion/lote/create') }}"><i class='fa fa-circle-o'></i>Crear</a></li>
                    <li><a href="{{ url('/recepcion/lote') }}"><i class='fa fa-circle-o'></i>Listar</a></li>
                </ul>
            </li>
            @elseif(\Auth::user()->users_role == "produccion")
            <li class="{{ isActiveRoute('produccion/home') }}"><a href="{{ url('/produccion/home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
            <li class="treeview {{ isActiveRoute('produccion/lote') }}">
                <a href="#"><i class='fa fa-folder'></i> <span>Lotes</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/produccion/lote') }}"><i class='fa fa-circle-o'></i>Listar</a></li>
                </ul>
            </li>
            <li class="treeview {{ areActiveRoutes(['produccion/ordenproduccion']) }}">
                <a href="#"><i class='fa fa-folder'></i> <span>Ordenes</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('/produccion/ordenproduccion') }}"><i class='fa fa-circle-o'></i>Producci&oacute;n</a></li>
                    <li><a href="#"><i class='fa fa-circle-o'></i>Traslado</a></li>
                </ul>
            </li>
            @elseif(\Auth::user()->users_role == "empaque")
            <li class="{{ isActiveRoute('empaque/home') }}"><a href="{{ url('/empaque/home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
            <li class="treeview {{ areActiveRoutes(['empaque/etiqueta/create']) }}">
                <a href="#"><i class='fa fa-list-alt'></i> <span>Etiqueta</span><i class="fa fa-angle-right pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('empaque/etiqueta/create') }}"><i class='fa fa-circle-o'></i>Imprimir</a></li>
                    <li><a href="{{ url('empaque/etiqueta') }}"><i class='fa fa-circle-o'></i>Listar</a></li>
                </ul>
            </li>
            @endif
            
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
