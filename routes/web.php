<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth()->check()) {
        $name_user = Auth()->user()->name;
        return view('start.start', ['name_user' => $name_user]);
    } else {
        return view('auth.login');
    }
})->name('dashboard');

Route::get('/clientes', 'CustomerController@index')->name('meus_clientes')->middleware('checkPermission:meus_clientes');
Route::get('/adicionar-cliente', 'CustomerController@create')->name('novo_cliente')->middleware('checkPermission:novo_cliente');
Route::post('/salvar-cliente', 'CustomerController@store')->name('adicionar_cliente')->middleware('checkPermission:adicionar_cliente');
Route::get('/visualizar-cliente/{id}', 'CustomerController@show')->name('visualizar_cliente')->middleware('checkPermission:visualizar_cliente');
Route::put('/atualizar-cliente/{customer}', 'CustomerController@update')->name('atualizar_cliente')->middleware('checkPermission:atualizar_cliente');
Route::delete('/deletar-cliente/{customer}', 'CustomerController@destroy')->name('deletar_cliente')->middleware('checkPermission:deletar_cliente');

Route::get('/fornecedores', 'SupplierController@index')->name('meus_fornecedores')->middleware('checkPermission:meus_fornecedores');
Route::get('/adicionar-fornecedor', 'SupplierController@create')->name('novo_fornecedor')->middleware('checkPermission:novo_fornecedor');
Route::post('/salvar-fornecedor', 'SupplierController@store')->name('adicionar_fornecedor')->middleware('checkPermission:adicionar_fornecedor');
Route::get('/visualizar-fornecedor/{id}', 'SupplierController@show')->name('visualizar_fornecedor')->middleware('checkPermission:visualizar_fornecedor');
Route::put('/atualizar-fornecedor/{supplier}', 'SupplierController@update')->name('atualizar_fornecedor')->middleware('checkPermission:atualizar_fornecedor');
Route::delete('/deletar-fornecedor/{supplier}', 'SupplierController@destroy')->name('deletar_fornecedor')->middleware('checkPermission:deletar_fornecedor');
Route::get('/categorias-fornecedores', 'SupplierController@categories')->name('categorias')->middleware('checkPermission:categorias');
Route::post('/nova-categoria-fornecedor', 'SupplierController@newCategory')->name('adicionar_categoria')->middleware('checkPermission:adicionar_categoria');
Route::delete('/deletar-categoria-fornecedor/{categorySupplier}', 'SupplierController@deleteCategory')->name('deletar_categoria')->middleware('checkPermission:deletar_categoria');

Route::get('/parceiros', 'PartnerController@index')->name('meus_parceiros')->middleware('checkPermission:meus_parceiros');
Route::get('/adicionar-parceiro', 'PartnerController@create')->name('novo_parceiro')->middleware('checkPermission:novo_parceiro');
Route::post('/salvar-parceiro', 'PartnerController@store')->name('adicionar_parceiro')->middleware('checkPermission:adicionar_parceiro');
Route::get('/visualizar-parceiro/{id}', 'PartnerController@show')->name('visualizar_parceiro')->middleware('checkPermission:visualizar_parceiro');
Route::put('/atualizar-parceiro/{partner}', 'PartnerController@update')->name('atualizar_parceiro')->middleware('checkPermission:atualizar_parceiro');
Route::delete('/deletar-parceiro/{partner}', 'PartnerController@destroy')->name('deletar_parceiro')->middleware('checkPermission:deletar_parceiro');

Route::get('/palestrantes', 'SpeakerController@index')->name('meus_palestrantes')->middleware('checkPermission:meus_palestrantes');
Route::get('/adicionar-palestrante', 'SpeakerController@create')->name('novo_palestrante')->middleware('checkPermission:novo_palestrante');
Route::post('/salvar-palestrante', 'SpeakerController@store')->name('adicionar_palestrante')->middleware('checkPermission:adicionar_palestrante');
Route::get('/visualizar-palestrante/{id}', 'SpeakerController@show')->name('visualizar_palestrante')->middleware('checkPermission:visualizar_palestrante');
Route::put('/atualizar-palestrante/{speaker}', 'SpeakerController@update')->name('atualizar_palestrante')->middleware('checkPermission:atualizar_palestrante');
Route::delete('/deletar-palestrante/{speaker}', 'SpeakerController@destroy')->name('deletar_palestrante')->middleware('checkPermission:deletar_palestrante');

