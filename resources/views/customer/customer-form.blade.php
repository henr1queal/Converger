@extends('layouts/layout')
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
                <h3><strong>Novo cliente</strong></h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <form method="POST" action="{{ route('adicionar_cliente') }}" id="contactForm" class="needs-validation"
                    novalidate>
                    @csrf
                    <h5 class="text-center mb-5">Informações gerais</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="nome" id="nome" required type="text"
                                    placeholder="Nome" value="{{ old('nome') }}" />
                                <label for="nome">Nome</label>
                                <div class="invalid-feedback">Nome do cliente é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="razaoSocial" id="razaoSocial" type="text"
                                    placeholder="Razão social" value="{{ old('razaoSocial') }}" />
                                <label for="razaoSocial">Razão social</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cnpj" id="cnpj" type="text"
                                    placeholder="CNPJ" value="{{ old('cnpj') }}" />
                                <label for="cnpj">CNPJ</label>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="estruturaLegalDaOrganizacao"
                                    id="estruturaLegalDaOrganizacao" aria-label="Estrutura legal da organização">
                                    <option {{ old('estruturaLegalDaOrganizacao') ? '' : 'selected' }} value=""
                                        disabled>Selecione</option>
                                    <option {{ old('estruturaLegalDaOrganizacao') == 1 ? 'selected' : '' }} value="1">
                                        Microempreendedor Individual (MEI)</option>
                                    <option {{ old('estruturaLegalDaOrganizacao') == 2 ? 'selected' : '' }} value="2">
                                        Microempresa (ME)</option>
                                    <option {{ old('estruturaLegalDaOrganizacao') == 3 ? 'selected' : '' }} value="3">
                                        Empresa de Pequeno Porte (EPP)</option>
                                    <option {{ old('estruturaLegalDaOrganizacao') == 4 ? 'selected' : '' }} value="4">
                                        Média empresa</option>
                                    <option {{ old('estruturaLegalDaOrganizacao') == 5 ? 'selected' : '' }} value="5">
                                        Grande empresa</option>
                                </select>
                                <label for="estruturaLegalDaOrganizacao">Estrutura legal da organização</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="inscMunicipal" id="inscMunicipal" type="text"
                                    placeholder="Insc. Municipal" value="{{ old('inscMunicipal') }}" />
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
                                    placeholder="CEP" maxlength="9" value="{{ old('cep') }}" />
                                <label for="cep">CEP</label>
                                <div class="invalid-feedback">CEP é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="endereco" id="endereco" type="text"
                                    placeholder="Endereço" value="{{ old('endereco') }}" />
                                <label for="endereco">Endereço</label>
                                <div class="invalid-feedback">Endereço é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="bairro" id="bairro" type="text"
                                    placeholder="Bairro" value="{{ old('bairro') }}" />
                                <label for="bairro">Bairro</label>
                                <div class="invalid-feedback">Bairro é obrigatório.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="row">
                                <div class="col-lg-6 mt-lg-2">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input mt-3 bg-success" name="temNumero"
                                                id="temNumero" type="checkbox" name="temNumero"
                                                {{ old('numero') ? 'checked' : '' }} />
                                            <label class="form-check-label" for="temNumero">Tem <br>numero?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="numero" id="numero"
                                            type="number" placeholder="Número" value="{{ old('numero') }}" />
                                        <label for="numero">Número</label>
                                        <div class="invalid-feedback">Número é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="complemento" id="complemento" type="text"
                                    placeholder="Complemento" value="{{ old('complemento') }}" />
                                <label for="complemento">Complemento</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="cidade" id="cidade" type="text"
                                    placeholder="Cidade" value="{{ old('cidade') }}" />
                                <label for="cidade">Cidade</label>
                                <div class="invalid-feedback">Cidade é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="estado" id="estado" aria-label="Estado">
                                    <option {{ old('estado') ? '' : 'selected' }} value="" disabled>Selecione
                                    </option>
                                    <option {{ old('estado') == 'AC' ? 'selected' : '' }} value="AC">Acre</option>
                                    <option {{ old('estado') == 'AL' ? 'selected' : '' }} value="AL">Alagoas</option>
                                    <option {{ old('estado') == 'AP' ? 'selected' : '' }} value="AP">Amapá</option>
                                    <option {{ old('estado') == 'AM' ? 'selected' : '' }} value="AM">Amazonas</option>
                                    <option {{ old('estado') == 'BA' ? 'selected' : '' }} value="BA">Bahia</option>
                                    <option {{ old('estado') == 'CE' ? 'selected' : '' }} value="CE">Ceará</option>
                                    <option {{ old('estado') == 'DF' ? 'selected' : '' }} value="DF">Distrito Federal
                                    </option>
                                    <option {{ old('estado') == 'ES' ? 'selected' : '' }} value="ES">Espírito Santo
                                    </option>
                                    <option {{ old('estado') == 'GO' ? 'selected' : '' }} value="GO">Goiás</option>
                                    <option {{ old('estado') == 'MA' ? 'selected' : '' }} value="MA">Maranhão</option>
                                    <option {{ old('estado') == 'MT' ? 'selected' : '' }} value="MT">Mato Grosso
                                    </option>
                                    <option {{ old('estado') == 'MS' ? 'selected' : '' }} value="MS">Mato Grosso do
                                        Sul</option>
                                    <option {{ old('estado') == 'MG' ? 'selected' : '' }} value="MG">Minas Gerais
                                    </option>
                                    <option {{ old('estado') == 'PA' ? 'selected' : '' }} value="PA">Pará</option>
                                    <option {{ old('estado') == 'PB' ? 'selected' : '' }} value="PB">Paraíba</option>
                                    <option {{ old('estado') == 'PR' ? 'selected' : '' }} value="PR">Paraná</option>
                                    <option {{ old('estado') == 'PE' ? 'selected' : '' }} value="PE">Pernambuco
                                    </option>
                                    <option {{ old('estado') == 'PI' ? 'selected' : '' }} value="PI">Piauí</option>
                                    <option {{ old('estado') == 'RJ' ? 'selected' : '' }} value="RJ">Rio de Janeiro
                                    </option>
                                    <option {{ old('estado') == 'RN' ? 'selected' : '' }} value="RN">Rio Grande do
                                        Norte</option>
                                    <option {{ old('estado') == 'RS' ? 'selected' : '' }} value="RS">Rio Grande do Sul
                                    </option>
                                    <option {{ old('estado') == 'RO' ? 'selected' : '' }} value="RO">Rondônia</option>
                                    <option {{ old('estado') == 'RR' ? 'selected' : '' }} value="RR">Roraima</option>
                                    <option {{ old('estado') == 'SC' ? 'selected' : '' }} value="SC">Santa Catarina
                                    </option>
                                    <option {{ old('estado') == 'SP' ? 'selected' : '' }} value="SP">São Paulo
                                    </option>
                                    <option {{ old('estado') == 'SE' ? 'selected' : '' }} value="SE">Sergipe</option>
                                    <option {{ old('estado') == 'TO' ? 'selected' : '' }} value="TO">Tocantins
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
                                <input class="form-control border-2" name="emailPrincipal" id="emailPrincipal"
                                    type="email" placeholder="E-mail principal" required
                                    value="{{ old('emailPrincipal') }}" />
                                <label for="emailPrincipal">E-mail principal</label>
                                <div class="invalid-feedback">E-mail principal é obrigatório.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="telefonePrincipal" id="telefonePrincipal"
                                    type="text" placeholder="Telefone principal"
                                    value="{{ old('telefonePrincipal') }}" />
                                <label for="telefonePrincipal">Telefone principal</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="celularPrincipal" id="celularPrincipal"
                                    type="text" placeholder="Celular principal"
                                    value="{{ old('celularPrincipal') }}" />
                                <label for="celularPrincipal">Celular principal</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="responsavelPeloPagamento"
                                    id="responsavelPeloPagamento" type="text" placeholder="Responsável pelo pagamento"
                                    value="{{ old('responsavelPeloPagamento') }}" />
                                <label for="responsavelPeloPagamento">Responsável pelo pagamento</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="telefoneDoResponsavel"
                                    id="telefoneDoResponsavel" type="text" placeholder="Telefone do responsável"
                                    value="{{ old('telefoneDoResponsavel') }}" />
                                <label for="telefoneDoResponsavel">Telefone do responsável</label>
                                <div class="invalid-feedback">Telefone do responsável é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none invisible">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="origemDoCliente" id="origemDoCliente"
                                    aria-label="Origem do cliente">
                                    <option {{ old('origemDoCliente' ? '' : 'selected') }} value="0">Manual</option>
                                    <option {{ old('origemDoCliente' === 1 ? 'selected' : '') }} value="1">Outro
                                    </option>
                                </select>
                                <label for="origemDoCliente">Origem do cliente</label>
                                <div class="invalid-feedback">Origem do cliente é obrigatório.</div>
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
                                <label for="observacao">Observação</label>
                            </div>
                        </div>
                    </div>
                    {{-- <hr class="my-3 pb-3 border-3"> --}}
                    <h5 class="text-center mt-4 mb-5">Informações</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="banco" id="banco" aria-label="Banco">
                                    <option value="" disabled {{ old('banco') ? '' : 'selected' }}>
                                        Selecione um banco</option>
                                    @foreach ($banks as $bank)
                                        <option {{ old('banco') === $bank->id ? 'selected' : '' }}
                                            value="{{ $bank->id }}">
                                            {{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                <label for="banco">Banco</label>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="agencia" id="agencia" type="text"
                                    placeholder="Agência" value="{{ old('agencia') }}" />
                                <label for="agencia">Agência</label>
                                <div class="invalid-feedback">Agência é obrigatória.</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="conta" id="conta" type="text"
                                    placeholder="Conta" value="{{ old('conta') }}" />
                                <label for="conta">Conta</label>
                                <div class="invalid-feedback">Conta é obrigatório.</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input class="form-control border-2" name="chavePix" id="chavePix" type="text"
                                    placeholder="Chave Pix" value="{{ old('chavePix') }}" />
                                <label for="chavePix">Chave Pix</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating mb-3">
                                <select class="form-select border-2" name="condicoesDePagamento"
                                    id="condicoesDePagamento" aria-label="Condições de pagamento">
                                    <option {{ old('condicoesDePagamento') === 1 ? 'selected' : '' }} value="1">7
                                        dias
                                    </option>
                                    <option {{ old('condicoesDePagamento') === 2 ? 'selected' : '' }} value="2">14
                                        dias
                                    </option>
                                    <option {{ old('condicoesDePagamento') === 3 ? 'selected' : 'selected' }}
                                        value="3">30
                                        dias</option>
                                    <option {{ old('condicoesDePagamento') === 4 ? 'selected' : '' }} value="4">60
                                        dias
                                    </option>
                                    <option {{ old('condicoesDePagamento') === 5 ? 'selected' : '' }} value="5">90
                                        dias
                                    </option>
                                    <option {{ old('condicoesDePagamento') === 6 ? 'selected' : '' }} value="6">180
                                        dias
                                    </option>
                                    <option {{ old('condicoesDePagamento') === 7 ? 'selected' : '' }} value="7">
                                        Customizado
                                    </option>
                                </select>
                                <label for="condicoesDePagamento">Condições de pagamento</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="submitSuccessMessage">
                        <div class="text-center mb-3">
                            <div class="fw-bolder">Novo cliente adicionado com sucesso!</div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg" id="submitButton" type="submit">Adicionar novo
                            cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
    <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
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
