@extends('layouts/layout')
@section('script-head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.css">
    <link href="{{ asset('../resources/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .was-validated .custom-select:invalid+.select2 .select2-selection {
            border-color: var(--bs-form-invalid-border-color) !important;
        }

        .was-validated .custom-select:valid+.select2 .select2-selection {
            border-color: var(--bs-form-valid-border-color) !important;
        }

        *:focus {
            outline: 0px;
        }

        .select2-selection__choice {
            color: #000000 !important;
        }
    </style>
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
                <h3>Visualizando palestrante</h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <form method="POST" action="{{ route('atualizar_palestrante', $speaker->id) }}" id="contactForm"
                    class="needs-validation position-relative" novalidate>
                    @method('PUT')
                    @csrf
                    <small class="position-absolute top-0 end-0">Adicionado em {{ $speaker->created_at->format('d/m/Y') }},
                        às {{ $speaker->created_at->format('H:i') }}.</small>
                    <h5 class="text-center mb-5">Informações gerais</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3" id="altura">
                                <input class="form-control border-2" name="nome" id="nome" type="text"
                                    placeholder="Nome" required value="{{ old('nome') ? old('nome') : $speaker->name }}" />
                                <label for="nome">Nome</label>
                                <div class="invalid-feedback">Nome do palestrante é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select js-example-basic-multiple custom-select" name="temas[]"
                                    id="temas" aria-label="Tema" required multiple="multiple">
                                    @foreach ($themes as $theme)
                                        <option value="{{ $theme->id }}"
                                            {{ in_array($theme->id, old('temas', $speaker->themes->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $theme->theme }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="data_nascimento" id="data_nascimento"
                                    type="date" placeholder="Data de nascimento" required
                                    value="{{ old('data_nascimento') ? old('data_nascimento') : $speaker->birth_date->format('Y-m-d') }}" />
                                <label for="data_nascimento">Data de nascimento</label>
                                <div class="invalid-feedback">Data de nascimento é obrigatória.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cpf" id="cpf" type="text"
                                    placeholder="CPF" required value="{{ old('cpf') ? old('cpf') : $speaker->cpf }}" />
                                <label for="cpf">CPF</label>
                                <div class="invalid-feedback">CPF é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" required name="rg" id="rg" type="text"
                                    placeholder="RG" value="{{ old('rg') ? old('rg') : $speaker->rg }}" />
                                <label for="rg">RG</label>
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
                                    value="{{ old('cep') ? old('cep') : $speaker->cep }}" />
                                <label for="cep">CEP</label>
                                <div class="invalid-feedback">CEP é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="endereco" id="endereco" type="text"
                                    placeholder="Endereço" required
                                    value="{{ old('endereco') ? old('endereco') : $speaker->address }}" />
                                <label for="endereco">Endereço</label>
                                <div class="invalid-feedback">Endereço é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="bairro" id="bairro" type="text"
                                    placeholder="Bairro" required
                                    value="{{ old('bairro') ? old('bairro') : $speaker->neighborhood }}" />
                                <label for="bairro">Bairro</label>
                                <div class="invalid-feedback">Bairro é obrigatório.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="row">
                                <div class="col-lg-6 col-xxl-5">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input mt-3 bg-success" name="temNumero"
                                                id="temNumero" type="checkbox" name="temNumero"
                                                {{ $speaker->number != null ? 'checked' : null }} />
                                            <label class="form-check-label" for="temNumero">Tem <br>numero?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xxl-7 ps-lg-0">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="numero" id="numero"
                                            type="number" placeholder="Número" required
                                            value="{{ old('numero') ? old('numero') : $speaker->number }}" />
                                        <label for="numero">Número</label>
                                        <div class="invalid-feedback">Número é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xxl-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="complemento" id="complemento" type="text"
                                    placeholder="Complemento"
                                    value="{{ old('complemento') ? old('complemento') : $speaker->complement }}" />
                                <label for="complemento">Complemento</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cidade" id="cidade" type="text"
                                    placeholder="Cidade" required
                                    value="{{ old('cidade') ? old('cidade') : $speaker->city }}" />
                                <label for="cidade">Cidade</label>
                                <div class="invalid-feedback">Cidade é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xxl-2">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="estado" id="estado" aria-label="Estado"
                                    required>
                                    <option {{ $speaker->state ? '' : 'selected' }} value="" disabled selected>
                                        Selecione</option>
                                    <option
                                        {{ old('estado') == 'AC' ? 'selected' : ($speaker->state == 'AC' ? 'selected' : '') }}
                                        value="AC">Acre</option>
                                    <option
                                        {{ old('estado') == 'AL' ? 'selected' : ($speaker->state == 'AL' ? 'selected' : '') }}
                                        value="AL">Alagoas
                                    </option>
                                    <option
                                        {{ old('estado') == 'AP' ? 'selected' : ($speaker->state == 'AP' ? 'selected' : '') }}
                                        value="AP">Amapá</option>
                                    <option
                                        {{ old('estado') == 'AM' ? 'selected' : ($speaker->state == 'AM' ? 'selected' : '') }}
                                        value="AM">Amazonas
                                    </option>
                                    <option
                                        {{ old('estado') == 'BA' ? 'selected' : ($speaker->state == 'BA' ? 'selected' : '') }}
                                        value="BA">Bahia</option>
                                    <option
                                        {{ old('estado') == 'CE' ? 'selected' : ($speaker->state == 'CE' ? 'selected' : '') }}
                                        value="CE">Ceará</option>
                                    <option
                                        {{ old('estado') == 'DF' ? 'selected' : ($speaker->state == 'DF' ? 'selected' : '') }}
                                        value="DF">Distrito
                                        Federal</option>
                                    <option
                                        {{ old('estado') == 'ES' ? 'selected' : ($speaker->state == 'ES' ? 'selected' : '') }}
                                        value="ES">Espírito Santo
                                    </option>
                                    <option
                                        {{ old('estado') == 'GO' ? 'selected' : ($speaker->state == 'GO' ? 'selected' : '') }}
                                        value="GO">Goiás</option>
                                    <option
                                        {{ old('estado') == 'MA' ? 'selected' : ($speaker->state == 'MA' ? 'selected' : '') }}
                                        value="MA">Maranhão
                                    </option>
                                    <option
                                        {{ old('estado') == 'MT' ? 'selected' : ($speaker->state == 'MT' ? 'selected' : '') }}
                                        value="MT">Mato Grosso
                                    </option>
                                    <option
                                        {{ old('estado') == 'MS' ? 'selected' : ($speaker->state == 'MS' ? 'selected' : '') }}
                                        value="MS">Mato Grosso do
                                        Sul</option>
                                    <option
                                        {{ old('estado') == 'MG' ? 'selected' : ($speaker->state == 'MG' ? 'selected' : '') }}
                                        value="MG">Minas Gerais
                                    </option>
                                    <option
                                        {{ old('estado') == 'PA' ? 'selected' : ($speaker->state == 'PA' ? 'selected' : '') }}
                                        value="PA">Pará</option>
                                    <option
                                        {{ old('estado') == 'PB' ? 'selected' : ($speaker->state == 'PB' ? 'selected' : '') }}
                                        value="PB">Paraíba
                                    </option>
                                    <option
                                        {{ old('estado') == 'PR' ? 'selected' : ($speaker->state == 'PR' ? 'selected' : '') }}
                                        value="PR">Paraná
                                    </option>
                                    <option
                                        {{ old('estado') == 'PE' ? 'selected' : ($speaker->state == 'PE' ? 'selected' : '') }}
                                        value="PE">Pernambuco
                                    </option>
                                    <option
                                        {{ old('estado') == 'PI' ? 'selected' : ($speaker->state == 'PI' ? 'selected' : '') }}
                                        value="PI">Piauí</option>
                                    <option
                                        {{ old('estado') == 'RJ' ? 'selected' : ($speaker->state == 'RJ' ? 'selected' : '') }}
                                        value="RJ">Rio de Janeiro
                                    </option>
                                    <option
                                        {{ old('estado') == 'RN' ? 'selected' : ($speaker->state == 'RN' ? 'selected' : '') }}
                                        value="RN">Rio Grande do
                                        Norte</option>
                                    <option
                                        {{ old('estado') == 'RS' ? 'selected' : ($speaker->state == 'RS' ? 'selected' : '') }}
                                        value="RS">Rio Grande do
                                        Sul</option>
                                    <option
                                        {{ old('estado') == 'RO' ? 'selected' : ($speaker->state == 'RO' ? 'selected' : '') }}
                                        value="RO">Rondônia
                                    </option>
                                    <option
                                        {{ old('estado') == 'RR' ? 'selected' : ($speaker->state == 'RR' ? 'selected' : '') }}
                                        value="RR">Roraima
                                    </option>
                                    <option
                                        {{ old('estado') == 'SC' ? 'selected' : ($speaker->state == 'SC' ? 'selected' : '') }}
                                        value="SC">Santa Catarina
                                    </option>
                                    <option
                                        {{ old('estado') == 'SP' ? 'selected' : ($speaker->state == 'SP' ? 'selected' : '') }}
                                        value="SP">São Paulo
                                    </option>
                                    <option
                                        {{ old('estado') == 'SE' ? 'selected' : ($speaker->state == 'SE' ? 'selected' : '') }}
                                        value="SE">Sergipe
                                    </option>
                                    <option
                                        {{ old('estado') == 'TO' ? 'selected' : ($speaker->state == 'TO' ? 'selected' : '') }}
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
                                    value="{{ old('eMailPrincipal') ? old('eMailPrincipal') : $speaker->email }}" />
                                <label for="eMailPrincipal">E-mail principal</label>
                                <div class="invalid-feedback">E-mail principal é obrigatório.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="celularPrincipal" id="celularPrincipal"
                                    type="text" placeholder="Celular principal" required
                                    value="{{ old('celularPrincipal') ? old('celularPrincipal') : $speaker->phone_number }}" />
                                <label for="celularPrincipal">Celular principal</label>
                                <div class="invalid-feedback">Celular principal é obrigatório.
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Observações</h5>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <textarea class="form-control border-2" name="observacao" id="observacao" type="text" placeholder="Observação"
                                    style="height: 10rem;">{{ old('observacao') ? old('observacao') : $speaker->observation }}</textarea>
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
                                    <option {{ $speaker->bank_id ? '' : 'selected' }} value="" disabled>
                                        Selecione um banco</option>
                                    @foreach ($banks as $bank)
                                        <option
                                            {{ old('banco') == $bank->id ? 'selected' : ($speaker->bank_id == $bank->id ? 'selected' : '') }}
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
                                    value="{{ old('agencia') ? old('agencia') : $speaker->agency }}" />
                                <label for="agencia">Agência</label>
                                <div class="invalid-feedback">Agência é obrigatória.</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="conta" id="conta" type="text"
                                    placeholder="Conta" required
                                    value="{{ old('conta') ? old('conta') : $speaker->account }}" />
                                <label for="conta">Conta</label>
                                <div class="invalid-feedback">Conta é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="chavePix" id="chavePix" type="text"
                                    placeholder="Chave Pix"
                                    value="{{ old('chavePix') ? old('chavePix') : $speaker->pix }}" />
                                <label for="chavePix">Chave Pix</label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col text-center">
                            <button class="btn btn-success btn-lg" disabled id="submitButton" type="submit">Atualizar
                                palestrante</button>
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
                        Tem certeza de que deseja excluir o palestrante {{ $speaker->name }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('deletar_palestrante', $speaker->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 mt-5">
                <a href="{{ route('meus_palestrantes') }}" class="btn btn-info text-decoration-none me-5">Ver todos os
                    palestrantes</a>
            </div>
            <div class="col-6 text-end mt-5">
                <button class="btn btn-outline-danger d-block ms-auto" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal">Excluir</button>
            </div>
        </div>
        @if ($speaker->events->count() > 0)
            <h5 class="text-center mt-4 pt-lg-5 mb-5">Eventos que participou</h5>
            <table id="table" class="table table-striped py-lg-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cliente</th>
                        <th>Palestrante(s)</th>
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
                    @foreach ($speaker->events as $event)
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
    </div>
@endsection

@section('script-body')
    <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        $(document).ready(function() {
            $(".js-example-basic-multiple").select2({
                placeholder: "Selecione um ou mais temas",
            });

            $('.select2-selection--multiple').addClass('form-control border-2 h-100')

            // 

            var alturaNome = $('#altura').height();
            $('.select2-container--default').css('height', alturaNome + 'px');

            // 

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

            $('#cpf').inputmask('999.999.999-99', {
                clearMaskOnLostFocus: true
            });

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
