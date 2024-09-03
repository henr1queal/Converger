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
                <h3><strong>Gerenciar categorias de fornecedores</strong></h3>
            </div>
        </div>
        <div class="card pt-2 py-lg-4 px-2 border-3">
            <div class="card-body">
                <form method="POST" action="{{ route('adicionar_categoria') }}" class="needs-validation" novalidate>
                    @csrf
                    <h5 class="text-center mb-5">Informações gerais</h5>
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col">
                                    <div class="form-floating mb-3">
                                        <input class="form-control border-2" name="nome" id="nome" type="text"
                                            placeholder="Nome" required value="{{ old('nome') }}" />
                                        <label for="nome">Nome da categoria</label>
                                        <div class="invalid-feedback">Nome do fornecedor é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-success btn-lg" id="submitButton" type="submit">Adicionar nova
                                    categoria</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row mt-5 mt-lg-5 justify-content-center">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col text-center">
                                <h5>Todas as categorias:</h5>
                                @if ($categories->count() > 0)
                                    <table class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Categoria</th>
                                                <th>Deletar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr data-href="{{ route('deletar_categoria', $category->id) }}"
                                                    style="cursor: pointer">
                                                    <td>{{ $category->name }}</td>
                                                    <td>
                                                        <button data-category-id="{{ $category->id }}"
                                                            onclick="nameCategory({{ $category->id }}, '{{ $category->name }}')"
                                                            type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteModal">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>Ainda não temos categorias. Cadastre uma no formulário acima.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Rejeição</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    Tem certeza de que deseja rejeitar e excluir <span class="nameCategory"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('deletar_categoria', 1) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-body')
    <script src="{{ asset('../resources/js/jquery.inputmask.min.js') }}"></script>
    <script>
        function nameCategory(id, name, choose) {
            selectedUserId = id;
            $('.nameCategory').text('');
            $('.nameCategory').text(name);
            $('#confirmDeleteModal form').attr('action',
                `{{ route('deletar_categoria', '') }}/${id}`);
        }
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
