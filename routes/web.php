<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', ['as' => 'login', 'uses' => 'LoginController@index']);
Route::post('/login', ['as' => 'login.autenticar', 'uses' => 'LoginController@autenticar']);
Route::post('/login2', ['as' => 'login.autenticar2', 'uses' => 'LoginController@autenticar2']);

Route::get('/logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

//Webservice
Route::get('/wsproduto/{cnpj}/{produto}', ['as' => 'produto.ws', 'uses' => 'WSProdutoJsonController@listar']);
Route::get('/wslink/{produto}/{codigo}/{versao}', ['as' => 'link.ws', 'uses' => 'WSLinkJsonController@listar']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',         ['as' => 'index',    'uses' => 'IndexController@index']);
    Route::get('/ambiente', ['as' => 'ambiente', 'uses' => 'IndexController@ambiente']);
    Route::post('/ambiente',['as' => 'ambiente', 'uses' => 'IndexController@ambienteChange']);

    // Perfil
    Route::get('/perfil', ['as' => 'perfil', 'uses' => 'PerfilController@index']);
    Route::get('/perfil/cadastrar', ['as' => 'perfil.cadastrar', 'uses' => 'PerfilController@create']);
    Route::post('/perfil', ['as' => 'perfil.gravar', 'uses' => 'PerfilController@store']);
    Route::get('/perfil/{id}/editar', ['as' => 'perfil.editar', 'uses' => 'PerfilController@edit']);
    Route::get('/perfil/{id}/acesso', ['as' => 'perfil.acesso', 'uses' => 'PerfilController@access']);
    Route::put('/perfil/{id}', ['as' => 'perfil.atualizar', 'uses' => 'PerfilController@update']);
    Route::put('/perfil-acesso/{id}', ['as' => 'perfil.atualizar_acesso', 'uses' => 'PerfilController@access_update']);
    Route::delete('/perfil/{id}', ['as' => 'perfil.excluir', 'uses' => 'PerfilController@destroy']);

    // Acesso
    Route::get('/acesso', ['as' => 'acesso', 'uses' => 'AcessoController@index']);
    Route::get('/acesso/cadastrar', ['as' => 'acesso.cadastrar', 'uses' => 'AcessoController@create']);
    Route::post('/acesso', ['as' => 'acesso.gravar', 'uses' => 'AcessoController@store']);
    Route::get('/acesso/{id}/editar', ['as' => 'acesso.editar', 'uses' => 'AcessoController@edit']);
    Route::put('/acesso/{id}', ['as' => 'acesso.atualizar', 'uses' => 'AcessoController@update']);
    Route::delete('/acesso/{id}', ['as' => 'acesso.excluir', 'uses' => 'AcessoController@destroy']);

    // Usuário vs Perfil
    Route::match(['get','post'],'/perfilusuario', ['as' => 'perfilusuario', 'uses' => 'PerfilUsuarioController@index']);
    Route::get('/perfilusuario-cadastro/perfilusuariocadastrar', ['as' => 'perfilusuario.perfilusuariocadastrar', 'uses' => 'PerfilUsuarioController@create']);
    Route::post('/perfilusuario-gravar', ['as' => 'perfilusuario.gravar', 'uses' => 'PerfilUsuarioController@store']);
    Route::delete('/perfilusuario/{id}', ['as' => 'perfilusuario.excluir', 'uses' => 'PerfilUsuarioController@destroy']);

    //Relatório de Capacidade Agenda
    Route::match(['get','post'],'/relCA', ['as' => 'relCA', 'uses' => 'RelCAController@index']);

    //Relatório de OS
    Route::match(['get','post'],'/relCO', ['as' => 'relCO', 'uses' => 'RelCOController@index']);
    Route::post('/relCO-ajax-cliente', ['as' => 'relCO.ajax.buscarCliente', 'uses' => 'RelCOController@buscarAjaxCliente']);
    Route::post('/relCO-ajax-projeto', ['as' => 'relCO.ajax.buscarProjeto', 'uses' => 'RelCOController@buscarAjaxProjeto']);

    //Relatório Geral
    Route::match(['get','post'],'/relGeral', ['as' => 'relGeral', 'uses' => 'RelGeralController@index']);

    //Liberação de Produto
    Route::match(['get','post'],'/produto', ['as' => 'produto', 'uses' => 'ProdutoController@index']);
    Route::post('/produto-ajax-cliente', ['as' => 'produto.ajax.buscarCliente', 'uses' => 'ProdutoController@buscarAjaxCliente']);
    Route::get('/produto/liberar', ['as' => 'produto.liberar', 'uses' => 'ProdutoController@create']);
    Route::post('/produto_gravar', ['as' => 'produto.gravar', 'uses' => 'ProdutoController@store']);
    Route::get('/produto/{id}/editar', ['as' => 'produto.editar', 'uses' => 'ProdutoController@edit']);
    Route::put('/produto/{id}', ['as' => 'produto.atualizar', 'uses' => 'ProdutoController@update']);
    Route::delete('/produto/{id}', ['as' => 'produto.excluir', 'uses' => 'ProdutoController@destroy']);

    //Cadastro de Link
    Route::match(['get','post'],'/link', ['as' => 'link', 'uses' => 'LinkController@index']);
    Route::get('/link/cadastrar', ['as' => 'link.cadastrar', 'uses' => 'LinkController@create']);
    Route::post('/link_gravar', ['as' => 'link.gravar', 'uses' => 'LinkController@store']);
    Route::get('/link/{id}/editar', ['as' => 'link.editar', 'uses' => 'LinkController@edit']);
    Route::put('/link/{id}', ['as' => 'link.atualizar', 'uses' => 'LinkController@update']);
    Route::delete('/link/{id}', ['as' => 'link.excluir', 'uses' => 'LinkController@destroy']);

    //Relatório de Intercompany
    Route::match(['get','post'],'/relIntercompany', ['as' => 'relIntercompany', 'uses' => 'RelIntercompanyController@index']);

    Route::match(['get','post'],'/agenda',  ['as' => 'agenda',                  'uses' => 'AgendaController@index']);
    Route::post('/agenda/eventos-json',     ['as' => 'agenda.eventos-json',     'uses' => 'AgendaController@eventosJson']);
    Route::get('/evento/{id}',              ['as' => 'evento.visualizar',       'uses' => 'EventoController@show']);

    //Cadastro de Projeto
    Route::match(['get','post'],'/projeto', ['as' => 'projeto', 'uses' => 'ProjetoController@index']);
    Route::post('/projeto-ajax-cliente', ['as' => 'projeto.ajax.buscarCliente', 'uses' => 'ProjetoController@buscarAjaxCliente']);
    Route::post('/projeto-ajax-projeto', ['as' => 'projeto.ajax.buscarProjeto', 'uses' => 'ProjetoController@buscarAjaxProjeto']);
    Route::get('/projeto/{id}/acesso', ['as' => 'projeto.acesso', 'uses' => 'ProjetoController@access']);
    Route::post('/projetousuario-incluir', ['as' => 'projetousuario.incluir', 'uses' => 'ProjetoController@accessStore']);
    Route::delete('/projetousuario/{id}/{projeto}', ['as' => 'projetousuario.excluir', 'uses' => 'ProjetoController@accessDestroy']);

    // Tabela Padrão
    Route::get('/tabela_padrao', ['as' => 'tabela_padrao', 'uses' => 'TabelaPadraoController@index']);
    Route::get('/tabela_padrao/cadastrar', ['as' => 'tabela_padrao.cadastrar', 'uses' => 'TabelaPadraoController@create']);
    Route::post('/tabela_padrao', ['as' => 'tabela_padrao.gravar', 'uses' => 'TabelaPadraoController@store']);
    Route::get('/tabela_padrao/{id}/editar', ['as' => 'tabela_padrao.editar', 'uses' => 'TabelaPadraoController@edit']);
    Route::put('/tabela_padrao/{id}', ['as' => 'tabela_padrao.atualizar', 'uses' => 'TabelaPadraoController@update']);
    Route::delete('/tabela_padrao/{id}', ['as' => 'tabela_padrao.excluir', 'uses' => 'TabelaPadraoController@destroy']);

    // Agenda Corporativa
    Route::get('/agenda-corporativa',                                           ['as' => 'agenda-corporativa',                      'uses' => 'AgendaCorporativa\\ListarController@index']);
    Route::post('/agenda-corporativa/ajax/projeto',                             ['as' => 'agenda-corporativa.ajax.buscarProjeto',   'uses' => 'AgendaCorporativa\\ListarController@buscarAjaxProjeto']);
    Route::post('/agenda-corporativa',                                          ['as' => 'agenda-corporativa.pesquisar',            'uses' => 'AgendaCorporativa\\ListarController@pesquisar']);
    Route::get('/agenda-corporativa/evento/novo/{consultor_id}/data/{data}',    ['as' => 'agenda-corporativa.evento.novo',          'uses' => 'AgendaCorporativa\\EventoController@novo']);
    Route::get('/agenda-corporativa/evento/editar/{evendo_id}',                 ['as' => 'agenda-corporativa.evento.editar',        'uses' => 'AgendaCorporativa\\EventoController@editar']);
    Route::post('/agenda-corporativa/evento/gravar',                            ['as' => 'agenda-corporativa.evento',               'uses' => 'AgendaCorporativa\\EventoController@gravar']);
    Route::post('/agenda-corporativa/datatable',                                ['as' => 'agenda-corporativa.listar.datatable',     'uses' => 'AgendaCorporativa\\ListarController@datatable']);
    Route::delete('/agenda-corporativa/evento/{id}',                            ['as' => 'agenda-corporativa.excluir',              'uses' => 'AgendaCorporativa\\ExcluirController@index']);
});