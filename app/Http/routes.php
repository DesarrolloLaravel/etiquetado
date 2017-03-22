<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Auth\AuthController@getLogin'); // Mostrar login
Route::post('/', 'Auth\AuthCustom@postLogin');
Route::get('logout', 'Auth\AuthCustom@getLogout');

Route::group(['prefix' => 'admin', 'middleware' => ['auth','profile:administracion'],'namespace' => 'Admin'], function(){

	Route::get('home', 'HomeController@home');
	Route::get('lote', 'LoteController@index');
	Route::get('lote/produccion','LoteController@lotes_produccion');
	Route::get('lote/next','LoteController@next');
	Route::post('lote/change','LoteController@lote_change');
	Route::get('lote/create','LoteController@create');
	Route::post('lote/store','LoteController@store');
	Route::post('lote/update','LoteController@update');
	Route::get('lote/show','LoteController@show');
	Route::get('lote/edit','LoteController@edit');
	Route::post('lote/delete','LoteController@destroy');

	Route::get('procesador','ProcesadorController@index');
	Route::get('procesador/edit','ProcesadorController@edit');
	Route::post('procesador/store','ProcesadorController@store');
	Route::post('procesador/update','ProcesadorController@update');
	Route::post('procesador/delete','ProcesadorController@delete');
    
    Route::get('elaborador','ElaboradorController@index');
    Route::get('elaborador/edit','ElaboradorController@edit');
    Route::post('elaborador/store','ElaboradorController@store');
    Route::post('elaborador/update','ElaboradorController@update');
    Route::post('elaborador/delete','ElaboradorController@delete');

    Route::get('envase','EnvaseController@index');
    Route::get('envase/edit','EnvaseController@edit');
    Route::post('envase/store','EnvaseController@store');
    Route::post('envase/update','EnvaseController@update');
    Route::post('envase/delete','EnvaseController@delete');

    Route::get('envaseDos','EnvaseDosController@index');
    Route::get('envaseDos/edit','EnvaseDosController@edit');
    Route::post('envaseDos/store','EnvaseDosController@store');
    Route::post('envaseDos/update','EnvaseDosController@update');
    Route::post('envaseDos/delete','EnvaseDosController@delete');

    Route::get('variante','VarianteController@index');
    Route::get('variante/edit','VarianteController@edit');
    Route::post('variante/store','VarianteController@store');
    Route::post('variante/update','VarianteController@update');
    Route::post('variante/delete','VarianteController@delete');

 	Route::get('productor','ProductorController@index');
    Route::get('productor/edit','ProductorController@edit');
    Route::post('productor/store','ProductorController@store');
    Route::post('productor/update','ProductorController@update');
    Route::post('productor/delete','ProductorController@delete');

    Route::get('cliente','ClienteController@index');
    Route::get('cliente/edit','ClienteController@edit');
    Route::post('cliente/store','ClienteController@store');
    Route::post('cliente/update','ClienteController@update');
    Route::post('cliente/delete','ClienteController@delete');

    Route::get('formato','FormatoController@index');
    Route::get('formato/edit','FormatoController@edit');
	Route::post('formato/store','FormatoController@store');
	Route::post('formato/update','FormatoController@update');
	Route::post('formato/delete','FormatoController@delete');

	Route::get('empresa','EmpresaController@index');
	Route::get('empresa/edit','EmpresaController@edit');
	Route::post('empresa/store','EmpresaController@store');
	Route::post('empresa/update','EmpresaController@update');
	Route::post('empresa/delete','EmpresaController@delete');

	Route::get('etiqueta','EtiquetaController@index');
    Route::get('etiqueta/all','EtiquetaController@indexAll');
	Route::get('etiqueta/print/{id}/{idioma}', 'EtiquetaController@print_etiqueta');
	Route::get('etiqueta/create', 'EtiquetaController@create');
	Route::post('etiqueta/store', 'EtiquetaController@store');
	Route::post('etiqueta/update', 'EtiquetaController@update');
	Route::post('etiqueta/destroy', 'EtiquetaController@destroy');
	Route::get('etiqueta/reprint','EtiquetaController@reprint');
	
	Route::get('ordenproduccion', 'OrdenProduccionController@index');
	Route::get('ordenproduccion/create', 'OrdenProduccionController@create');
	Route::get('ordenproduccion/show', 'OrdenProduccionController@show');
	Route::get('ordenproduccion/edit', 'OrdenProduccionController@edit');
	Route::post('ordenproduccion/store', 'OrdenProduccionController@store');
	Route::post('ordenproduccion/update', 'OrdenProduccionController@update');

	Route::get('producto', 'ProductoController@index');
	Route::post('producto/store', 'ProductoController@store');
	Route::get('producto/show', 'ProductoController@show');

	Route::get('caja','CajaController@index');
	Route::post('caja','CajaController@index');
	Route::get('caja/lote_product','CajaController@getCajasByLoteOfProduct');
	Route::get('caja/show','CajaController@show');
    Route::get('caja/export/{lote_id}','CajaController@exportTodayPacking');
    Route::get('caja/export/today/{lote_id}/{today}','CajaController@exportTodayPacking');
    Route::get('caja/export/history/{lote_id}','CajaController@exportHistoryPacking');

	Route::get('frigorifico','FrigorificoController@index');

	Route::get('camara','CamaraController@index');

	Route::get('posicion','PosicionController@index');

	Route::get('despacho','OrdenDespachoController@index');
	Route::get('despacho/create','OrdenDespachoController@create');
	Route::post('despacho/store','OrdenDespachoController@store');
	Route::get('despacho/next','OrdenDespachoController@next');
	Route::get('despacho/execute','OrdenDespachoController@execute');
	Route::get('despacho/show','OrdenDespachoController@show');
	Route::post('despacho/update','OrdenDespachoController@update');
	Route::post('despacho/discount','OrdenDespachoController@discount');
	Route::get('despacho/resume','OrdenDespachoController@resume');
	Route::get('despacho/export','OrdenDespachoController@exportPacking');
    Route::post('despacho/delete', 'OrdenDespachoController@destroy');

    Route::get('calibre','CalibreController@index');
    Route::post('calibre/store','CalibreController@store');
    Route::get('calibre/edit','CalibreController@edit');
    Route::post('calibre/update','CalibreController@update');
    Route::post('calibre/delete','CalibreController@destroy');

    Route::get('calidad','CalidadController@index');
    Route::post('calidad/store','CalidadController@store');
    Route::get('calidad/edit','CalidadController@edit');
    Route::post('calidad/update','CalidadController@update');
    Route::post('calidad/delete','CalidadController@destroy');

    Route::get('especie','EspecieController@index');
    Route::post('especie/store','EspecieController@store');
    Route::get('especie/edit','EspecieController@edit');
    Route::post('especie/update','EspecieController@update');
    Route::post('especie/delete','EspecieController@destroy');

    Route::get('unidad_medida','UnidadMedidaController@index');
    Route::post('unidad_medida/store','UnidadMedidaController@store');
    Route::get('unidad_medida/edit','UnidadMedidaController@edit');
    Route::post('unidad_medida/update','UnidadMedidaController@update');
    Route::post('unidad_medida/delete','UnidadMedidaController@destroy');

    Route::get('nordic','NordicController@index');
    Route::get('nordic/print/{orden_id}/{producto_id}/{fecha}', 'NordicController@print_etiqueta');
    Route::get('nordic/create', 'NordicController@create');
    Route::post('nordic/store', 'NordicController@store');
    Route::post('nordic/update', 'NordicController@update');
    Route::post('nordic/destroy', 'NordicController@destroy');
    Route::get('nordic/reprint','NordicController@reprint');

	Route::resource('lote', 'LoteController');

});

