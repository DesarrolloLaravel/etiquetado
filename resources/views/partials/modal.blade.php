<div class="modal fade" id="@yield('modal-id','modal')" tabindex="-1" role="dialog">
    <div class="modal-dialog @yield('modal-size','modal-lg')" role="document" style="background-color: #FFFFFF;">
        <div class="modal-header" style="background-color: #3c8dbc; color: white;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="color: white;">&times;</span>
            </button>
            <h4 class="modal-title">@yield('modal-title', 'title')</h4>
        </div>

        @yield('modal-body')

        @yield('modal-footer')
    </div>
</div><!-- /.posicion modal -->