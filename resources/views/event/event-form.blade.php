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
                <h3><strong>Novo evento</strong></h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <form method="POST" action="{{ route('adicionar_evento') }}" id="contactForm" class="needs-validation"
                    novalidate>
                    @csrf
                    <h5 class="text-center mb-5">Informações gerais</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3" id="altura">
                                <input class="form-control border-2" name="nome" id="nome" type="text"
                                    placeholder="Nome" required value="{{ old('nome') }}" />
                                <label for="nome">Nome do evento</label>
                                <div class="invalid-feedback">Nome do evento é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <div class="form-floating mb-3">
                                    <select class="form-select border-2" name="cliente" id="cliente" aria-label="Cliente"
                                        required>
                                        <option {{ old('cliente') ? '' : 'selected' }} value="" disabled>Selecione
                                            um cliente</option>
                                        @foreach ($customers as $customer)
                                            <option {{ old('cliente') == $customer->id ? 'selected' : '' }}
                                                value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
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
                                            {{ in_array($theme->id, old('temas', [])) ? 'selected' : '' }}>
                                            {{ $theme->theme }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select js-example-basic-multiple-palestrante custom-select"
                                    name="palestrantes[]" id="palestrantes" aria-label="Tema" required multiple="multiple">
                                    @foreach ($speakers as $speaker)
                                        <option value="{{ $speaker->id }}"
                                            {{ in_array($speaker->id, old('palestrantes', [])) ? 'selected' : '' }}>
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
                                <input class="form-control border-2" name="dataHora" id="dataHora" type="datetime-local"
                                    placeholder="Data e Hora do evento" value="{{ old('dataHora') }}" />
                                <label for="dataHora">Data e hora do evento</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="tipoEvento" id="tipoEvento"
                                    aria-label="Tipo do evento" required>
                                    <option {{ old('tipoEvento') ? '' : 'selected' }} value="" disabled>
                                        Selecione um tipo de evento
                                    </option>
                                    <option {{ old('tipoEvento') == 1 ? 'selected' : '' }} value="1">Presencial
                                    </option>
                                    <option {{ old('tipoEvento') == 2 ? 'selected' : '' }} value="2">Remoto
                                    </option>
                                    <option {{ old('tipoEvento') == 3 ? 'selected' : '' }} value="3">Híbrido
                                    </option>
                                </select>
                                <label for="tipoEvento">Tipo do evento (presencial, remoto ou híbrido)</label>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Informações de endereço</h5>
                    <div class="fields-info" style="display: none;">
                        <div class="row justify-content-center">
                            <div class="coltext-center">
                                <p>Endereço não é necessário quando um evento é remoto.</p>
                            </div>
                        </div>
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="cep" id="cep" type="text"
                                        placeholder="CEP" maxlength="9" required value="{{ old('cep') }}" />
                                    <label for="cep">CEP</label>
                                    <div class="invalid-feedback">CEP é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="endereco" id="endereco" type="text"
                                        placeholder="Endereço" required value="{{ old('endereco') }}" />
                                    <label for="endereco">Endereço</label>
                                    <div class="invalid-feedback">Endereço é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="bairro" id="bairro" type="text"
                                        placeholder="Bairro" required value="{{ old('bairro') }}" />
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
                                                    {{ old('temNumero') ? 'checked' : '' }} />
                                                <label class="form-check-label" for="temNumero">Tem <br>numero?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control border-2" name="numero" id="numero"
                                                type="number" placeholder="Número" value="{{ old('numero') }}"
                                                required />
                                            <label for="numero">Número</label>
                                            <div class="invalid-feedback">Número é obrigatório.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="complemento" id="complemento"
                                        type="text" placeholder="Complemento" value="{{ old('complemento') }}" />
                                    <label for="complemento">Complemento</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-floating mb-3">
                                    <input class="form-control border-2" name="cidade" id="cidade" type="text"
                                        placeholder="Cidade" required value="{{ old('cidade') }}" />
                                    <label for="cidade">Cidade</label>
                                    <div class="invalid-feedback">Cidade é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-floating mb-3">
                                    <select class="form-select border-2" name="estado" id="estado"
                                        aria-label="Estado" required>
                                        <option {{ old('estado') ? '' : 'selected' }} value="" disabled>Selecione
                                        </option>
                                        <option {{ old('estado') == 'AC' ? 'selected' : '' }} value="AC">Acre</option>
                                        <option {{ old('estado') == 'AL' ? 'selected' : '' }} value="AL">Alagoas
                                        </option>
                                        <option {{ old('estado') == 'AP' ? 'selected' : '' }} value="AP">Amapá
                                        </option>
                                        <option {{ old('estado') == 'AM' ? 'selected' : '' }} value="AM">Amazonas
                                        </option>
                                        <option {{ old('estado') == 'BA' ? 'selected' : '' }} value="BA">Bahia
                                        </option>
                                        <option {{ old('estado') == 'CE' ? 'selected' : '' }} value="CE">Ceará
                                        </option>
                                        <option {{ old('estado') == 'DF' ? 'selected' : '' }} value="DF">Distrito
                                            Federal
                                        </option>
                                        <option {{ old('estado') == 'ES' ? 'selected' : '' }} value="ES">Espírito
                                            Santo
                                        </option>
                                        <option {{ old('estado') == 'GO' ? 'selected' : '' }} value="GO">Goiás
                                        </option>
                                        <option {{ old('estado') == 'MA' ? 'selected' : '' }} value="MA">Maranhão
                                        </option>
                                        <option {{ old('estado') == 'MT' ? 'selected' : '' }} value="MT">Mato Grosso
                                        </option>
                                        <option {{ old('estado') == 'MS' ? 'selected' : '' }} value="MS">Mato Grosso
                                            do
                                            Sul</option>
                                        <option {{ old('estado') == 'MG' ? 'selected' : '' }} value="MG">Minas Gerais
                                        </option>
                                        <option {{ old('estado') == 'PA' ? 'selected' : '' }} value="PA">Pará</option>
                                        <option {{ old('estado') == 'PB' ? 'selected' : '' }} value="PB">Paraíba
                                        </option>
                                        <option {{ old('estado') == 'PR' ? 'selected' : '' }} value="PR">Paraná
                                        </option>
                                        <option {{ old('estado') == 'PE' ? 'selected' : '' }} value="PE">Pernambuco
                                        </option>
                                        <option {{ old('estado') == 'PI' ? 'selected' : '' }} value="PI">Piauí
                                        </option>
                                        <option {{ old('estado') == 'RJ' ? 'selected' : '' }} value="RJ">Rio de
                                            Janeiro
                                        </option>
                                        <option {{ old('estado') == 'RN' ? 'selected' : '' }} value="RN">Rio Grande do
                                            Norte</option>
                                        <option {{ old('estado') == 'RS' ? 'selected' : '' }} value="RS">Rio Grande do
                                            Sul
                                        </option>
                                        <option {{ old('estado') == 'RO' ? 'selected' : '' }} value="RO">Rondônia
                                        </option>
                                        <option {{ old('estado') == 'RR' ? 'selected' : '' }} value="RR">Roraima
                                        </option>
                                        <option {{ old('estado') == 'SC' ? 'selected' : '' }} value="SC">Santa
                                            Catarina
                                        </option>
                                        <option {{ old('estado') == 'SP' ? 'selected' : '' }} value="SP">São Paulo
                                        </option>
                                        <option {{ old('estado') == 'SE' ? 'selected' : '' }} value="SE">Sergipe
                                        </option>
                                        <option {{ old('estado') == 'TO' ? 'selected' : '' }} value="TO">Tocantins
                                        </option>
                                    </select>
                                    <label for="estado">Estado</label>
                                    <div class="invalid-feedback">Estado é obrigatório.</div>
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
                                    style="height: 10rem;">{{ old('observacao') }}</textarea>
                                <label for="observacao">Observação sobre o evento</label>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg" id="submitButton" type="submit">Adicionar novo
                            evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
    <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".js-example-basic-multiple").select2({
                placeholder: "Selecione um ou mais temas",
            });

            $(".js-example-basic-multiple-palestrante").select2({
                placeholder: "Selecione um ou mais palestrantes",
            });

            $('.select2-selection--multiple').addClass('form-control border-2 h-100 ps-lg-1')

            // 

            var alturaNome = $('#altura').height();
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

            $('#tipoEvento').change(function() {
                if ($(this).val() === '2') {
                    $('.fields').hide();
                    $('.fields-info').show();
                    $('.fields :input').each(function() {
                        if (this.id !== 'complemento') {
                            // console.log(this.id + ' 1')
                            $(this).prop('required', false);
                        }
                    });
                } else {
                    $('.fields').show();
                    $('.fields-info').hide();
                    $('.fields :input').each(function() {
                        // console.log()
                        if (this.id !== 'temNumero' && this.id !== 'complemento' && this.id !==
                            'numero') {
                            $(this).prop('required', true);
                        }
                    });
                }
            });


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
                                $("#estado").val(dados.uf).trigger('input');
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