Route::group(['prefix' => 'recepcion', 'middleware' => ['auth','profile:recepcion'], 'namespace' => 'Recepcion'], function(){

	Route::get('home', 'HomeController@home');
	Route::get('lote', 'LoteController@index');
	Route::get('lote/produccion','LoteController@lotes_produccion');
	Route::get('lote/next','LoteController@next');
	Route::post('lote/change','LoteController@lote_change');
	Route::get('lote/create','LoteController@create');
	Route::post('lote/store','LoteController@store');
	Route::get('lote/show','LoteController@show');
});

Route::group(['prefix' => 'produccion', 'middleware' => ['auth','profile:produccion'], 'namespace' => 'Produccion'], function(){

	Route::get('home', 'HomeController@home');
	Route::get('lote', 'LoteController@index');
	Route::get('lote/show','LoteController@show');

	Route::get('ordenproduccion', 'OrdenProduccionController@index');
	Route::get('ordenproduccion/create', 'OrdenProduccionController@create');
	Route::get('ordenproduccion/show', 'OrdenProduccionController@show');
	Route::post('ordenproduccion/store', 'OrdenProduccionController@store');
});

Route::group(['prefix' => 'empaque', 'middleware' => ['auth','profile:empaque'], 'namespace' => 'Empaque'], function(){

	Route::get('home', 'HomeController@home');
	Route::get('etiqueta','EtiquetaController@index');
	Route::get('etiqueta/print/{id}', 'EtiquetaController@print_etiqueta');
	Route::get('etiqueta/create', 'EtiquetaController@create');
	Route::post('etiqueta/store', 'EtiquetaController@store');
	Route::post('etiqueta/update', 'EtiquetaController@update');
	Route::post('etiqueta/destroy', 'EtiquetaController@destroy');
	Route::get('etiqueta/reprint','EtiquetaController@reprint');

	Route::get('lote','LoteController@index');
	Route::get('lote/show','LoteController@show');

	Route::get('ordenproduccion', 'OrdenProduccionController@index');
	Route::get('ordenproduccion/show', 'OrdenProduccionController@show');

	Route::get('producto/show', 'ProductoController@show');
});

Route::group(['prefix' => 'bodega', 'middleware' => ['auth','profile:almacenamiento'], 'namespace' => 'Almacenamiento'], function(){

	Route::get('home', 'HomeController@home');
	Route::get('lote', 'LoteController@index');
	Route::get('lote/produccion','LoteController@lotes_produccion');
	Route::get('lote/next','LoteController@next');
	Route::post('lote/change','LoteController@lote_change');
	Route::get('lote/create','LoteController@create');
	Route::post('lote/store','LoteController@store');
	Route::get('lote/show','LoteController@show');
});