@extends('layouts/layout')
@section('script-head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.css">
@endsection
@section('content')
    <div class="container px-lg-5 mt-lg-3 mb-5">
        @if (session()->get('success'))
            @php
                $message = session()->get('success');
                $message_success = true;
            @endphp
        @elseif(session()->get('error'))
            @php
                $message = session()->get('error');
                $message_error = true;
            @endphp
        @endif
        @if ($errors->any())
            <div class="row">
                <div class="col">
                    <div class="alert mb-4 mb-lg-3 alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        @if (isset($message_success) || isset($message_error))
            <div class="row">
                <div class="col">
                    <div class="alert mb-4 mb-lg-3 {{ isset($message_success) ? 'alert-success' : 'alert-danger' }}">
                        {{ $message }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row mb-4 mb-lg-5 mt-lg-3">
            <div class="col text-center">
                <h3>Visualizando parceiro</h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <form method="POST" action="{{ route('atualizar_parceiro', $partner->id) }}" id="contactForm"
                    class="needs-validation position-relative" novalidate>
                    @method('PUT')
                    @csrf
                    <small class="position-absolute top-0 end-0">Adicionado em {{ $partner->created_at->format('d/m/Y') }},
                        às {{ $partner->created_at->format('H:i') }}.</small>
                    <h5 class="text-center mb-5">Informações gerais</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="nome" id="nome" type="text"
                                    placeholder="Nome" required value="{{ old('nome') ? old('nome') : $partner->name }}" />
                                <label for="nome">Nome</label>
                                <div class="invalid-feedback">Nome do parceiro é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="razaoSocial" id="razaoSocial" type="text"
                                    placeholder="Razão social"
                                    value="{{ old('razaoSocial') ? old('razaoSocial') : $partner->corporate_reason }}" />
                                <label for="razaoSocial">Razão social</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cnpj" id="cnpj" type="text"
                                    placeholder="CNPJ" required value="{{ old('cnpj') ? old('cnpj') : $partner->cnpj }}" />
                                <label for="cnpj">CNPJ</label>
                                <div class="invalid-feedback">CNPJ é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="estruturaLegalDaOrganizacao"
                                    id="estruturaLegalDaOrganizacao" aria-label="Estrutura legal da organização">
                                    <option value="" disabled>Selecione</option>
                                    <option
                                        {{ old('estruturaLegalDaOrganizacao') == 1 ? 'selected' : ($partner->organization_type_id == 1 ? 'selected' : '') }}
                                        value="1">Microempreendedor Individual (MEI)</option>
                                    <option
                                        {{ old('estruturaLegalDaOrganizacao') == 2 ? 'selected' : ($partner->organization_type_id == 2 ? 'selected' : '') }}
                                        value="2">Microempresa (ME)</option>
                                    <option
                                        {{ old('estruturaLegalDaOrganizacao') == 3 ? 'selected' : ($partner->organization_type_id == 3 ? 'selected' : '') }}
                                        value="3">Empresa de Pequeno Porte (EPP)</option>
                                    <option
                                        {{ old('estruturaLegalDaOrganizacao') == 4 ? 'selected' : ($partner->organization_type_id == 4 ? 'selected' : '') }}
                                        value="4">Média empresa</option>
                                    <option
                                        {{ old('estruturaLegalDaOrganizacao') == 5 ? 'selected' : ($partner->organization_type_id == 5 ? 'selected' : '') }}
                                        value="5">Grande empresa</option>
                                </select>
                                <label for="estruturaLegalDaOrganizacao">Estrutura legal da organização</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="inscMunicipal" id="inscMunicipal" type="text"
                                    placeholder="Insc. Municipal"
                                    value="{{ old('inscMunicipal') ? old('inscMunicipal') : $partner->municipal_registration }}" />
                                <label for="inscMunicipal">Insc. Municipal</label>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Informações de endereço</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cep" id="cep" type="text"
                                    placeholder="CEP" maxlength="9" required
                                    value="{{ old('cep') ? old('cep') : $partner->cep }}" />
                                <label for="cep">CEP</label>
                                <div class="invalid-feedback">CEP é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="endereco" id="endereco" type="text"
                                    placeholder="Endereço" required
                                    value="{{ old('endereco') ? old('endereco') : $partner->address }}" />
                                <label for="endereco">Endereço</label>
                                <div class="invalid-feedback">Endereço é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="bairro" id="bairro" type="text"
                                    placeholder="Bairro" required
                                    value="{{ old('bairro') ? old('bairro') : $partner->neighborhood }}" />
                                <label for="bairro">Bairro</label>
                                <div class="invalid-feedback">Bairro é obrigatório.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="row">
                                <div class="col-lg-4 col-xxl-5">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input mt-3 bg-success" name="temNumero"
                                                id="temNumero" type="checkbox" name="temNumero"
                                                {{ $partner->number != null ? 'checked' : null }} />
                                            <label class="form-check-label" for="temNumero">Tem <br>numero?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xxl-7 ps-lg-0">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="numero" id="numero"
                                            type="number" placeholder="Número" {{ $partner->number ? 'required' : '' }}
                                            value="{{ old('numero') ? old('numero') : $partner->number }}" />
                                        <label for="numero">Número</label>
                                        <div class="invalid-feedback">Número é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="complemento" id="complemento" type="text"
                                    placeholder="Complemento"
                                    value="{{ old('complemento') ? old('complemento') : $partner->complement }}" />
                                <label for="complemento">Complemento</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cidade" id="cidade" type="text"
                                    placeholder="Cidade" required
                                    value="{{ old('cidade') ? old('cidade') : $partner->city }}" />
                                <label for="cidade">Cidade</label>
                                <div class="invalid-feedback">Cidade é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="estado" id="estado" aria-label="Estado"
                                    required>
                                    <option {{ $partner->state ? '' : 'selected' }} value="" disabled selected>
                                        Selecione</option>
                                    <option
                                        {{ old('estado') == 'AC' ? 'selected' : ($partner->state == 'AC' ? 'selected' : '') }}
                                        value="AC">Acre</option>
                                    <option
                                        {{ old('estado') == 'AL' ? 'selected' : ($partner->state == 'AL' ? 'selected' : '') }}
                                        value="AL">Alagoas
                                    </option>
                                    <option
                                        {{ old('estado') == 'AP' ? 'selected' : ($partner->state == 'AP' ? 'selected' : '') }}
                                        value="AP">Amapá</option>
                                    <option
                                        {{ old('estado') == 'AM' ? 'selected' : ($partner->state == 'AM' ? 'selected' : '') }}
                                        value="AM">Amazonas
                                    </option>
                                    <option
                                        {{ old('estado') == 'BA' ? 'selected' : ($partner->state == 'BA' ? 'selected' : '') }}
                                        value="BA">Bahia</option>
                                    <option
                                        {{ old('estado') == 'CE' ? 'selected' : ($partner->state == 'CE' ? 'selected' : '') }}
                                        value="CE">Ceará</option>
                                    <option
                                        {{ old('estado') == 'DF' ? 'selected' : ($partner->state == 'DF' ? 'selected' : '') }}
                                        value="DF">Distrito
                                        Federal</option>
                                    <option
                                        {{ old('estado') == 'ES' ? 'selected' : ($partner->state == 'ES' ? 'selected' : '') }}
                                        value="ES">Espírito Santo
                                    </option>
                                    <option
                                        {{ old('estado') == 'GO' ? 'selected' : ($partner->state == 'GO' ? 'selected' : '') }}
                                        value="GO">Goiás</option>
                                    <option
                                        {{ old('estado') == 'MA' ? 'selected' : ($partner->state == 'MA' ? 'selected' : '') }}
                                        value="MA">Maranhão
                                    </option>
                                    <option
                                        {{ old('estado') == 'MT' ? 'selected' : ($partner->state == 'MT' ? 'selected' : '') }}
                                        value="MT">Mato Grosso
                                    </option>
                                    <option
                                        {{ old('estado') == 'MS' ? 'selected' : ($partner->state == 'MS' ? 'selected' : '') }}
                                        value="MS">Mato Grosso do
                                        Sul</option>
                                    <option
                                        {{ old('estado') == 'MG' ? 'selected' : ($partner->state == 'MG' ? 'selected' : '') }}
                                        value="MG">Minas Gerais
                                    </option>
                                    <option
                                        {{ old('estado') == 'PA' ? 'selected' : ($partner->state == 'PA' ? 'selected' : '') }}
                                        value="PA">Pará</option>
                                    <option
                                        {{ old('estado') == 'PB' ? 'selected' : ($partner->state == 'PB' ? 'selected' : '') }}
                                        value="PB">Paraíba
                                    </option>
                                    <option
                                        {{ old('estado') == 'PR' ? 'selected' : ($partner->state == 'PR' ? 'selected' : '') }}
                                        value="PR">Paraná
                                    </option>
                                    <option
                                        {{ old('estado') == 'PE' ? 'selected' : ($partner->state == 'PE' ? 'selected' : '') }}
                                        value="PE">Pernambuco
                                    </option>
                                    <option
                                        {{ old('estado') == 'PI' ? 'selected' : ($partner->state == 'PI' ? 'selected' : '') }}
                                        value="PI">Piauí</option>
                                    <option
                                        {{ old('estado') == 'RJ' ? 'selected' : ($partner->state == 'RJ' ? 'selected' : '') }}
                                        value="RJ">Rio de Janeiro
                                    </option>
                                    <option
                                        {{ old('estado') == 'RN' ? 'selected' : ($partner->state == 'RN' ? 'selected' : '') }}
                                        value="RN">Rio Grande do
                                        Norte</option>
                                    <option
                                        {{ old('estado') == 'RS' ? 'selected' : ($partner->state == 'RS' ? 'selected' : '') }}
                                        value="RS">Rio Grande do
                                        Sul</option>
                                    <option
                                        {{ old('estado') == 'RO' ? 'selected' : ($partner->state == 'RO' ? 'selected' : '') }}
                                        value="RO">Rondônia
                                    </option>
                                    <option
                                        {{ old('estado') == 'RR' ? 'selected' : ($partner->state == 'RR' ? 'selected' : '') }}
                                        value="RR">Roraima
                                    </option>
                                    <option
                                        {{ old('estado') == 'SC' ? 'selected' : ($partner->state == 'SC' ? 'selected' : '') }}
                                        value="SC">Santa Catarina
                                    </option>
                                    <option
                                        {{ old('estado') == 'SP' ? 'selected' : ($partner->state == 'SP' ? 'selected' : '') }}
                                        value="SP">São Paulo
                                    </option>
                                    <option
                                        {{ old('estado') == 'SE' ? 'selected' : ($partner->state == 'SE' ? 'selected' : '') }}
                                        value="SE">Sergipe
                                    </option>
                                    <option
                                        {{ old('estado') == 'TO' ? 'selected' : ($partner->state == 'TO' ? 'selected' : '') }}
                                        value="TO">Tocantins
                                    </option>
                                </select>
                                <label for="estado">Estado</label>
                                <div class="invalid-feedback">Estado é obrigatório.</div>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Informações de contato</h5>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="eMailPrincipal" id="eMailPrincipal"
                                    type="email" placeholder="E-mail principal" required
                                    value="{{ old('eMailPrincipal') ? old('eMailPrincipal') : $partner->email }}" />
                                <label for="eMailPrincipal">E-mail principal</label>
                                <div class="invalid-feedback">E-mail principal é obrigatório.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="telefonePrincipal" id="telefonePrincipal"
                                    type="text" placeholder="Telefone principal" required
                                    value="{{ old('telefonePrincipal') ? old('telefonePrincipal') : $partner->phone }}" />
                                <label for="telefonePrincipal">Telefone principal</label>
                                <div class="invalid-feedback">Telefone principal é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="celularPrincipal" id="celularPrincipal"
                                    type="text" placeholder="Celular principal" required
                                    value="{{ old('celularPrincipal') ? old('celularPrincipal') : $partner->phone_number }}" />
                                <label for="celularPrincipal">Celular principal</label>
                                <div class="invalid-feedback">Celular principal é obrigatório.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="responsavelPeloPagamento"
                                    id="responsavelPeloPagamento" type="text" placeholder="Responsável pelo pagamento"
                                    value="{{ old('responsavelPeloPagamento') ? old('responsavelPeloPagamento') : $partner->responsible->full_name }}" />
                                <label for="responsavelPeloPagamento">Responsável pelo pagamento</label>
                                <div class="invalid-feedback">Responsável pelo pagamento é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="telefoneDoResponsavel"
                                    id="telefoneDoResponsavel" type="text" placeholder="Telefone do responsável"
                                    required
                                    value="{{ old('telefoneDoResponsavel') ? old('telefoneDoResponsavel') : $partner->responsible->phone }}" />
                                <label for="telefoneDoResponsavel">Telefone do responsável</label>
                                <div class="invalid-feedback">Telefone do responsável é obrigatório.</div>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Observações</h5>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <textarea class="form-control border-2" name="observacao" id="observacao" type="text" placeholder="Observação"
                                    style="height: 10rem;">{{ old('observacao') ? old('observacao') : $partner->observation }}</textarea>
                                <label for="observacao">Observação</label>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Informações financeiras</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="banco" id="banco" aria-label="Banco"
                                    required>
                                    <option {{ $partner->bank_id ? '' : 'selected' }} value="" disabled>
                                        Selecione um banco</option>
                                    @foreach ($banks as $bank)
                                        <option
                                            {{ old('banco') == $bank->id ? 'selected' : ($partner->bank_id == $bank->id ? 'selected' : '') }}
                                            value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                <label for="banco">Banco</label>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="agencia" id="agencia" type="text"
                                    placeholder="Agência" required
                                    value="{{ old('agencia') ? old('agencia') : $partner->agency }}" />
                                <label for="agencia">Agência</label>
                                <div class="invalid-feedback">Agência é obrigatória.</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="conta" id="conta" type="text"
                                    placeholder="Conta" required
                                    value="{{ old('conta') ? old('conta') : $partner->account }}" />
                                <label for="conta">Conta</label>
                                <div class="invalid-feedback">Conta é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="chavePix" id="chavePix" type="text"
                                    placeholder="Chave Pix"
                                    value="{{ old('chavePix') ? old('chavePix') : $partner->pix }}" />
                                <label for="chavePix">Chave Pix</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col text-center">
                            <button class="btn btn-success btn-lg" id="submitButton" type="submit">Atualizar
                                parceiro</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        Tem certeza de que deseja excluir o parceiro {{ $partner->name }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('deletar_parceiro', $partner->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if ($partner->events->count() > 0)
            <h5 class="text-center mt-4 pt-lg-5 mb-5">Eventos que participou</h5>
            <table id="table" class="table table-striped py-lg-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cliente</th>
                        <th>Palestrantes</th>
                        <th>Data do evento</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $status_numbers = [
                            1 => [
                                'name' => 'Em aberto',
                                'color' => 'white',
                            ],
                            2 => [
                                'name' => 'Iniciado',
                                'color' => 'blue',
                            ],
                            3 => [
                                'name' => 'Finalizado',
                                'color' => 'green',
                            ],
                            4 => [
                                'name' => 'Cancelado',
                                'color' => 'red',
                            ],
                        ];
                    @endphp
                    @foreach ($partner->events as $event)
                        <tr data-href="{{ route('visualizar_evento', $event->id) }}" style="cursor: pointer">
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->customer->name }}</td>
                            @if ($event->speakers->count() > 0 && $event->speakers->count() < 2)
                                <td>{{ $event->speakers->first()->name }}</td>
                            @elseif($event->speakers->count() > 1)
                                <td>{{ $event->speakers->first()->name }} e +
                                    {{ $event->speakers->count() - 1 }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                            @if ($event->status == 1)
                                @php
                                    $color_text = 'black';
                                @endphp
                            @else
                                @php
                                    $color_text = 'white';
                                @endphp
                            @endif
                            <td>{{ date('d/m/Y \à\s H\hi', strtotime($event->start_date_time)) }}</td>
                            <td>
                                <span class="badge rounded-pill"
                                    style="min-width: 30px; padding: 6px 20px; background-color: {{ $status_numbers[$event->status]['color'] }}; color: {{ $color_text }};">{{ $status_numbers[$event->status]['name'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="row">
            <div class="col-6 mt-5">
                <a href="{{ route('meus_parceiros') }}" class="btn btn-info text-decoration-none me-5">Ver todos os
                    parceiros</a>
            </div>
            <div class="col-6 text-end mt-5">
                <button class="btn btn-outline-danger d-block ms-auto" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal">Excluir</button>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
    <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script>
        $('#table').DataTable({
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "Mostrar _MENU_ registros por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sSearch": "Pesquisar:",
                "sZeroRecords": "Nenhum registro correspondente encontrado",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Próximo",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": ativar para classificar a coluna em ordem crescente",
                    "sSortDescending": ": ativar para classificar a coluna em ordem decrescente"
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tr[data-href]');
            rows.forEach(row => {
                row.addEventListener('dblclick', () => {
                    window.location.href = row.getAttribute('data-href');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (() => {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                const forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
            })()

            // 

            $('#cnpj').inputmask('99.999.999/9999-99', {
                clearMaskOnLostFocus: true
            });

            $('#telefonePrincipal').inputmask('(99) 9999-9999', {
                clearMaskOnLostFocus: true
            });

            $('#telefoneDoResponsavel').inputmask([
                "(99) 9999-9999",
                "(99) 9 9999-9999"
            ], {
                clearMaskOnLostFocus: true
            });

            $('#celularPrincipal').inputmask('(99) 9 9999-9999', {
                clearMaskOnLostFocus: true
            });

            // 

            function atualizarNumero() {
                const temNumero = $('#temNumero').is(':checked');
                const numeroInput = $('#numero');

                if (!temNumero) {
                    numeroInput.val('');
                    numeroInput.prop('disabled', true);
                    numeroInput.prop('required', false);
                } else {
                    numeroInput.prop('disabled', false);
                    numeroInput.prop('required', true);
                }
            }

            atualizarNumero();

            $('#temNumero').change(function() {
                atualizarNumero();
            });

            // 



            function limpa_formulário_cep() {
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#estado").val("");
            }


            $("#cep").blur(function() {
                var cep = $(this).val().replace(/\D/g, '');

                if (cep != "") {
                    var validacep = /^[0-9]{8}$/;

                    if (validacep.test(cep)) {

                        $("#endereco").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#estado").val("...");

                        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#estado").val(dados.uf);
                            } else {
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } else {
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } else {
                    limpa_formulário_cep();
                }
            });
        });
    </script>
@endsection