Route::get('/eventos', 'EventController@index')->name('meus_eventos')->middleware('checkPermission:meus_eventos');
Route::get('/adicionar-evento', 'EventController@create')->name('novo_evento')->middleware('checkPermission:novo_evento');
Route::post('/salvar-evento', 'EventController@store')->name('adicionar_evento')->middleware('checkPermission:adicionar_evento');
Route::get('/visualizar-evento/{id}', 'EventController@show')->name('visualizar_evento')->middleware('checkPermission:visualizar_evento');
Route::put('/atualizar-evento/{event}', 'EventController@update')->name('atualizar_evento')->middleware('checkPermission:atualizar_evento');
Route::post('/nova-nota-fiscal/{id}', 'FiscalNoteController@novaNotaFiscal')->name('nova_nota_fiscal')->middleware('checkPermission:nova_nota_fiscal');
Route::delete('/excluir-nota-fiscal/{id}', 'FiscalNoteController@excluirNotaFiscal')->name('excluir_nota_fiscal')->middleware('checkPermission:excluir_nota_fiscal');
Route::patch('/mudar-status-nota/{id}', 'FiscalNoteController@mudarStatus')->name('mudar_status_nota')->middleware('checkPermission:mudar_status_nota');

Route::match(['post', 'delete'], '/colaborador/{user}/{value}', 'UserController@feedback')->name('aceitar_recusar_usuario')->middleware('checkPermission:aceitar_recusar_usuario');

Route::get('/colaboradores', 'UserController@index')->name('meus_colaboradores')->middleware('checkPermission:meus_colaboradores');
Route::get('/colaboradores/pendentes', 'UserController@pending')->name('meus_colaboradores_pendentes')->middleware('checkPermission:meus_colaboradores_pendentes');
Route::get('/visualizar-colaborador/{user}', 'UserController@show')->name('visualizar_colaborador')->middleware('checkPermission:visualizar_colaborador');
Route::put('/atualizar-colaborador/{user}', 'UserController@update')->name('atualizar_colaborador')->middleware('checkPermission:atualizar_colaborador');
Route::delete('/deletar-colaborador/{user}', 'UserController@destroy')->name('deletar_colaborador')->middleware('checkPermission:deletar_colaborador');

Route::get('/financeiro', 'FinancialController@index')->name('meu_financeiro')->middleware('checkPermission:meu_financeiro');
Route::view('/atualizar-caixa', 'financial.financial-edit-converger-value')->name('atualizar_caixa')->middleware('checkPermission:atualizar_caixa');
Route::post('/novo-valor-caixa', 'FinancialController@updateValue')->name('salvar_novo_valor')->middleware('checkPermission:salvar_novo_valor');
Route::post('/nova-despesa', 'FinancialController@store')->name('salvar_nova_despesa')->middleware('checkPermission:salvar_nova_despesa');
Route::post('/atualizar-status-despesa/{financial}', 'FinancialController@updateStatus')->name('atualizar_status_despesa')->middleware('checkPermission:atualizar_status_despesa');
Route::delete('/deletar-despesa/{financial}', 'FinancialController@destroy')->name('deletar_despesa')->middleware('checkPermission:deletar_despesa');

Route::get('/estatisticas', 'StatisticsController@index')->name('estatisticas')->middleware('checkPermission:estatisticas');
Route::post('/sua-rota-para-calcular-soma', 'StatisticsController@calculateSum')->middleware('checkPermission:atualizar_colaborador');

Route::post('/email-cobranca/{id}', 'SendedEmailController@sendEmailNotificationToUser')->name('enviar_email_cobranca')->middleware('checkPermission:enviar_email_cobranca');