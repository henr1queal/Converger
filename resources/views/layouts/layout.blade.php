<!doctype html>
<html lang="pt" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Converger Gerenciamento</title>
    <link href="{{ asset('../resources/css/bootstrap.min.css') }}" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('../resources/icons/icons-1.11.0/font/bootstrap-icons.css') }}">
    <style>
        label {
            color: #ffffff !important;
        }

        .container {
            max-width: 1600px;
        }

        :root {
            --font-family-sans-serif: "Open Sans", -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji",
                "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        *,
        *::before,
        *::after {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        nav {
            display: block;
        }

        body {
            margin: 0;
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI",
                Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji",
                "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            /* color: #515151; */
            text-align: left;
            /* background-color: #e9edf4; */
        }

        .select2-results {
            color: black !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        a {
            color: #3f84fc;
            text-decoration: none;
            background-color: transparent;
        }

        a:hover {
            color: #0458eb;
            text-decoration: underline;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-family: "Nunito", sans-serif;
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h1,
        .h1 {
            font-size: 2.5rem;
            font-weight: normal;
        }

        .card {
            position: relative;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            /* background-color: #fff; */
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0;
        }

        .card-body {
            -webkit-box-flex: 1;
            -webkit-flex: 1 1 auto;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            text-align: center;
        }

        .dashboard {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            min-height: 100vh;
        }

        .dashboard-app {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-flex: 2;
            -webkit-flex-grow: 2;
            -ms-flex-positive: 2;
            flex-grow: 2;
        }

        .dashboard-content {
            -webkit-box-flex: 2;
            -webkit-flex-grow: 2;
            -ms-flex-positive: 2;
            flex-grow: 2;
            padding: 25px;
        }

        .dashboard-nav {
            background-color: #000000 !important;
            min-width: 300px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow: auto;
            background-color: #373193;
            overflow-x: hidden;
        }

        .dashboard-compact .dashboard-nav {
            display: none;
        }

        .dashboard-nav header {
            min-height: 54px;
            padding: 8px 27px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            -webkit-box-align: center;
            -webkit-align-items: end;
            -ms-flex-align: end;
            align-items: end;
        }

        .dashboard-nav header .menu-toggle {
            display: none;
            margin-right: auto;
        }

        .dashboard-nav a {
            color: #515151;
        }

        .dashboard-nav a:hover {
            text-decoration: none;
        }

        .dashboard-nav a {
            color: #fff;
        }

        .brand-logo {
            font-family: "Nunito", sans-serif;
            font-weight: bold;
            font-size: 20px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            color: #515151;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .brand-logo:focus,
        .brand-logo:active,
        .brand-logo:hover {
            color: #dbdbdb;
            text-decoration: none;
        }

        .brand-logo i {
            color: #d2d1d1;
            font-size: 27px;
            margin-right: 10px;
        }

        .dashboard-nav-list {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .dashboard-nav-item {
            min-height: 56px;
            padding: 8px 20px 8px 70px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            letter-spacing: 0.02em;
            transition: ease-out 0.5s;
        }

        .dashboard-nav-item i {
            width: 36px;
            font-size: 19px;
            margin-left: -40px;
        }

        .dashboard-nav-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .active {
            background: rgba(0, 0, 0, 0.1);
        }

        .dashboard-nav-dropdown {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .dashboard-nav-dropdown.show {
            background: rgba(255, 255, 255, 0.04);
        }

        .dashboard-nav-dropdown.show>.dashboard-nav-dropdown-toggle {
            font-weight: bold;
        }

        .dashboard-nav-dropdown.show>.dashboard-nav-dropdown-toggle:after {
            -webkit-transform: none;
            -o-transform: none;
            transform: none;
        }

        .dashboard-nav-dropdown.show>.dashboard-nav-dropdown-menu {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
        }

        .dashboard-nav-dropdown-toggle:after {
            content: "";
            margin-left: auto;
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid rgba(81, 81, 81, 0.8);
            -webkit-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .dashboard-nav .dashboard-nav-dropdown-toggle:after {
            border-top-color: rgba(255, 255, 255, 0.72);
        }

        .dashboard-nav-dropdown-menu {
            display: none;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .dashboard-nav-dropdown-item {
            min-height: 40px;
            padding: 8px 20px 8px 70px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            transition: ease-out 0.5s;
        }

        .dashboard-nav-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .menu-toggle {
            position: relative;
            width: 42px;
            height: 42px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            color: #443ea2;
        }

        .menu-toggle:hover,
        .menu-toggle:active,
        .menu-toggle:focus {
            text-decoration: none;
            color: #875de5;
        }

        .menu-toggle i {
            font-size: 20px;
        }

        .nav-item-divider {
            height: 1px;
            margin: 1rem 0;
            overflow: hidden;
            background-color: rgba(236, 238, 239, 0.3);
        }

        @media (min-width: 992px) {
            .dashboard-app {
                margin-left: 300px;
            }

            .dashboard-compact .dashboard-app {
                margin-left: 0;
            }
        }


        @media (max-width: 768px) {
            .dashboard-content {
                padding: 15px 0px;
            }
        }

        @media (max-width: 992px) {
            .dashboard-nav {
                display: none;
                position: fixed;
                top: 0;
                right: 0;
                left: 0;
                bottom: 0;
                z-index: 1070;
            }

            .dashboard-nav.mobile-show {
                display: block;
            }
        }

        @media (max-width: 992px) {
            .dashboard-nav header .menu-toggle {
                display: -webkit-box;
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
            }
        }

        @media (min-width: 1400px) {
            .sandwich {
                position: absolute;
                top: 0;
                left: 0;
            }
        }

        @media (min-width: 992px) {
            .sandwich {
                position: absolute;
                top: -17px;
                left: -10px;
            }
        }

        .alert {
            width: 882px;
            margin: 0 auto;
        }

        .bi-exclamation-circle {
            color: #fd7e14;
        }
    </style>
    @yield('script-head')
</head>

<body>
    <div class="dashboard {{ !isset($_COOKIE['menu']) ? '' : 'dashboard-compact' }}">
        <div class="dashboard-nav">
            <header class="row justify-content-between align-items-center">
                <div class="col">
                    <a href="#" class="brand-logo"><span>Converger</span></a>
                </div>
                <div class="col text-end pe-0 ">
                    <a href="#!" class="menu-toggle mx-0 ms-auto justify-content-end"><i
                            class="bi bi-list text-light" style="font-size: 1.8rem" id="fechar"></i></a>
                </div>
            </header>
            <nav class="dashboard-nav-list">
                @php
                    $permissionsByName = $userPermissions->keyBy('name');
                @endphp
                @if ($userCeo || $permissionsByName->has('estatisticas'))
                    <a href="{{ route('estatisticas') }}" class="dashboard-nav-item"><i class="bi bi-graph-up"></i></i>
                        Estatísticas</a>
                @endif
                @if ($userCeo || $permissionsByName->has('meus_clientes'))
                    <div class="dashboard-nav-dropdown">
                        <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                                class="bi bi-building"></i>
                            Cliente</a>
                        <div class='dashboard-nav-dropdown-menu'>
                            @if ($userCeo || $permissionsByName->has('adicionar_cliente'))
                                <a href="{{ route('novo_cliente') }}" class="dashboard-nav-dropdown-item"><i
                                        class="me-2 bi bi-plus-lg" style="font-size: 1rem"></i> Adicionar clientes</a>
                            @endif
                            <a href="{{ route('meus_clientes') }}" class="dashboard-nav-dropdown-item"><i
                                    class="me-2 bi bi-eye" style="font-size: 1rem"></i> Nossos clientes</a>
                        </div>
                    </div>
                @endif
                @if ($userCeo || $permissionsByName->has('meus_fornecedores'))
                    <div class="dashboard-nav-dropdown">
                        <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                                class="bi bi-box-seam"></i>
                            Fornecedor</a>
                        <div class='dashboard-nav-dropdown-menu'>
                            @if ($userCeo || $permissionsByName->has('novo_fornecedor'))
                                <a href="{{ route('novo_fornecedor') }}" class="dashboard-nav-dropdown-item"><i
                                        class="me-2 bi bi-plus-lg" style="font-size: 1rem"></i> Adicionar
                                    fornecedores</a>
                                <a href="{{ route('meus_fornecedores') }}" class="dashboard-nav-dropdown-item"><i
                                        class="me-2 bi bi-eye" style="font-size: 1rem"></i> Nossos
                                    fornecedores</a>
                            @endif
                            @if ($userCeo || $permissionsByName->has('categorias'))
                                <a href="{{ route('categorias') }}" class="dashboard-nav-dropdown-item"><i
                                        class="bi bi-bookmarks me-2" style="font-size: 1rem;"></i> Gerenciar
                                    categorias</a>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($userCeo || $permissionsByName->has('meus_parceiros'))
                    <div class="dashboard-nav-dropdown">
                        <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                                class="bi bi-p-square"></i>
                            Parceiro</a>
                        <div class='dashboard-nav-dropdown-menu'>
                            @if ($userCeo || $permissionsByName->has('novo_parceiro'))
                                <a href="{{ route('novo_parceiro') }}" class="dashboard-nav-dropdown-item"><i
                                        class="me-2 bi bi-plus-lg" style="font-size: 1rem"></i> Adicionar
                                    parceiros</a>
                            @endif
                            <a href="{{ route('meus_parceiros') }}" class="dashboard-nav-dropdown-item"><i
                                    class="me-2 bi bi-eye" style="font-size: 1rem"></i> Nossos parceiros</a>
                        </div>
                    </div>
                @endif
                @if ($userCeo || $permissionsByName->has('meus_palestrantes'))
                    <div class="dashboard-nav-dropdown">
                        <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                                class="bi bi-mic"></i>
                            Palestrante</a>
                        <div class='dashboard-nav-dropdown-menu'>
                            @if ($userCeo || $permissionsByName->has('novo_palestrante'))
                                <a href="{{ route('novo_palestrante') }}" class="dashboard-nav-dropdown-item"><i
                                        class="me-2 bi bi-plus-lg" style="font-size: 1rem"></i> Adicionar
                                    palestrantes</a>
                            @endif
                            <a href="{{ route('meus_palestrantes') }}" class="dashboard-nav-dropdown-item"><i
                                    class="me-2 bi bi-eye" style="font-size: 1rem"></i> Nossos
                                palestrantes</a>
                        </div>
                    </div>
                @endif
                @if ($userCeo || $permissionsByName->has('meus_eventos'))
                    <div class="dashboard-nav-dropdown">
                        <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                                class="bi bi-geo-fill"></i>
                            Evento
                            @if (count($eventsExpiredIds) > 0)
                                <i class="bi bi-exclamation-circle ms-2" style="font-size: 0.9rem; color: #fd7e14."></i>
                            @elseif(count($eventsFutureIds) > 0)
                                <i class="bi bi-exclamation-circle ms-2"
                                    style="font-size: 0.9rem; color: rgb(94, 216, 238);"></i>
                            @endif
                        </a>
                        <div class='dashboard-nav-dropdown-menu'>
                            @if ($userCeo || $permissionsByName->has('novo_evento'))
                                <a href="{{ route('novo_evento') }}" class="dashboard-nav-dropdown-item"><i
                                        class="me-2 bi bi-plus-lg" style="font-size: 1rem"></i> Adicionar
                                    evento</a>
                            @endif
                            <a href="{{ route('meus_eventos') }}" class="dashboard-nav-dropdown-item"><i
                                    class="me-2 bi bi-eye" style="font-size: 1rem"></i> Nossos
                                eventos @if (count($eventsExpiredIds) > 0)
                                    <i class="bi bi-exclamation-circle ms-2"
                                        style="font-size: 0.9rem; color: #fd7e14;"></i>
                                @elseif(count($eventsFutureIds) > 0)
                                    <i class="bi bi-exclamation-circle ms-2"
                                        style="font-size: 0.9rem; color: rgb(94, 216, 238);"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                @endif
                @if ($userCeo || $permissionsByName->has('meus_colaboradores'))
                    <div class="dashboard-nav-dropdown">
                        <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                                class="bi bi-person"></i>
                            Colaborador</a>
                        <div class='dashboard-nav-dropdown-menu'>
                            <a href="{{ route('meus_colaboradores') }}" class="dashboard-nav-dropdown-item"><i
                                    class="me-2 bi bi-eye" style="font-size: 1rem"></i> Nossos
                                colaboradores</a>
                            @if ($userCeo || $permissionsByName->has('meus_colaboradores_pendentes'))
                                <a href="{{ route('meus_colaboradores_pendentes') }}"
                                    class="dashboard-nav-dropdown-item"><i class="bi bi-person-exclamation me-2"></i>
                                    Cadastros pendentes</a>
                            @endif
                        </div>
                    </div>
                @endif
                {{-- @if ($userCeo || $permissionsByName->has('meet'))
                    <a href="#" class="dashboard-nav-item"><i class="bi bi-webcam"></i>
                        Reunião</a>
                @endif --}}
                @if ($userCeo || $permissionsByName->has('meu_financeiro'))
                    <a href="{{ route('meu_financeiro') }}" class="dashboard-nav-item"><i
                            class="bi bi-piggy-bank"></i>
                        Financeiro</a>
                @endif
                <div class="dashboard-nav-dropdown">
                    <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i
                            class="bi bi-person-square" style="font-size: 1rem"></i> Perfil</a>
                    <div class="dashboard-nav-dropdown-menu">
                        <a href="./user/profile" class="dashboard-nav-dropdown-item"><i class="bi bi-lock me-2"
                                style="font-size: 1rem"></i> Configurações de segurança</a>
                    </div>
                </div>
                <div class="nav-item-divider"></div>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <a href="#" class="dashboard-nav-item"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="bi bi-power"></i> Deslogar
                    </a>
                </form>
                <div class="mt-4 d-block mx-auto"><small>Horário atual:</small>
                    <br>
                    <small id="data-e-hora"></small>
                </div>
            </nav>
        </div>
        <div class='dashboard-app'>
            <div class='dashboard-content position-relative'>
                <a href="#!" class="menu-toggle sandwich mx-auto mb-4 mb-lg-0 ms-lg-5 mt-lg-5"><i
                        class="bi bi-list text-light" style="font-size: 3rem" id="abrir"></i></a>
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('../resources/js/jquery-3.7.1.min.js') }}"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('../resources/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script>
        const screenWidth = window.innerWidth;

        $('#abrir').click(function() {
            if (screenWidth < 992) {
                $('body, html').css('overflow-y', 'hidden');
            }
        })
        $('#fechar').click(function() {
            if (screenWidth < 992) {
                $('body, html').css('overflow-y', 'auto');
            }
        })
    </script>
    <script>
        const mobileScreen = window.matchMedia("(max-width: 990px )");
        $(document).ready(function() {
            $(".dashboard-nav-dropdown-toggle").click(function() {
                $(this).closest(".dashboard-nav-dropdown")
                    .toggleClass("show")
                    .find(".dashboard-nav-dropdown")
                    .removeClass("show");
                $(this).parent()
                    .siblings()
                    .removeClass("show");
            });
            $(".menu-toggle").click(function() {
                if (mobileScreen.matches) {
                    $(".dashboard-nav").toggleClass("mobile-show");
                } else {
                    $(".dashboard").toggleClass("dashboard-compact");
                }
            });

            $('#abrir').click(function() {
                var nomeDoCookie = "menu";

                // Função para verificar se um cookie existe
                function cookieExiste(nomeDoCookie) {
                    var cookies = document.cookie.split('; ');
                    for (var i = 0; i < cookies.length; i++) {
                        var cookie = cookies[i].split('=');
                        if (cookie[0] === nomeDoCookie) {
                            return true;
                        }
                    }
                    return false;
                }

                // Função para obter o valor atual do cookie
                function obterValorDoCookie(nomeDoCookie) {
                    var cookies = document.cookie.split('; ');
                    for (var i = 0; i < cookies.length; i++) {
                        var cookie = cookies[i].split('=');
                        if (cookie[0] === nomeDoCookie) {
                            return cookie[1];
                        }
                    }
                    return null;
                }

                // Verifique se o cookie já existe
                if (cookieExiste(nomeDoCookie)) {
                    var valorAtual = obterValorDoCookie(nomeDoCookie);

                    // Verifique o valor atual e defina o novo valor com base nele
                    if (valorAtual === "fechado") {
                        // Mude para "fechado"
                        document.cookie = nomeDoCookie +
                            "=aberto; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    } else {
                        // Mude para "aberto"
                        document.cookie = nomeDoCookie +
                            "=fechado; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    }

                } else {
                    // Se o cookie não existir, crie e defina como "aberto"
                    document.cookie = nomeDoCookie +
                        "=aberto; expires=Thu, 01 Jan 2030 00:00:00 UTC; path=/;";
                }
            })
        });
    </script>
    <script>
        function atualizarDataEHora() {
            const elementoDataEHora = document.getElementById('data-e-hora');
            const dataEHoraAtual = new Date();
            const dataEHoraFormatada = dataEHoraAtual
                .toLocaleString();

            elementoDataEHora.textContent = dataEHoraFormatada;
        }

        atualizarDataEHora();

        setInterval(atualizarDataEHora, 1000);

        $(document).keydown(function(e) {
            if (e.ctrlKey && e.key === 'm') {
                // Detectou Ctrl + M, agora acione o clique no elemento #abrir
                $('#abrir').click();
            }
        });
    </script>
    @yield('script-body')
</body>

</html>
