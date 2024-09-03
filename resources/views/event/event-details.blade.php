    @extends('layouts/layout')
    @section('script-head')
        <link href="{{ asset('../resources/css/select2.min.css') }}" rel="stylesheet" />
        <style>
            .was-validated .custom-select:invalid+.select2 .select2-selection {
                border-color: var(--bs-form-invalid-border-color) !important;
            }

            .was-validated .custom-select:valid+.select2 .select2-selection {
                border-color: var(--bs-form-valid-border-color);
            }

            *:focus {
                outline: 0px;
            }

            .select2-search__field::placeholder {
                color: var(--bs-card-color);
                font-size: 1rem !important;
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
            <div class="row mb-3 mb-lg-5">
                <div class="col text-center">
                    <h3><strong>Visualizando evento</strong></h3>
                </div>
            </div>
            <div class="card pt-2 py-lg-4 px-2 border-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('atualizar_evento', $event->id) }}" id="contactForm"
                        class="needs-validation position-relative" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="position-absolute top-0 start-0 col-lg-2">
                            <div class="form-floating mb-3">
                                <select name="status" id="status" class="form-select border-2">
                                    <option value="1"
                                        {{ old('status') == 1 ? 'selected' : ($event->status == 1 ? 'selected' : 'selected') }}>
                                        Em aberto</option>
                                    <option value="2"
                                        {{ old('status') == 2 ? 'selected' : ($event->status == 2 ? 'selected' : '') }}>
                                        Iniciado
                                    </option>
                                    <option value="3"
                                        {{ old('status') == 3 ? 'selected' : ($event->status == 3 ? 'selected' : '') }}>
                                        Finalizado</option>
                                    <option value="4"
                                        {{ old('status') == 4 ? 'selected' : ($event->status == 4 ? 'selected' : '') }}>
                                        Cancelado</option>
                                </select>
                                <label for="status">Status do evento</label>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 text-end">
                            <small>Adicionado em {{ $event->created_at->format('d/m/Y') }},
                                às {{ $event->created_at->format('H:i') }}.</small>
                            <br>
                            <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="bi bi-filetype-pdf"></i> Gerenciar notas fiscais @if ($event->fiscalNote)
                                    <b>(1)</b>
                                @else
                                    <b>(0)</b>
                                @endif
                            </button>
                        </div>

                        <h5 class="text-center mb-5">Informações gerais</h5>
                        <div class="row pt-4">
                            <div class="col-lg-6">
                                <div class="form-floating mb-3" id="altura">
                                    <input class="form-control border-2" name="nome" id="nome" type="text"
                                        placeholder="Nome" required
                                        value="{{ old('nome') ? old('nome') : $event->name }}" />
                                    <label for="nome">Nome do evento</label>
                                    <div class="invalid-feedback">Nome do evento é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <div class="form-floating mb-3">
                                        <select class="form-select border-2" name="cliente" id="cliente"
                                            aria-label="Cliente" required>
                                            <option value="{{ $event->customer->id }}" selected>
                                                {{ $event->customer->name }}
                                            </option>
                                        </select>
                                        <label for="cliente">Cliente</label>
                                        <div class="invalid-feedback">Cliente é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select js-example-basic-multiple custom-select" name="temas[]"
                                        id="temas" aria-label="Tema" required multiple="multiple">
                                        @foreach ($eventThemes as $theme)
                                            <option value="{{ $theme->id }}"
                                                {{ in_array($theme->id, old('temas', $event->themes->pluck('id')->toArray())) ? 'selected' : ($event->themes->contains($theme->id) ? 'selected' : '') }}>
                                                {{ $theme->theme }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select js-example-basic-multiple-palestrante custom-select"
                                        name="palestrantes[]" id="palestrantes" aria-label="Tema" required
                                        multiple="multiple">
                                        @foreach ($speakers as $speaker)
                                            <option value="{{ $speaker->id }}"
                                                {{ in_array($speaker->id, old('palestrantes', [])) ? 'selected' : ($event->speakers->contains($speaker->id) ? 'selected' : '') }}>
                                                {{ $speaker->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="dataHora" id="dataHora"
                                        type="datetime-local" placeholder="Data e Hora do evento"
                                        value="{{ old('dataHora') ? old('dataHora') : $event->start_date_time }}" />
                                    <label for="dataHora">Data e hora do evento</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-floating mb-3">
                                    <select class="form-select border-2" name="tipoEvento" id="tipoEvento"
                                        aria-label="Tipo do evento" required>
                                        <option
                                            {{ old('tipoEvento') == 1 ? 'selected' : ($event->event_type_id == 1 ? 'selected' : '') }}
                                            value="1">Presencial
                                        </option>
                                        <option
                                            {{ old('tipoEvento') == 2 ? 'selected' : ($event->event_type_id == 2 ? 'selected' : '') }}
                                            value="2">Remoto
                                        </option>
                                        <option
                                            {{ old('tipoEvento') == 3 ? 'selected' : ($event->event_type_id == 3 ? 'selected' : '') }}
                                            value="3">Híbrido
                                        </option>
                                    </select>
                                    <label for="tipoEvento">Tipo do evento (presencial, remoto ou híbrido)</label>
                                </div>
                            </div>
                        </div>
                        {{-- <hr class="my-3 pb-3 border-3"> --}}
                        <h5 class="text-center mt-4 mb-5 fields"
                            @if ($event->event_type_id == 2) style="display: none;" @endif>
                            Informações de endereço</h5>
                        <div class="fields" @if ($event->event_type_id == 2) style="display: none;" @endif>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="cep" id="cep" type="text"
                                            placeholder="CEP" @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('cep') ? old('cep') : $event->cep }}" />
                                        <label for="cep">CEP</label>
                                        <div class="invalid-feedback">CEP é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="endereco" id="endereco"
                                            type="text" placeholder="Endereço"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('endereco') ? old('endereco') : $event->address }}" />
                                        <label for="endereco">Endereço</label>
                                        <div class="invalid-feedback">Endereço é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="bairro" id="bairro"
                                            type="text" placeholder="Bairro"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('bairro') ? old('bairro') : $event->neighborhood }}" />
                                        <label for="bairro">Bairro</label>
                                        <div class="invalid-feedback">Bairro é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="row">
                                        <div class="col-lg-6 mt-lg-2">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input mt-3 bg-success" name="temNumero"
                                                        id="temNumero" type="checkbox" name="temNumero"
                                                        {{ old('temNumero') ? 'checked' : ($event->number == null || $event->number == '' ? '' : 'checked') }} />
                                                    <label class="form-check-label" for="temNumero">Tem
                                                        <br>numero?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control border-2" name="numero" id="numero"
                                                    type="number" placeholder="Número"
                                                    value="{{ old('numero') ? old('numero') : $event->number }}"
                                                    @if ($event->event_type_id != 2) required @endif />
                                                <label for="numero">Número</label>
                                                <div class="invalid-feedback">Número é obrigatório.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="complemento" id="complemento"
                                            type="text" placeholder="Complemento"
                                            value="{{ old('complemento') ? old('complemento') : $event->complement }}" />
                                        <label for="complemento">Complemento</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="cidade" id="cidade"
                                            type="text" placeholder="Cidade"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('cidade') ? old('cidade') : $event->city }}" />
                                        <label for="cidade">Cidade</label>
                                        <div class="invalid-feedback">Cidade é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-select border-2" name="estado" id="estado"
                                            aria-label="Estado" @if ($event->event_type_id != 2) required @endif>
                                            <option
                                                {{ old('estado') == 'AC' ? 'selected' : ($event->state == 'AC' ? 'selected' : '') }}
                                                value="AC">Acre</option>
                                            <option
                                                {{ old('estado') == 'AL' ? 'selected' : ($event->state == 'AL' ? 'selected' : '') }}
                                                value="AL">Alagoas
                                            </option>
                                            <option
                                                {{ old('estado') == 'AP' ? 'selected' : ($event->state == 'AP' ? 'selected' : '') }}
                                                value="AP">Amapá
                                            </option>
                                            <option
                                                {{ old('estado') == 'AM' ? 'selected' : ($event->state == 'AM' ? 'selected' : '') }}
                                                value="AM">Amazonas
                                            </option>
                                            <option
                                                {{ old('estado') == 'BA' ? 'selected' : ($event->state == 'BA' ? 'selected' : '') }}
                                                value="BA">Bahia
                                            </option>
                                            <option
                                                {{ old('estado') == 'CE' ? 'selected' : ($event->state == 'CE' ? 'selected' : '') }}
                                                value="CE">Ceará
                                            </option>
                                            <option
                                                {{ old('estado') == 'DF' ? 'selected' : ($event->state == 'DF' ? 'selected' : '') }}
                                                value="DF">Distrito
                                                Federal
                                            </option>
                                            <option
                                                {{ old('estado') == 'ES' ? 'selected' : ($event->state == 'ES' ? 'selected' : '') }}
                                                value="ES">Espírito
                                                Santo
                                            </option>
                                            <option
                                                {{ old('estado') == 'GO' ? 'selected' : ($event->state == 'GO' ? 'selected' : '') }}
                                                value="GO">Goiás
                                            </option>
                                            <option
                                                {{ old('estado') == 'MA' ? 'selected' : ($event->state == 'MA' ? 'selected' : '') }}
                                                value="MA">Maranhão
                                            </option>
                                            <option
                                                {{ old('estado') == 'MT' ? 'selected' : ($event->state == 'MT' ? 'selected' : '') }}
                                                value="MT">Mato Grosso
                                            </option>
                                            <option
                                                {{ old('estado') == 'MS' ? 'selected' : ($event->state == 'MS' ? 'selected' : '') }}
                                                value="MS">Mato Grosso
                                                do
                                                Sul</option>
                                            <option
                                                {{ old('estado') == 'MG' ? 'selected' : ($event->state == 'MG' ? 'selected' : '') }}
                                                value="MG">Minas Gerais
                                            </option>
                                            <option
                                                {{ old('estado') == 'PA' ? 'selected' : ($event->state == 'PA' ? 'selected' : '') }}
                                                value="PA">Pará</option>
                                            <option
                                                {{ old('estado') == 'PB' ? 'selected' : ($event->state == 'PB' ? 'selected' : '') }}
                                                value="PB">Paraíba
                                            </option>
                                            <option
                                                {{ old('estado') == 'PR' ? 'selected' : ($event->state == 'PR' ? 'selected' : '') }}
                                                value="PR">Paraná
                                            </option>
                                            <option
                                                {{ old('estado') == 'PE' ? 'selected' : ($event->state == 'PE' ? 'selected' : '') }}
                                                value="PE">Pernambuco
                                            </option>
                                            <option
                                                {{ old('estado') == 'PI' ? 'selected' : ($event->state == 'PI' ? 'selected' : '') }}
                                                value="PI">Piauí
                                            </option>
                                            <option
                                                {{ old('estado') == 'RJ' ? 'selected' : ($event->state == 'RJ' ? 'selected' : '') }}
                                                value="RJ">Rio de
                                                Janeiro
                                            </option>
                                            <option
                                                {{ old('estado') == 'RN' ? 'selected' : ($event->state == 'RN' ? 'selected' : '') }}
                                                value="RN">Rio Grande do
                                                Norte</option>
                                            <option
                                                {{ old('estado') == 'RS' ? 'selected' : ($event->state == 'RS' ? 'selected' : '') }}
                                                value="RS">Rio Grande do
                                                Sul
                                            </option>
                                            <option
                                                {{ old('estado') == 'RO' ? 'selected' : ($event->state == 'RO' ? 'selected' : '') }}
                                                value="RO">Rondônia
                                            </option>
                                            <option
                                                {{ old('estado') == 'RR' ? 'selected' : ($event->state == 'RR' ? 'selected' : '') }}
                                                value="RR">Roraima
                                            </option>
                                            <option
                                                {{ old('estado') == 'SC' ? 'selected' : ($event->state == 'SC' ? 'selected' : '') }}
                                                value="SC">Santa
                                                Catarina
                                            </option>
                                            <option
                                                {{ old('estado') == 'SP' ? 'selected' : ($event->state == 'SP' ? 'selected' : '') }}
                                                value="SP">São Paulo
                                            </option>
                                            <option
                                                {{ old('estado') == 'SE' ? 'selected' : ($event->state == 'SE' ? 'selected' : '') }}
                                                value="SE">Sergipe
                                            </option>
                                            <option
                                                {{ old('estado') == 'TO' ? 'selected' : ($event->state == 'TO' ? 'selected' : '') }}
                                                value="TO">Tocantins
                                            </option>
                                        </select>
                                        <label for="estado">Estado</label>
                                        <div class="invalid-feedback">Estado é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <hr class="my-3 pb-3 border-3"> --}}
                        <h5 class="text-center mt-4 mb-5 mt-lg-5">Observações</h5>
                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control border-2" name="observacao" id="observacao" type="text" placeholder="Observação"
                                        style="height: 10rem;">{{ old('observacao') ? old('observacao') : $event->observation }}</textarea>
                                    <label for="observacao">Observação sobre o evento</label>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-center mt-4 mb-5 mt-lg-5">Informações financeiras</h5>
                        <div class="row">
                            <div class="col-auto col-xxl-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="valorTotal" id="valorTotal"
                                        type="text" placeholder="Valor total" required
                                        value="{{ old('valorTotal') ? old('valorTotal') : $event->total_price }}" />
                                    <label for="valorTotal">Valor total</label>
                                    <div class="invalid-feedback">Valor total é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-auto col-xxl-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="nossoValor" id="nossoValor"
                                        type="text" placeholder="Nosso valor" required
                                        value="{{ old('nossoValor') ? old('nossoValor') : $event->our_price }}" />
                                    <label for="nossoValor">Nosso valor</label>
                                    <div class="invalid-feedback">Nosso valor é obrigatório.</div>
                                </div>
                            </div>
                            @php
                                $prazo_pagamento_cliente = $event->customer->financial_observation;
                                // dd($prazo_pagamento_cliente);
                                if ($prazo_pagamento_cliente == 1) {
                                    $data_pagamento = $event->created_at->addDays(7);
                                    $event->payment_term ? ($data_cobranca = $event->payment_term->subDays(3)) : ($data_cobranca = $data_pagamento->subDays(3));
                                } elseif ($prazo_pagamento_cliente == 2) {
                                    $data_pagamento = $event->created_at->addDays(14);
                                    $event->payment_term ? ($data_cobranca = $event->payment_term->subDays(3)) : ($data_cobranca = $data_pagamento->subDays(3));
                                } elseif ($prazo_pagamento_cliente == 4) {
                                    $data_pagamento = $event->created_at->addDays(60);
                                    $event->payment_term ? ($data_cobranca = $event->payment_term->subDays(3)) : ($data_cobranca = $data_pagamento->subDays(3));
                                } elseif ($prazo_pagamento_cliente == 5) {
                                    $data_pagamento = $event->created_at->addDays(90);
                                    $event->payment_term ? ($data_cobranca = $event->payment_term->subDays(3)) : ($data_cobranca = $data_pagamento->subDays(3));
                                } elseif ($prazo_pagamento_cliente == 6) {
                                    $data_pagamento = $event->created_at->addDays(180);
                                    $event->payment_term ? ($data_cobranca = $event->payment_term->subDays(3)) : ($data_cobranca = $data_pagamento->subDays(3));
                                } else {
                                    $data_pagamento = $event->created_at->addDays(30);
                                    $event->payment_term ? ($data_cobranca = $event->payment_term->subDays(3)) : ($data_cobranca = $data_pagamento->subDays(3));
                                }
                                $data_pagamento->addDays(3);
                            @endphp
                            <div class="col-auto col-xxl-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="prazoPagamento" id="prazoPagamento"
                                        type="date" required
                                        value="{{ old('prazoPagamento') ? old('prazoPagamento') : ($event->payment_term ? date('Y-m-d', strtotime($event->payment_term)) : date('Y-m-d', strtotime($data_pagamento))) }}" />
                                    <label for="prazoPagamento">Prazo de pagamento</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto col-xxl-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="emissaoNota" id="emissaoNota"
                                        type="date" placeholder="Emissão da nota" disabled
                                        value="{{ $event->note_emission_date ? date('Y-m-d', strtotime($event->note_emission_date)) : null }}" />
                                    <label for="emissaoNota">Emissão da nota</label>
                                    <div class="invalid-feedback">Data de emissão da nota é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-auto col-xxl-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="dataRecebimento" id="dataRecebimento"
                                        type="date" placeholder="Data recebimento" disabled
                                        value="{{ $event->receipt_date ? date('Y-m-d', strtotime($event->receipt_date)) : null }}" />
                                    <label for="dataRecebimento">Data recebimento</label>
                                    <div class="invalid-feedback">Data recebimento é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-auto col-lg-5 col-xxl-3">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="dataPagamentoPalestrante"
                                        id="dataPagamentoPalestrante" type="date" placeholder="Valor total" disabled
                                        value="{{ old('dataPagamentoPalestrante') ? old('dataPagamentoPalestrante') : ($event->speaker_payment_date ? date('Y-m-d', strtotime($event->speaker_payment_date)) : null) }}" />
                                    <label for="dataPagamentoPalestrante">Data de pagamento p/ palestrante(s)</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control border-2" name="observacaoFinanceira" id="observacaoFinanceira" type="text"
                                        placeholder="Observação" style="height: 10rem;">{{ old('observacaoFinanceira') ? old('observacaoFinanceira') : $event->financial_observation }}</textarea>
                                    <label for="observacaoFinanceira">Observação financeira</label>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-center mt-4 mb-5 fields"
                            @if ($event->event_type_id == 2) style="display: none;" @endif>
                            Transporte e deslocamento</h5>
                        <div class="fields" @if ($event->event_type_id == 2) style="display: none;" @endif>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-floating mb-3">
                                        <div class="form-floating mb-3">
                                            <select class="form-select border-2" name="tipoTransporte"
                                                id="tipoTransporte" aria-label="Tipo de transporte" required>
                                                <option value="0" {{ $hasTransport === false ? 'selected' : '' }}>
                                                    Selecione
                                                </option>
                                                <option value="1"
                                                    {{ $event->transportPlane->count() > 0 ? 'selected' : '' }}>
                                                    Avião
                                                </option>
                                                <option value="2"
                                                    {{ $event->transportBusBetweenCity->count() > 0 ? 'selected' : '' }}>
                                                    Ônibus - longa distância
                                                </option>
                                                <option value="3"
                                                    {{ $event->transportBusSameCity->count() > 0 ? 'selected' : '' }}>
                                                    Ônibus - curta distância
                                                </option>
                                                <option value="4"
                                                    {{ $event->transportUber->count() > 0 ? 'selected' : '' }}>
                                                    Uber
                                                </option>
                                                <option value="5"
                                                    {{ $event->transportTaxi->count() > 0 ? 'selected' : '' }}>
                                                    Táxi
                                                </option>
                                            </select>
                                            <label for="tipoTransporte">Tipo de transporte</label>
                                            <div class="invalid-feedback">Tipo de transporte é obrigatório.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div id="load" style="display: none;">
                                        <div class="spinner-border mt-lg-3" role="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="aviaoSelecionado" {!! $event->transportPlane->count() > 0 ? '' : 'style="display: none;"' !!}>
                                @php
                                    $plane_one = $event->transportPlane->where('type', 1)->first();
                                    $plane_two = $event->transportPlane->where('type', 2)->first();
                                @endphp
                                <h6 class="text-center mt-4 mb-4"><strong>Ida</strong></h6>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <select class="form-select border-2" name="empresaAereaIda"
                                                id="empresaAereaIda">
                                                <option value="" disabled
                                                    {{ old('empresaAereaIda') ? '' : (optional($plane_one)->company ? '' : 'selected') }}>
                                                    Selecione</option>
                                                <option value="1"
                                                    {{ old('empresaAereaIda') ? 'selected' : (optional($plane_one)->company == 1 ? 'selected' : '') }}>
                                                    Gol</option>
                                                <option value="2"
                                                    {{ old('empresaAereaIda') ? 'selected' : (optional($plane_one)->company == 2 ? 'selected' : '') }}>
                                                    Latam</option>
                                            </select>
                                            <label for="empresaAereaIda">Empresa</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="numeroAviaoIda"
                                                id="numeroAviaoIda" type="number" placeholder="Número"
                                                value="{{ old('numeroAviaoIda') ? old('numeroAviaoIda') : ($plane_one ? $plane_one->number : '') }}" />
                                            <label for="numeroAviaoIda">Número</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="assentoAviaoIda"
                                                id="assentoAviaoIda" type="text" placeholder="Assento"
                                                value="{{ old('assentoAviaoIda') ? old('assentoAviaoIda') : ($plane_one ? $plane_one->seat : '') }}" />
                                            <label for="assentoAviaoIda">Assento</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="aeroportoOrigemIda"
                                                id="aeroportoOrigemIda" type="text" placeholder="Aeroporto de Origem"
                                                value="{{ old('aeroportoOrigemIda') ? old('aeroportoOrigemIda') : ($plane_one ? $plane_one->origin_airport : '') }}" />
                                            <label for="aeroportoOrigemIda">Aeroporto de Origem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="aeroportoDestinoIda"
                                                id="aeroportoDestinoIda" type="text"
                                                placeholder="Aeroporto de Destino"
                                                value="{{ old('aeroportoDestinoIda') ? old('aeroportoDestinoIda') : ($plane_one ? $plane_one->destiny_airport : '') }}" />
                                            <label for="aeroportoDestinoIda">Aeroporto de Destino</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataViagemIda" id="dataViagemIda"
                                                type="date"
                                                value="{{ old('dataViagemIda') ? old('dataViagemIda') : ($plane_one ? $plane_one->trip_date : '') }}" />
                                            <label for="dataViagemIda">Data da Viagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="inicioEmbarqueIda"
                                                id="inicioEmbarqueIda" type="time"
                                                value="{{ old('inicioEmbarqueIda') ? old('inicioEmbarqueIda') : ($plane_one ? $plane_one->start_boarding : '') }}" />
                                            <label for="inicioEmbarqueIda">Início do Embarque</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="previsaoChegadaIda"
                                                id="previsaoChegadaIda" type="time"
                                                value="{{ old('previsaoChegadaIda') ? old('previsaoChegadaIda') : ($plane_one ? $plane_one->arrival_forecast : '') }}" />
                                            <label for="previsaoChegadaIda">Previsão de Chegada</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="compradorPassagemIda"
                                                id="compradorPassagemIda" type="text"
                                                placeholder="Comprador da Passagem"
                                                value="{{ old('compradorPassagemIda') ? old('compradorPassagemIda') : ($plane_one ? $plane_one->ticket_buyer : '') }}" />
                                            <label for="compradorPassagemIda">Comprador da Passagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="telefoneCompradorIda"
                                                id="telefoneCompradorIda" type="text"
                                                placeholder="Telefone do Comprador"
                                                value="{{ old('telefoneCompradorIda') ? old('telefoneCompradorIda') : ($plane_one ? $plane_one->phone_buyer : '') }}" />
                                            <label for="telefoneCompradorIda">Telefone do Comprador</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2  " name="observacaoViagemIda" id="observacaoViagemIda"
                                                placeholder="Observação sobre a ida" style="height: 10rem;">{{ old('observacaoViagemIda') ? old('observacaoViagemIda') : ($plane_one ? $plane_one->observation : '') }}</textarea>
                                            <label for="observacaoViagemIda">Observação sobre a ida</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-center mt-4 mb-4"><strong>Volta</strong></h6>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <select class="form-select border-2" name="empresaAereaVolta"
                                                id="empresaAereaVolta">
                                                <option value="" disabled
                                                    {{ old('empresaAereaVolta') ? '' : (optional($plane_two)->company ? '' : 'selected') }}>
                                                    Selecione</option>
                                                <option value="1"
                                                    {{ old('empresaAereaVolta') ? 'selected' : (optional($plane_two)->company == 1 ? 'selected' : '') }}>
                                                    Gol</option>
                                                <option value="2"
                                                    {{ old('empresaAereaVolta') ? 'selected' : (optional($plane_two)->company == 2 ? 'selected' : '') }}>
                                                    Latam</option>
                                            </select>
                                            <label for="empresaAereaVolta">Empresa</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="numeroAviaoVolta"
                                                id="numeroAviaoVolta" type="number" placeholder="Número"
                                                value="{{ old('numeroAviaoVolta') ? old('numeroAviaoVolta') : ($plane_two ? $plane_two->number : '') }}" />
                                            <label for="numeroAviaoVolta">Número</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="assentoAviaoVolta"
                                                id="assentoAviaoVolta" type="text" placeholder="Assento"
                                                value="{{ old('assentoAviaoVolta') ? old('assentoAviaoVolta') : ($plane_two ? $plane_two->seat : '') }}" />
                                            <label for="assentoAviaoVolta">Assento</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="aeroportoOrigemVolta"
                                                id="aeroportoOrigemVolta" type="text"
                                                placeholder="Aeroporto de Origem"
                                                value="{{ old('aeroportoOrigemVolta') ? old('aeroportoOrigemVolta') : ($plane_two ? $plane_two->origin_airport : '') }}" />
                                            <label for="aeroportoOrigemVolta">Aeroporto de Origem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="aeroportoDestinoVolta"
                                                id="aeroportoDestinoVolta" type="text"
                                                placeholder="Aeroporto de Destino"
                                                value="{{ old('aeroportoDestinoVolta') ? old('aeroportoDestinoVolta') : ($plane_two ? $plane_two->destiny_airport : '') }}" />
                                            <label for="aeroportoDestinoVolta">Aeroporto de Destino</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataViagemVolta"
                                                id="dataViagemVolta" type="date"
                                                value="{{ old('dataViagemVolta') ? old('dataViagemVolta') : ($plane_two ? $plane_two->trip_date : '') }}" />
                                            <label for="dataViagemVolta">Data da Viagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="inicioEmbarqueVolta"
                                                id="inicioEmbarqueVolta" type="time"
                                                value="{{ old('inicioEmbarqueVolta') ? old('inicioEmbarqueVolta') : ($plane_two ? $plane_two->start_boarding : '') }}" />
                                            <label for="inicioEmbarqueVolta">Início do Embarque</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="previsaoChegadaVolta"
                                                id="previsaoChegadaVolta" type="time"
                                                value="{{ old('previsaoChegadaVolta') ? old('previsaoChegadaVolta') : ($plane_two ? $plane_two->arrival_forecast : '') }}" />
                                            <label for="previsaoChegadaVolta">Previsão de Chegada</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="compradorPassagemVolta"
                                                id="compradorPassagemVolta" type="text"
                                                placeholder="Comprador da Passagem"
                                                value="{{ old('compradorPassagemVolta') ? old('compradorPassagemVolta') : ($plane_two ? $plane_two->ticket_buyer : '') }}" />
                                            <label for="compradorPassagemVolta">Comprador da Passagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="telefoneCompradorVolta"
                                                id="telefoneCompradorVolta" type="text"
                                                placeholder="Telefone do Comprador"
                                                value="{{ old('telefoneCompradorVolta') ? old('telefoneCompradorVolta') : ($plane_two ? $plane_two->phone_buyer : '') }}" />
                                            <label for="telefoneCompradorVolta">Telefone do Comprador</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2  " name="observacaoViagemVolta" id="observacaoViagemVolta"
                                                placeholder="Observação sobre a volta" style="height: 10rem;">{{ old('observacaoViagemVolta') ? old('observacaoViagemVolta') : ($plane_two ? $plane_two->observation : '') }}</textarea>
                                            <label for="observacaoViagemVolta">Observação sobre a volta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Ônibus --}}
                            <div id="onibusLongaDistanciaSelecionado" style="display: none;">
                                <h6 class="text-center mt-4 mb-4"><strong>Ida</strong></h6>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="empresaOnibusIda"
                                                id="empresaOnibusIda" type="text" placeholder="Empresa" />
                                            <label for="empresaOnibusIda">Empresa</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="numeroOnibusIda"
                                                id="numeroOnibusIda" type="number" placeholder="Número" />
                                            <label for="numeroOnibusIda">Número</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="assentoOnibusIda"
                                                id="assentoOnibusIda" type="text" placeholder="Assento" />
                                            <label for="assentoOnibusIda">Assento</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="terminalOrigemIda"
                                                id="terminalOrigemIda" type="text"
                                                placeholder="Terminal Rodoviário de origem" />
                                            <label for="terminalOrigemIda">Terminal Rodoviário de origem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="terminalDestinoIda"
                                                id="terminalDestinoIda" type="text"
                                                placeholder="Terminal Rodoviário de destino" />
                                            <label for="terminalDestinoIda">Terminal Rodoviário de destino</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataViagemOnibusEntreCidadesIda"
                                                id="dataViagemOnibusEntreCidadesIda" type="date" />
                                            <label for="dataViagemOnibusEntreCidadesIda">Data da Viagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="inicioEmbarqueOnibusIda"
                                                id="inicioEmbarqueOnibusIda" type="time" />
                                            <label for="inicioEmbarqueOnibusIda">Início do Embarque</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="previsaoChegadaOnibusIda"
                                                id="previsaoChegadaOnibusIda" type="time" />
                                            <label for="previsaoChegadaOnibusIda">Previsão de Chegada</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="compradorPassagemOnibusIda"
                                                id="compradorPassagemOnibusIda" type="text"
                                                placeholder="Comprador da Passagem" />
                                            <label for="compradorPassagemOnibusIda">Comprador da Passagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="telefoneCompradorOnibusIda"
                                                id="telefoneCompradorOnibusIda" type="text"
                                                placeholder="Telefone do Comprador" />
                                            <label for="telefoneCompradorOnibusIda">Telefone do Comprador</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2  " name="observacaoViagemOnibusIda" id="observacaoViagemOnibusIda"
                                                placeholder="Observação sobre a ida" style="height: 10rem;"></textarea>
                                            <label for="observacaoViagemOnibusIda">Observação sobre a ida</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-center mt-4 mb-4"><strong>Volta</strong></h6>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="empresaOnibusVolta"
                                                id="empresaOnibusVolta" type="text" placeholder="Empresa" />
                                            <label for="empresaOnibusVolta">Empresa</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="numeroOnibusVolta"
                                                id="numeroOnibusVolta" type="number" placeholder="Número" />
                                            <label for="numeroOnibusVolta">Número</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="assentoOnibusVolta"
                                                id="assentoOnibusVolta" type="text" placeholder="Assento" />
                                            <label for="assentoOnibusVolta">Assento</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="terminalOrigemVolta"
                                                id="terminalOrigemVolta" type="text"
                                                placeholder="Terminal Rodoviário de origem" />
                                            <label for="terminalOrigemVolta">Terminal Rodoviário de origem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="terminalDestinoVolta"
                                                id="terminalDestinoVolta" type="text"
                                                placeholder="Terminal Rodoviário de destino" />
                                            <label for="terminalDestinoVolta">Terminal Rodoviário de destino</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataViagemOnibusEntreCidadesVolta"
                                                id="dataViagemOnibusEntreCidadesVolta" type="date" />
                                            <label for="dataViagemOnibusEntreCidadesVolta">Data da Viagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="inicioEmbarqueOnibusVolta"
                                                id="inicioEmbarqueOnibusVolta" type="time" />
                                            <label for="inicioEmbarqueOnibusVolta">Início do Embarque</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="previsaoChegadaOnibusVolta"
                                                id="previsaoChegadaOnibusVolta" type="time" />
                                            <label for="previsaoChegadaOnibusVolta">Previsão de Chegada</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="compradorPassagemOnibusVolta"
                                                id="compradorPassagemOnibusVolta" type="text"
                                                placeholder="Comprador da Passagem" />
                                            <label for="compradorPassagemOnibusVolta">Comprador da Passagem</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="telefoneCompradorOnibusVolta"
                                                id="telefoneCompradorOnibusVolta" type="text"
                                                placeholder="Telefone do Comprador" />
                                            <label for="telefoneCompradorOnibusVolta">Telefone do Comprador</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2  " name="observacaoViagemOnibusVolta" id="observacaoViagemOnibusVolta"
                                                placeholder="Observação sobre a ida" style="height: 10rem;"></textarea>
                                            <label for="observacaoViagemOnibusVolta">Observação sobre a ida</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="onibusCurtaDistanciaSelecionado" {!! $event->transportBusSameCity->count() > 0 ? '' : 'style="display: none;"' !!}>
                                @php
                                    $bus_same_city_one = $event->transportBusSameCity->where('type', 1)->first();
                                    $bus_same_city_two = $event->transportBusSameCity->where('type', 2)->first();
                                @endphp
                                <h6 class="text-center mt-4 mb-4"><strong>Ida</strong></h6>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2"
                                                name="dataLocomocaoOnibusCurtaDistanciaIda"
                                                id="dataLocomocaoOnibusCurtaDistanciaIda" type="date"
                                                value="{{ $bus_same_city_one ? $bus_same_city_one->date : '' }}" />
                                            <label for="dataLocomocaoOnibusCurtaDistanciaIda">Data da locomoção</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="ObservacaoOnibusCurtaDistanciaIda"
                                                id="ObservacaoOnibusCurtaDistanciaIda" style="height: 10rem;">{{ $bus_same_city_one ? $bus_same_city_one->observation : '' }}</textarea>
                                            <label for="ObservacaoOnibusCurtaDistanciaIda">Observação sobre a ida</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-center mt-4 mb-4"><strong>Volta</strong></h6>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2"
                                                name="dataLocomocaoOnibusCurtaDistanciaVolta"
                                                id="dataLocomocaoOnibusCurtaDistanciaVolta" type="date"
                                                value="{{ $bus_same_city_two ? $bus_same_city_two->date : '' }}" />
                                            <label for="dataLocomocaoOnibusCurtaDistanciaVolta">Data da Locomoção</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="ObservacaoOnibusCurtaDistanciaVolta"
                                                id="ObservacaoOnibusCurtaDistanciaVolta" style="height: 10rem;">{{ $bus_same_city_two ? $bus_same_city_two->observation : '' }}</textarea>
                                            <label for="ObservacaoOnibusCurtaDistanciaVolta">Observação sobre a
                                                volta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="uberSelecionado" {!! $event->transportUber->count() > 0 ? '' : 'style="display: none;"' !!}>
                                @php
                                    $uber_one = $event->transportUber->where('type', 1)->first();
                                    $uber_two = $event->transportUber->where('type', 2)->first();
                                @endphp
                                <h6 class="text-center mt-4 mb-4"><strong>Ida</strong></h6>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataLocomocaoUberIda"
                                                value="{{ $uber_one ? $uber_one->date : '' }}" id="dataLocomocaoUberIda"
                                                type="date" />
                                            <label for="dataLocomocaoUberIda">Data da locomoção</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="ObservacaoUberIda" id="ObservacaoUberIda" style="height: 10rem;">{{ $uber_one ? $uber_one->observation : '' }}</textarea>
                                            <label for="ObservacaoUberIda">Observação sobre a ida</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-center mt-4 mb-4"><strong>Volta</strong></h6>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataLocomocaoUberVolta"
                                                value="{{ $uber_two ? $uber_two->date : '' }}"
                                                id="dataLocomocaoUberVolta" type="date" />
                                            <label for="dataLocomocaoUberVolta">Data da locomoção</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="ObservacaoUberVolta" id="ObservacaoUberVolta" style="height: 10rem;">{{ $uber_two ? $uber_two->observation : '' }}</textarea>
                                            <label for="ObservacaoUberVolta">Observação sobre a volta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="taxiSelecionado" {!! $event->transportTaxi->count() > 0 ? '' : 'style="display: none;"' !!}>
                                @php
                                    $taxi_one = $event->transportTaxi->where('type', 1)->first();
                                    $taxi_two = $event->transportTaxi->where('type', 2)->first();
                                @endphp
                                <h6 class="text-center mt-4 mb-4"><strong>Ida</strong></h6>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataLocomocaoTaxiIda"
                                                id="dataLocomocaoTaxiIda" type="date"
                                                value="{{ $taxi_one ? $taxi_one->date : '' }}" />
                                            <label for="dataLocomocaoTaxiIda">Data da locomoção</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="ObservacaoTaxiIda" id="ObservacaoTaxiIda" style="height: 10rem;">{{ $taxi_one ? $taxi_one->observation : '' }}</textarea>
                                            <label for="ObservacaoTaxiIda">Observação sobre a ida</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-center mt-4 mb-4"><strong>Volta</strong></h6>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="dataLocomocaoTaxiVolta"
                                                id="dataLocomocaoTaxiVolta" type="date"
                                                value="{{ $taxi_two ? $taxi_two->date : '' }}" />
                                            <label for="dataLocomocaoTaxiVolta">Data da locomoção</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="ObservacaoTaxiVolta" id="ObservacaoTaxiVolta" style="height: 10rem;">{{ $taxi_two ? $taxi_two->observation : '' }}</textarea>
                                            <label for="ObservacaoTaxiVolta">Observação sobre a volta</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="text-center mt-4 mb-5"
                                @if ($event->event_type_id == 2) style="display: none;" @endif>
                                Hospedagem e acomodação</h5>
                            @php
                                $hosting = $event->hosting;
                            @endphp
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="nomeHospedagem" id="nomeHospedagem"
                                            type="text" placeholder="Nome da hospedagem"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('nomeHospedagem') ? old('nomeHospedagem') : ($hosting ? $hosting->name : '') }}" />
                                        <label for="nomeHospedagem">Nome da hospedagem</label>
                                        <div class="invalid-feedback">Nome da hospedagem é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="checkIn" id="checkIn"
                                            type="datetime-local" placeholder="Check in"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('checkIn') ? old('checkIn') : ($hosting ? $hosting->check_in : '') }}" />
                                        <label for="checkIn">Check in</label>
                                        <div class="invalid-feedback">Check in é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="checkOut" id="checkOut"
                                            type="datetime-local" placeholder="Check out"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('checkOut') ? old('checkOut') : ($hosting ? $hosting->check_out : '') }}" />
                                        <label for="checkOut">Check out</label>
                                        <div class="invalid-feedback">Check out é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="cepHospedagem" id="cepHospedagem"
                                            type="text" placeholder="CEP"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('cepHospedagem') ? old('cepHospedagem') : ($hosting ? $hosting->cep : '') }}" />
                                        <label for="cepHospedagem">CEP</label>
                                        <div class="invalid-feedback">CEP é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="enderecoHospedagem"
                                            id="enderecoHospedagem" type="text" placeholder="Endereço"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('enderecoHospedagem') ? old('enderecoHospedagem') : ($hosting ? $hosting->address : '') }}" />
                                        <label for="enderecoHospedagem">Endereço</label>
                                        <div class="invalid-feedback">Endereço da hospedagem é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="bairroHospedagem"
                                            id="bairroHospedagem" type="text" placeholder="Bairro"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('bairroHospedagem') ? old('bairroHospedagem') : ($hosting ? $hosting->neighborhood : '') }}" />
                                        <label for="bairroHospedagem">Bairro</label>
                                        <div class="invalid-feedback">Bairro é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="row">
                                        <div class="col-lg-6 mt-lg-2">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input mt-3 bg-success"
                                                        name="temNumeroHospedagem" id="temNumeroHospedagem"
                                                        type="checkbox"
                                                        {{ old('temNumeroHospedagem') ? 'checked' : (optional($hosting)->number ? 'checked' : '') }}>
                                                    <label class="form-check-label" for="temNumeroHospedagem">Tem
                                                        <br>numero?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control border-2" name="numeroHospedagem"
                                                    id="numeroHospedagem" type="number" placeholder="Número"
                                                    value="{{ old('numeroHospedagem') ? old('numeroHospedagem') : ($hosting ? $hosting->number : '') }}"
                                                    @if ($event->event_type_id != 2) required @endif />
                                                <label for="numeroHospedagem">Número</label>
                                                <div class="invalid-feedback">Número é obrigatório.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="complementoHospedagem"
                                            id="complementoHospedagem" type="text" placeholder="Complemento"
                                            value="{{ old('complementoHospedagem') ? old('complementoHospedagem') : ($hosting ? $hosting->complement : '') }}" />
                                        <label for="complementoHospedagem">Complemento</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="cidadeHospedagem"
                                            id="cidadeHospedagem" type="text" placeholder="Cidade"
                                            @if ($event->event_type_id != 2) required @endif
                                            value="{{ old('cidadeHospedagem') ? old('cidadeHospedagem') : ($hosting ? $hosting->city : '') }}" />
                                        <label for="cidadeHospedagem">Cidade</label>
                                        <div class="invalid-feedback">Cidade é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-select border-2" name="estadoHospedagem"
                                            id="estadoHospedagem" aria-label="Estado"
                                            @if ($event->event_type_id != 2) required @endif>
                                            <option
                                                {{ old('estadoHospedagem') == 'AC' || ($hosting && $hosting->state == 'AC') ? 'selected' : '' }}
                                                value="AC">Acre</option>
                                            <option
                                                {{ old('estadoHospedagem') == 'AL' || ($hosting && $hosting->state == 'AL') ? 'selected' : '' }}
                                                value="AL">Alagoas
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'AP' || ($hosting && $hosting->state == 'AP') ? 'selected' : '' }}
                                                value="AP">Amapá
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'AM' || ($hosting && $hosting->state == 'AM') ? 'selected' : '' }}
                                                value="AM">Amazonas
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'BA' || ($hosting && $hosting->state == 'BA') ? 'selected' : '' }}
                                                value="BA">Bahia
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'CE' || ($hosting && $hosting->state == 'CE') ? 'selected' : '' }}
                                                value="CE">Ceará
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'DF' || ($hosting && $hosting->state == 'DF') ? 'selected' : '' }}
                                                value="DF">Distrito
                                                Federal
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'ES' || ($hosting && $hosting->state == 'ES') ? 'selected' : '' }}
                                                value="ES">Espírito
                                                Santo
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'GO' || ($hosting && $hosting->state == 'GO') ? 'selected' : '' }}
                                                value="GO">Goiás
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'MA' || ($hosting && $hosting->state == 'MA') ? 'selected' : '' }}
                                                value="MA">Maranhão
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'MT' || ($hosting && $hosting->state == 'MT') ? 'selected' : '' }}
                                                value="MT">Mato Grosso
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'MS' || ($hosting && $hosting->state == 'MS') ? 'selected' : '' }}
                                                value="MS">Mato Grosso
                                                do
                                                Sul</option>
                                            <option
                                                {{ old('estadoHospedagem') == 'MG' || ($hosting && $hosting->state == 'MG') ? 'selected' : '' }}
                                                value="MG">Minas Gerais
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'PA' || ($hosting && $hosting->state == 'PA') ? 'selected' : '' }}
                                                value="PA">Pará</option>
                                            <option
                                                {{ old('estadoHospedagem') == 'PB' || ($hosting && $hosting->state == 'PB') ? 'selected' : '' }}
                                                value="PB">Paraíba
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'PR' || ($hosting && $hosting->state == 'PR') ? 'selected' : '' }}
                                                value="PR">Paraná
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'PE' || ($hosting && $hosting->state == 'PE') ? 'selected' : '' }}
                                                value="PE">Pernambuco
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'PI' || ($hosting && $hosting->state == 'PI') ? 'selected' : '' }}
                                                value="PI">Piauí
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'RJ' || ($hosting && $hosting->state == 'RJ') ? 'selected' : '' }}
                                                value="RJ">Rio de
                                                Janeiro
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'RN' || ($hosting && $hosting->state == 'RN') ? 'selected' : '' }}
                                                value="RN">Rio Grande do
                                                Norte</option>
                                            <option
                                                {{ old('estadoHospedagem') == 'RS' || ($hosting && $hosting->state == 'RS') ? 'selected' : '' }}
                                                value="RS">Rio Grande do
                                                Sul
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'RO' || ($hosting && $hosting->state == 'RO') ? 'selected' : '' }}
                                                value="RO">Rondônia
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'RR' || ($hosting && $hosting->state == 'RR') ? 'selected' : '' }}
                                                value="RR">Roraima
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'SC' || ($hosting && $hosting->state == 'SC') ? 'selected' : '' }}
                                                value="SC">Santa
                                                Catarina
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'SP' || ($hosting && $hosting->state == 'SP') ? 'selected' : '' }}
                                                value="SP">São Paulo
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'SE' || ($hosting && $hosting->state == 'SE') ? 'selected' : '' }}
                                                value="SE">Sergipe
                                            </option>
                                            <option
                                                {{ old('estadoHospedagem') == 'TO' || ($hosting && $hosting->state == 'TO') ? 'selected' : '' }}
                                                value="TO">Tocantins
                                            </option>
                                        </select>
                                        <label for="estadoHospedagem">Estado</label>
                                        <div class="invalid-feedback">Estado é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="pontoDeReferenciaHospedagem"
                                            id="pontoDeReferenciaHospedagem" type="text"
                                            placeholder="Ponto de referência"
                                            value="{{ old('pontoDeReferenciaHospedagem') ? old('pontoDeReferenciaHospedagem') : ($hosting ? $hosting->reference_point : '') }}" />
                                        <label for="pontoDeReferenciaHospedagem">Ponto de referência</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control border-2" name="observacaoHospedagem" id="observacaoHospedagem"
                                            placeholder="Observação" style="height: 10rem;">{{ old('observacaoHospedagem') ? old('observacaoHospedagem') : ($hosting ? $hosting->observation : '') }}</textarea>
                                        <label for="observacaoHospedagem">Observação sobre a hospedagem</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-center mt-4 mb-5 mt-lg-5">Palestrantes</h5>
                        @php
                            $count = 1;
                        @endphp
                        @foreach ($event->speakers as $speaker)
                            @php
                                $paid = $speaker->pivot->paid;
                            @endphp
                            <div class="row align-items-center">
                                <div class="col-lg-3">
                                    <p class="mb-0"><strong>Palestrante {{ $count }}:</strong></p>
                                    <p>{{ $speaker->name }}</p>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border-2 valor-palestrante"
                                            name="valorPalestrante[{{ $speaker->id }}]" placeholder="Valor em Reais"
                                            value="{{ $speaker->pivot->price }}">
                                        <label for="valorPalestrante">Valor do palestrante</label>
                                        <div class="invalid-feedback">Valor do palestrante é obrigatório.</div>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-lg-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input bg-success" id="pagamentoPalestranteEfetuado"
                                            type="checkbox" name="pagamentoPalestranteEfetuado[{{ $speaker->id }}]"
                                            {{ old('pagamentoPalestranteEfetuado') ? 'checked' : ($paid == null || $paid == 0 ? '' : 'checked') }} />
                                        <label class="form-check-label" for="pagamentoPalestranteEfetuado">Pagamento
                                            efetuado?</label>
                                    </div>
                                </div>
                                <div class="col-lg-2 text-end mb-lg-4 offset-lg-1">
                                    @if ($paid == null || $paid == 0)
                                        <small>Pagamento não efetuado.</small>
                                    @else
                                        <small>Pagamento já efetuado.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control border-2" name="observacaoPalestrante[{{ $speaker->id }}]"
                                        id="observacaoPalestrante[{{ $speaker->id }}]" type="text" placeholder="Observação"
                                        style="height: 10rem;">{{ old('observacaoPalestrante[$speaker->id]') ? old('observacaoPalestrante[$speaker->id}') : $speaker->pivot->observation }}</textarea>
                                    <label for="observacaoPalestrante[{{ $speaker->id }}]">Observação do
                                        palestrante</label>
                                    <div class="invalid-feedback">Explique o que foi feito pelo palestrante
                                    </div>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <hr style="width: 5%;" class="mx-auto">
                            @endif
                            @php
                                $count++;
                            @endphp
                        @endforeach
                        <h5 class="text-center mt-4 mb-5 mt-lg-5">Fornecedores</h5>
                        <div class="row">
                            <div id="supplierContainer">
                                @php
                                    $count = 1;
                                @endphp
                                @foreach ($event->suppliers as $supplier)
                                    @php
                                        $paid = $supplier->pivot->paid;
                                    @endphp
                                    <div class="row supplier-row align-items-center">
                                        <div class="col-lg-3">
                                            <p class="mb-0"><strong>Fornecedor {{ $count }}:</strong></p>
                                            <p>{{ $supplier->name }}</p>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border-2 valor-fornecedor"
                                                    name="valorFornecedor[{{ $supplier->id }}]"
                                                    placeholder="Valor em Reais"
                                                    value="{{ $supplier->pivot->price }}">
                                                <label for="valorFornecedor">Valor do fornecedor</label>
                                                <div class="invalid-feedback">Valor do fornecedor é obrigatório.</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-lg-3">
                                            <input type="text" name="fornecedor[{{ $supplier->id }}]"
                                                value="" style="display: none;">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input bg-success"
                                                    id="pagamentoFornecedorEfetuado" type="checkbox"
                                                    name="pagamentoFornecedorEfetuado[{{ $supplier->id }}]"
                                                    {{ old('pagamentoFornecedorEfetuado') ? 'checked' : ($paid == null || $paid == 0 ? '' : 'checked') }} />
                                                <label class="form-check-label"
                                                    for="pagamentoFornecedorEfetuado">Pagamento
                                                    efetuado?</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 text-end mb-lg-4 offset-lg-1">
                                            @if ($paid == null || $paid == 0)
                                                <small>Pagamento não efetuado.</small>
                                            @else
                                                <small>Pagamento já efetuado.</small>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <textarea class="form-control border-2" name="observacaoFornecedor[{{ $supplier->id }}]"
                                                    id="observacaoFornecedor[{{ $supplier->id }}]" type="text" placeholder="Observação"
                                                    style="height: 10rem;">{{ old('observacaoFornecedor[$supplier->id]') ? old('observacaoFornecedor[$supplier->id}') : $supplier->pivot->observation }}</textarea>
                                                <label for="observacaoPalestrante[{{ $supplier->id }}]">Observação do
                                                    fornecedor</label>
                                                <div class="invalid-feedback">Explique o que foi feito pelo fornecedor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-danger removeSupplier mb-3">Remover
                                                fornecedor</button>
                                        </div>
                                        @if (!$loop->last)
                                            <hr style="width: 5%;" class="mx-auto my-3 py-3">
                                        @endif
                                    </div>
                                    @php
                                        $count++;
                                    @endphp
                                @endforeach
                                <!-- Conteúdo gerado dinamicamente aqui -->
                            </div>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-primary mb-3" id="addSupplier">Adicionar
                                    fornecedor</button>
                            </div>
                        </div>
                        <h5 class="text-center mt-4 mb-5 mt-lg-5">Parceiro</h5>
                        <div class="row">
                            <div id="partnerContainer">
                                @if ($event->partner_id !== null || old('parceiro'))
                                    <div class="partner-row">
                                        <div class="form-row row mb-3 align-items-center">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control custom-select border-2" name="parceiro">
                                                        <option value="">Selecione um parceiro</option>
                                                        @foreach ($partners as $partner)
                                                            <option value="{{ $partner->id }}"
                                                                {{ old('parceiro') ? 'selected' : (optional($partner)->id == $event->partner_id ? 'selected' : '') }}>
                                                                {{ $partner->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="valorParceiro">Selecionar parceiro</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control border-2 valor-parceiro"
                                                        name="valorParceiro" placeholder="Valor em Reais"
                                                        value="{{ $event->partner_price }}">
                                                    <label for="valorParceiro">Valor do parceiro</label>
                                                    <div class="invalid-feedback">Valor do parceiro é obrigatório.</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 mb-lg-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input bg-success"
                                                        name="pagamentoParceiroEfetuado" id="pagamentoParceiroEfetuado"
                                                        type="checkbox" name="pagamentoParceiroEfetuado"
                                                        {{ old('pagamentoParceiroEfetuado') ? 'checked' : ($event->partner_received == null || $event->partner_received == 0 ? '' : 'checked') }} />
                                                    <label class="form-check-label"
                                                        for="pagamentoParceiroEfetuado">Pagamento
                                                        efetuado?</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 text-end mb-lg-4">
                                                @if ($event->partner_received == null || $event->partner_received == 0)
                                                    <small>Pagamento não efetuado.</small>
                                                @else
                                                    <small>Pagamento já efetuado.</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <textarea class="form-control border-2" name="observacaoParceiro" id="observacaoParceiro" type="text"
                                                        placeholder="Observação" style="height: 10rem;">{{ old('observacaoParceiro') ? old('observacaoParceiro') : $event->partner_observation }}</textarea>
                                                    <label for="observacaoParceiro">Observação do parceiro</label>
                                                    <div class="invalid-feedback">Explique o que foi feito pelo parceiro
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-danger removePartner mb-3">Remover
                                                    parceiro</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-primary mb-3" id="addPartner"
                                    @if ($event->partner_id !== null) style="display: none;" @endif>Adicionar
                                    parceiro</button>
                            </div>
                        </div>
                        <h5 class="text-center mt-4 mb-5 mt-lg-5">Vendedor</h5>
                        <div class="row">
                            <div id="sellerContainer">
                                @if ($event->seller_id !== null)
                                    <div class="seller-row">
                                        <div class="form-row row mb-3 align-items-center">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <select class="form-control custom-select border-2" name="vendedor">
                                                        <option value="">Selecione um vendedor</option>
                                                        @foreach ($sellers as $seller)
                                                            <option value="{{ $seller->id }}"
                                                                {{ old('parceiro') ? 'selected' : (optional($seller)->id == $event->seller_id ? 'selected' : '') }}>
                                                                {{ $seller->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="valorVendedor">Selecionar vendedor</label>
                                                </div>
                                            </div>
                                            {{-- {{dd($event->seller_price)}} --}}
                                            <div class="col-lg-3">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control border-2 valor-vendedor"
                                                        name="valorVendedor" placeholder="Valor em Reais"
                                                        value="{{ $event->seller_price }}">
                                                    <label for="valorVendedor">Valor do vendedor</label>
                                                    <div class="invalid-feedback">Valor do vendedor é obrigatório.</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 mb-lg-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input bg-success"
                                                        name="pagamentoVendedorEfetuado" id="pagamentoVendedorEfetuado"
                                                        type="checkbox" name="pagamentoVendedorEfetuado"
                                                        {{ old('pagamentoVendedorEfetuado') ? 'checked' : ($event->seller_received == null || $event->seller_received == 0 ? '' : 'checked') }} />
                                                    <label class="form-check-label"
                                                        for="pagamentoVendedorEfetuado">Pagamento
                                                        efetuado?</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 text-end mb-lg-4">
                                                @if ($event->seller_received == null || $event->seller_received == 0)
                                                    <small>Pagamento não efetuado.</small>
                                                @else
                                                    <small>Pagamento já efetuado.</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <textarea class="form-control border-2" name="observacaoVendedor" id="observacaoVendedor" type="text"
                                                        placeholder="Observação" style="height: 10rem;">{{ old('observacaoVendedor') ? old('observacaoVendedor') : $event->seller_observation }}</textarea>
                                                    <label for="observacaoVendedor">Observação do vendedor</label>
                                                    <div class="invalid-feedback">Explique o que foi feito pelo vendedor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-danger removeSeller mb-3">Remover
                                                    vendedor</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-primary mb-3" id="addSeller"
                                    @if ($event->seller_id !== null) style="display: none;" @endif>Adicionar
                                    vendedor</button>
                            </div>
                        </div>
                        {{-- <hr class="my-3 pb-3 border-3"> --}}
                        <div class="row">
                            <div class="col text-center">
                                <button class="btn btn-success btn-lg" id="submitButton" type="submit">Salvar
                                    alterações</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="col-lg-6 offset-lg-3 text-center">
                                <p class="mb-0">Gerenciar notas fiscais do evento:</p>
                                <p class="mb-0">{{ $event->name }}</p>
                            </div>
                            <div class="col-lg-3 text-end">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col text-center">
                                </div>
                            </div>
                            <form action="{{ route('nova_nota_fiscal', ['id' => $event->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="mb-3" style="width: fit-content;"><small>Nova <br>Nota fiscal:
                                        </small></div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <input class="form-control border-2" name="nota_fiscal" id="nota_fiscal"
                                                type="file" placeholder="Nota fiscal"
                                                value="{{ old('nota_fiscal') }}" title="Selecionar Nota Fiscal"
                                                accept="application/pdf" style="height: calc(3.5rem + calc(var(--bs-border-width) * 2));
                                                min-height: calc(3.5rem + calc(var(--bs-border-width) * 2));
                                                line-height: 2.5rem;" />
                                            <div class="invalid-feedback">Nota fiscal deve ser um PDF.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="data_faturamento"
                                                id="data_faturamento" type="date" placeholder="Data de faturamento"
                                                value="{{ old('data_faturamento') ? old('data_faturamento') : ($event->payment_term ? date('Y-m-d', strtotime($event->payment_term)) : date('Y-m-d', strtotime($data_pagamento))) }}" />
                                            <label for="data_faturamento">Data de faturamento</label>
                                            <div class="invalid-feedback">Data de faturamento é obrigatória</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" name="observacao_nota_fiscal" id="observacao_nota_fiscal" type="text"
                                                placeholder="Observação" style="height: 10rem;">{{ old('observacao_nota_fiscal') }}</textarea>
                                            <label for="observacao_nota_fiscal">Observação sobre a nota
                                                fiscal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <button class="btn btn-success" {{ $event->fiscalNote ? 'disabled' : '' }}
                                            type="submit">Adicionar</button>
                                    </div>
                                </div>
                            </form>
                            @if ($event->fiscalNote)
                                <div class="row">
                                    <p class="mt-lg-5 text-center">Nota já adicionada:</p>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input
                                                onclick="visualizarNota('{{ asset('storage/nf/' . $event->fiscalNote->filename) }}')"
                                                class="form-control" type="text"
                                                value="{{ $event->fiscalNote->filename }}" readonly
                                                style="cursor: pointer;">
                                            <label for="observacao_nota_fiscal">Nome do arquivo</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                @if ($event->fiscalNote->paid == 0)
                                                    <button onclick="mudarStatusNota({{ $event->fiscalNote->id }})"
                                                        type="button" class="w-100 btn btn-success">Declarar <br>como
                                                        paga</button>
                                                @else
                                                    <button onclick="mudarStatusNota({{ $event->fiscalNote->id }})"
                                                        type="button" class="w-100 btn btn-danger">Declarar <br>como
                                                        não
                                                        paga</button>
                                                @endif
                                            </div>
                                            <div class="col-lg-6">
                                                <button
                                                    onclick="enviarCobranca({{ $event->fiscalNote->id }}, '{{ $event->customer->emailPrincipal }}')"
                                                    type="button" class="w-100 btn btn-warning">Enviar lembrete <br>por
                                                    email</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" type="text"
                                                value="{{ date('d/m/Y', strtotime($event->fiscalNote->payment_term)) }}"
                                                readonly>
                                            <label>Data de faturamento</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control border-2" type="text" placeholder="Observação" style="height: 10rem;" readonly>{{ $event->fiscalNote->observation }}</textarea>
                                            <label for="observacao_nota_fiscal">Observação sobre a nota
                                                fiscal</label>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('excluir_nota_fiscal', ['id' => $event->fiscalNote->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit"
                                        onclick="return confirm('Tem certeza que deseja excluir esta nota fiscal?')"><i
                                            class="bi bi-trash"></i> Excluir esta nota fiscal</button>
                                </form>
                                <div class="row">
                                    <div class="col-12">
                                        <p class="mt-lg-5 text-center">Envios realizados</p>
                                        @if ($event->fiscalNote->sendedEmail->count() > 0)
                                        <table class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Envio</th>
                                                    <th>Data e hora</th>
                                                    <th>Quem enviou</th>
                                                    <th>Tipo do envio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($event->fiscalNote->sendedEmail as $send)
                                                <tr data-href="{{ route('visualizar_evento', $event->id) }}"
                                                    style="cursor: pointer">
                                                    <td>{{ $loop->count - $loop->iteration + 1 }}º</td>
                                                    <td>{{ $send->created_at->format('d/m/Y \à\s H:i') }}</td>
                                                    @if ($send->user_id)
                                                    <td>{{ $send->user->name }}</td>
                                                    <td>Manual</td>
                                                    @else
                                                    <td>Sistema</td>
                                                    <td>Automático</td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                            <p class="text-center">Ainda não foram enviados lembretes para o cliente.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mt-5">
                    <a href="{{ route('meus_eventos') }}" class="btn btn-info text-decoration-none me-5">Ver todos os
                        eventos</a>
                </div>
            </div>
        @endsection
        @section('script-body')
            <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                function visualizarNota(pdfUrl) {
                    window.open(pdfUrl, '_blank');
                }
            </script>
            <script>
                function novoValor(selectElement) {
                    var newName = 'fornecedor[' + selectElement.value + ']';
                    $(selectElement).attr('name', newName);

                    // Find the nearest input elements and update their names and values
                    var closestRow = $(selectElement).closest('.supplier-row');
                    closestRow.find('input[name^="valorFornecedor"]').attr('name', 'valorFornecedor[' + selectElement.value + ']');
                    closestRow.find('input[name^="pagamentoFornecedorEfetuado"]').attr('name', 'pagamentoFornecedorEfetuado[' +
                        selectElement.value + ']');
                    closestRow.find('textarea[name^="observacaoFornecedor"]').attr('name', 'observacaoFornecedor[' + selectElement
                        .value + ']');
                };
            </script>

            <script>
                function mudarStatusNota(id) {
                    if (confirm('Deseja declarar nota fiscal paga pelo cliente?')) {
                        // Preparar os dados que você deseja enviar
                        var dados = {
                            "_token": "{{ csrf_token() }}", // Token CSRF
                            "_method": "PATCH" // Método PATCH
                        };

                        // Enviar a solicitação AJAX
                        $.ajax({
                            type: "POST", // Use POST para enviar o método PATCH
                            url: "/mudar-status-nota/" + id, // URL da rota com o ID
                            data: dados,
                            success: function(data) {
                                // Manipule a resposta se necessário
                                alert('Status da nota fiscal atualizado com sucesso.');
                                // Atualize a página ou faça qualquer outra ação necessária após a atualização
                                window.location.reload();
                            },
                            error: function(error) {
                                // Trate erros se necessário
                                alert('Ocorreu um erro ao atualizar o status da nota fiscal.');
                            }
                        });
                    } else {
                        alert('Nota fiscal continua em aberto.')
                    }
                }
            </script>
            <script>
                function enviarCobranca(id, email) {
                    if (confirm('Deseja enviar um e-mail para ' + email + '?')) {
                        window.location.href = 'mailto:' + email;
                        if (confirm('Você já fez o envio do e-mail?')) {
                            // Preparar os dados que você deseja enviar
                            var dados = {
                                "_token": "{{ csrf_token() }}", // Token CSRF
                                "_method": "POST" // Método PATCH
                            };

                            // Enviar a solicitação AJAX
                            $.ajax({
                                type: "POST", // Use POST para enviar o método PATCH
                                url: "/email-cobranca/" + id, // URL da rota com o ID
                                data: dados,
                                success: function(data) {
                                    // Manipule a resposta se necessário
                                    alert('E-mail enviado com sucesso!');
                                    // Atualize a página ou faça qualquer outra ação necessária após a atualização
                                    window.location.reload();
                                },
                                error: function(error) {
                                    alert('Ocorreu um erro ao atualizar o status da nota fiscal.');
                                }
                            });
                        } else {
                            alert('Nota fiscal continua em aberto.')
                        }
                    } else {
                        alert('Nota fiscal continua em aberto.')
                    }
                }
            </script>
            <script>
                $(document).ready(function() {
                    $('#tipoTransporte').change(function() {
                        var selectedOption = $(this).val();
                        $('#load').show();

                        if (selectedOption === '1') {
                            setTimeout(function() {
                                $('#load').hide();
                                $('#onibusLongaDistanciaSelecionado').hide();
                                $('#onibusCurtaDistanciaSelecionado').hide();
                                $('#uberSelecionado').hide();
                                $('#taxiSelecionado').hide();
                                $('#aviaoSelecionado').show();

                                $('#onibusLongaDistanciaSelecionado :input').val('');
                                $('#onibusCurtaDistanciaSelecionado :input').val('');
                                $('#uberSelecionado :input').val('');
                                $('#taxiSelecionado :input').val('');

                            }, 500);
                        } else if (selectedOption === '2') {
                            setTimeout(function() {
                                $('#load').hide();
                                $('#aviaoSelecionado').hide();
                                $('#onibusCurtaDistanciaSelecionado').hide();
                                $('#uberSelecionado').hide();
                                $('#taxiSelecionado').hide();
                                $('#onibusLongaDistanciaSelecionado').show();

                                $('#aviaoSelecionado :input').val('');
                                $('#onibusCurtaDistanciaSelecionado :input').val('');
                                $('#uberSelecionado :input').val('');
                                $('#taxiSelecionado :input').val('');
                            }, 500);
                        } else if (selectedOption === '3') {
                            setTimeout(function() {
                                $('#load').hide();
                                $('#aviaoSelecionado').hide();
                                $('#onibusLongaDistanciaSelecionado').hide();
                                $('#uberSelecionado').hide();
                                $('#taxiSelecionado').hide();
                                $('#onibusCurtaDistanciaSelecionado').show();

                                $('#aviaoSelecionado :input').val('');
                                $('#onibusLongaDistanciaSelecionado :input').val('');
                                $('#uberSelecionado :input').val('');
                                $('#taxiSelecionado :input').val('');
                            }, 500);
                        } else if (selectedOption === '4') {
                            setTimeout(function() {
                                $('#load').hide();
                                $('#aviaoSelecionado').hide();
                                $('#onibusLongaDistanciaSelecionado').hide();
                                $('#onibusCurtaDistanciaSelecionado').hide();
                                $('#taxiSelecionado').hide();
                                $('#uberSelecionado').show();

                                $('#aviaoSelecionado :input').val('');
                                $('#onibusLongaDistanciaSelecionado :input').val('');
                                $('#onibusCurtaDistanciaSelecionado :input').val('');
                                $('#taxiSelecionado :input').val('');
                            }, 500);
                        } else if (selectedOption === '5') {
                            setTimeout(function() {
                                $('#load').hide();
                                $('#aviaoSelecionado').hide();
                                $('#onibusLongaDistanciaSelecionado').hide();
                                $('#onibusCurtaDistanciaSelecionado').hide();
                                $('#uberSelecionado').hide();
                                $('#taxiSelecionado').show();

                                $('#aviaoSelecionado :input').val('');
                                $('#onibusLongaDistanciaSelecionado :input').val('');
                                $('#onibusCurtaDistanciaSelecionado :input').val('');
                                $('#uberSelecionado :input').val('');
                            }, 500);
                        } else {
                            setTimeout(function() {
                                $('#load').hide();
                                $('#aviaoSelecionado').hide();
                                $('#onibusLongaDistanciaSelecionado').hide();
                                $('#onibusCurtaDistanciaSelecionado').hide();
                                $('#uberSelecionado').hide();
                                $('#taxiSelecionado').hide();

                                $('#aviaoSelecionado :input').val('');
                                $('#onibusLongaDistanciaSelecionado :input').val('');
                                $('#onibusCurtaDistanciaSelecionado :input').val('');
                                $('#uberSelecionado :input').val('');
                                $('#taxiSelecionado :input').val('');
                            }, 500);
                        }
                    });

                    // let suppliers = {{ $suppliers }}

                    // Variáveis para controlar o número máximo de fornecedores e os fornecedores disponíveis
                    let maxFornecedores = {{ $suppliers->count() }};
                    let fornecedoresDisponiveis = {!! json_encode($suppliers) !!};

                    // Função para adicionar uma nova linha de fornecedor
                    let fornecedorNumber = $('.supplier-row').length + 1;

                    function addFornecedorRow() {
                        var fornecedorCount = $('#supplierContainer .form-row').length;

                        // Verifica se já atingiu o limite de fornecedores
                        if (fornecedorCount < maxFornecedores) {
                            var $newRow = $(
                                '<div class="supplier-row">' +
                                '<div class="row mb-3">' +
                                '<div class="col">' +
                                '<span>Fornecedor ' + fornecedorNumber + '</span>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-row row mb-3 align-items-center">' +
                                '<div class="col-lg-4">' +
                                '<div class="form-floating mb-3">' +
                                '<select class="form-control custom-select border-2" onchange="novoValor(this)" name="fornecedor[]">' +
                                '<option value="">Selecione um fornecedor</option>' +
                                '</select>' +
                                '<label for="valorPalestrante">Selecionar fornecedor</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                '<div class="form-floating mb-3">' +
                                '<input inputmode="decimal" type="text" class="form-control border-2 valor-fornecedor" name="valorFornecedor[]" placeholder="Valor em Reais">' +
                                '<label for="valorFornecedor">Valor do fornecedor</label>' +
                                '<div class="invalid-feedback">Valor do fornecedor é obrigatório.</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-3 mb-lg-3">' +
                                '<div class="form-check form-switch">' +
                                '<input class="form-check-input bg-success" name="pagamentoFornecedorEfetuado[]" id="pagamentoFornecedorEfetuado" type="checkbox" /> ' +
                                '<label class="form-check-label" for="pagamentoFornecedorEfetuado">Pagamento efetuado?</label> ' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-2 text-end mb-lg-4">' +
                                '<small>Pagamento não efetuado.</small>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                '<div class="col-12">' +
                                '<div class="form-floating mb-3">' +
                                '<textarea class="form-control border-2" name="observacaoFornecedor[]" id="observacaoFornecedor" type="text" placeholder="Observação" style="height: 10rem;">' +
                                '{{ old('observacaoFornecedor') ? old('observacaoFornecedor') : '' }}' +
                                '</textarea>' +
                                '<label for="observacaoFornecedor">Observação do fornecedor</label>' +
                                '<div class="invalid-feedback">Explique o que foi feito pelo fornecedor</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                '<div class="col-12 text-end">' +
                                '<button type="button" class="btn btn-danger removeSupplier mb-3">Remover fornecedor ' +
                                fornecedorNumber + '</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>');

                            // Adiciona as opções de fornecedores ao select
                            fornecedoresDisponiveis.forEach(function(fornecedor) {
                                $newRow.find(`select[name="fornecedor[]"]`).append(
                                    '<option value="' + fornecedor
                                    .id +
                                    '">' + fornecedor.name + '</option>');
                            });

                            // Adiciona a nova linha ao container
                            $('#supplierContainer').append($newRow);
                            mascararValores();
                        } else {
                            alert('Você atingiu o limite de fornecedores.');
                        }
                        fornecedorNumber++;
                        if (fornecedorNumber === maxFornecedores + 1) {
                            $('#addSupplier').hide();
                        }
                    }

                    if (fornecedorNumber === maxFornecedores + 1) {
                        $('#addSupplier').hide();
                    }

                    // Adiciona um fornecedor quando o botão "Adicionar Fornecedor" é clicado
                    $('#addSupplier').on('click', function() {
                        addFornecedorRow();
                    });

                    // Remove um fornecedor quando o botão "Remover" é clicado
                    $('#supplierContainer').on('click', '.removeSupplier', function() {
                        $(this).closest('.supplier-row').remove();
                        fornecedorNumber--;
                        $('#addSupplier').show();
                    });


                    // Vendedores


                    let maxVendedores = {{ $sellers->count() }};
                    let vendedoresDisponiveis = {!! json_encode($sellers) !!};

                    // Função para adicionar uma nova linha de vendedor
                    function addVendedorRow() {
                        var vendedorCount = $('#sellerContainer .form-row').length;

                        // Verifica se já atingiu o limite de vendedores
                        if (vendedorCount < maxVendedores) {
                            var $newRow = $(
                                '<div class="seller-row">' +
                                '<div class="row mb-3">' +
                                '<div class="col">' +
                                '<span>Vendedor</span>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-row row mb-3 align-items-center">' +
                                '<div class="col-lg-4">' +
                                '<div class="form-floating mb-3">' +
                                '<select class="form-control custom-select border-2" name="vendedor">' +
                                '<option value="">Selecione um vendedor</option>' +
                                '</select>' +
                                '<label for="valorVendedor">Selecionar vendedor</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                '<div class="form-floating mb-3">' +
                                '<input type="text" class="form-control border-2 valor-vendedor" name="valorVendedor" placeholder="Valor em Reais">' +
                                '<label for="valorVendedor">Valor do vendedor</label>' +
                                '<div class="invalid-feedback">Valor do vendedor é obrigatório.</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-3 mb-lg-3">' +
                                '<div class="form-check form-switch">' +
                                '<input class="form-check-input bg-success" name="pagamentoVendedorEfetuado" id="pagamentoVendedorEfetuado" type="checkbox" name="pagamentoVendedorEfetuado" {{ old('pagamentoVendedorEfetuado') ? 'checked' : ($event->number == null || $event->number == '' ? '' : 'checked') }} /> ' +
                                '<label class="form-check-label" for="pagamentoVendedorEfetuado">Pagamento efetuado?</label> ' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-2 text-end mb-lg-4">' +
                                '<small>Pagamento não efetuado.</small>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                '<div class="col-12">' +
                                '<div class="form-floating mb-3">' +
                                '<textarea class="form-control border-2" name="observacaoVendedor" id="observacaoVendedor" type="text" placeholder="Observação" style="height: 10rem;">' +
                                '{{ old('observacaoVendedor') ? old('observacaoVendedor') : '' }}' +
                                '</textarea>' +
                                '<label for="observacaoVendedor">Observação do vendedor</label>' +
                                '<div class="invalid-feedback">Explique o que foi feito pelo vendedor</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                '<div class="col-12 text-end">' +
                                '<button type="button" class="btn btn-danger removeSeller mb-3">Remover vendedor</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>');

                            // Adiciona as opções de vendedores ao select
                            vendedoresDisponiveis.forEach(function(vendedor) {
                                $newRow.find(`select[name="vendedor"]`).append(
                                    '<option value="' + vendedor
                                    .id +
                                    '">' + vendedor.name + '</option>');
                            });

                            // Adiciona a nova linha ao container
                            $('#sellerContainer').append($newRow);
                            mascararValores();
                        } else {
                            alert('Não existem ou não podem ser adicionados vendedores.');
                        }
                        $('#addSeller').hide();
                    }

                    // Adiciona um vendedor quando o botão "Adicionar Vendedor" é clicado
                    $('#addSeller').on('click', function() {
                        addVendedorRow();
                    });

                    // Remove um vendedor quando o botão "Remover" é clicado
                    $('#sellerContainer').on('click', '.removeSeller', function() {
                        $(this).closest('.seller-row').remove();
                        $('#addSeller').show();
                    });



                    // Parceiros

                    let parceirosDisponiveis = {!! json_encode($partners) !!};

                    function addPartnerRow() {
                        var partnerCount = $('#partnerContainer .form-row').length;

                        // Verifica se já atingiu o limite de parceiros
                        if (partnerCount < 1) {
                            var $newRow = $(
                                '<div class="partner-row">' +
                                '<div class="form-row row mb-3 align-items-center">' +
                                '<div class="col-lg-4">' +
                                '<div class="form-floating mb-3">' +
                                '<select class="form-control custom-select border-2" name="parceiro">' +
                                '<option value="">Selecione um parceiro</option>' +
                                '</select>' +
                                '<label for="valorPalestrante">Selecionar parceiro</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-3">' +
                                '<div class="form-floating mb-3">' +
                                '<input type="text" class="form-control border-2" name="valorParceiro" placeholder="Valor em Reais">' +
                                '<label for="valorParceiro">Valor do parceiro</label>' +
                                '<div class="invalid-feedback">Valor do parceiro é obrigatório.</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-3 mb-lg-3">' +
                                '<div class="form-check form-switch">' +
                                '<input class="form-check-input bg-success" name="pagamentoParceiroEfetuado" id="pagamentoParceiroEfetuado" type="checkbox" name="pagamentoParceiroEfetuado" {{ old('pagamentoParceiroEfetuado') ? 'checked' : ($event->number == null || $event->number == '' ? '' : 'checked') }} /> ' +
                                '<label class="form-check-label" for="pagamentoParceiroEfetuado">Pagamento efetuado?</label> ' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-2 text-end mb-lg-4">' +
                                '<small>Pagamento não efetuado.</small>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                '<div class="col-12">' +
                                '<div class="form-floating mb-3">' +
                                '<textarea class="form-control border-2" name="observacaoParceiro" id="observacaoParceiro" type="text" placeholder="Observação" style="height: 10rem;">' +
                                '{{ old('observacaoParceiro') ? old('observacaoParceiro') : $event->partner_observation }}' +
                                '</textarea>' +
                                '<label for="observacaoParceiro">Observação do parceiro</label>' +
                                '<div class="invalid-feedback">Explique o que foi feito pelo parceiro</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                '<div class="col-12 text-end">' +
                                '<button type="button" class="btn btn-danger removePartner mb-3">Remover parceiro</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>');

                            // Adiciona as opções de parceiroes ao select
                            parceirosDisponiveis.forEach(function(parceiro) {
                                $newRow.find('select[name="parceiro"]').append('<option value="' + parceiro.id +
                                    '">' + parceiro.name + '</option>');
                            });

                            // Adiciona a nova linha ao container
                            $('#partnerContainer').append($newRow);
                            $('#addPartner').hide();
                            mascararValores();
                        } else {
                            alert('Você só pode adicionar um parceiro.');
                        }
                    }

                    // Adiciona um parceiro quando o botão "Adicionar parceiro" é clicado
                    $('#addPartner').on('click', function() {
                        addPartnerRow();
                    });

                    // Remove um parceiro quando o botão "Remover" é clicado
                    $('#partnerContainer').on('click', '.removePartner', function() {
                        $(this).closest('.partner-row').remove();
                        $('#addPartner').show();
                    });

                    $(".js-example-basic-multiple").select2({
                        placeholder: "Selecione um ou mais temas",
                    });

                    $(".js-example-basic-multiple-palestrante").select2({
                        placeholder: "Selecione um ou mais palestrantes",
                    });

                    $('.select2-selection--multiple').addClass('form-control border-2 h-100 ps-lg-1')

                    // 

                    let alturaNome = $('#altura').height();
                    $('.select2-container--default').css('height', alturaNome + 'px');


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

                    mascararValores();

                    function mascararValores() {
                        $('#valorTotal, #nossoValor, .valor-palestrante, .valor-fornecedor, .valor-parceiro, .valor-vendedor')
                            .inputmask(
                                'currency', {
                                    'alias': 'numeric',
                                    'groupSeparator': ',',
                                    'autoGroup': true,
                                    'digits': 2,
                                    'radixPoint': '.',
                                    'allowMinus': false,
                                    'prefix': 'R$ ',
                                    'rightAlign': false,
                                    'autoUnmask': true,
                                    'placeholder': '0'
                                }
                            );
                    }

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

                    function atualizarNumeroHospedagem() {
                        const temNumero = $('#temNumeroHospedagem').is(':checked');
                        const numeroInput = $('#numeroHospedagem');

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
                    atualizarNumeroHospedagem();

                    $('#temNumero').change(function() {
                        atualizarNumero();
                    });

                    $('#temNumeroHospedagem').change(function() {
                        atualizarNumeroHospedagem();
                    });

                    //

                    $('#tipoEvento').change(function() {
                        if ($(this).val() === '2') {
                            $('.fields').hide();
                            $('.fields-info').show();
                            $('.fields :input').each(function() {
                                $(this).prop('required', false);
                            });
                        } else {
                            $('.fields').show();
                            $('.fields-info').hide();
                            $('.fields :input').each(function() {
                                $(this).prop('required', true);
                            });
                        }
                    })

                    //

                    function limpa_formulário_cep() {
                        $("#endereco").val("");
                        $("#bairro").val("");
                        $("#cidade").val("");
                        $("#estado").val("");
                    }

                    function limpa_formulário_cep_hospedagem() {
                        $("#enderecoHospedagem").val("");
                        $("#bairroHospedagem").val("");
                        $("#cidadeHospedagem").val("");
                        $("#estadoHospedagem").val("");
                    }


                    $("#cep").blur(function() {
                        let cep = $(this).val().replace(/\D/g, '');

                        if (cep != "") {
                            let validacep = /^[0-9]{8}$/;

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

                    $("#cepHospedagem").blur(function() {
                        let cep = $(this).val().replace(/\D/g, '');

                        if (cep != "") {
                            let validacep = /^[0-9]{8}$/;

                            if (validacep.test(cep)) {

                                $("#enderecoHospedagem").val("...");
                                $("#bairroHospedagem").val("...");
                                $("#cidadeHospedagem").val("...");
                                $("#estadoHospedagem").val("...");

                                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                                    if (!("erro" in dados)) {
                                        $("#enderecoHospedagem").val(dados.logradouro);
                                        $("#bairroHospedagem").val(dados.bairro);
                                        $("#cidadeHospedagem").val(dados.localidade);
                                        $("#estadoHospedagem").val(dados.uf);
                                    } else {
                                        limpa_formulário_cep_hospedagem();
                                        alert("CEP não encontrado.");
                                    }
                                });
                            } else {
                                limpa_formulário_cep_hospedagem();
                                alert("Formato de CEP inválido.");
                            }
                        } else {
                            limpa_formulário_cep_hospedagem();
                        }
                    });
                });
            </script>
        @endsection
