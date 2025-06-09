<?php
include("config.php");

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = mysqli_connect($servername, $username, $password, $database);
    mysqli_set_charset($conn, "utf8");

    if (!$conn) {
        die("<script>alert('Erro na conexão com o banco MySQL.');</script>");
    }

    function limparNumero($valor)
    {
        if ($valor === null || $valor === '') return 0;
        return (int) preg_replace('/\D/', '', $valor);
    }

    function limparDocumento($valor)
    {
        return preg_replace('/\D/', '', $valor);
    }


    $cpf_cnpj = limparDocumento($_POST['cpf_cnpj'] ?? '');
    $estado = $_POST['estado'];
    $valor_patrimonio = limparNumero($_POST['valor_patrimonio'] ?? '');
    $renda_mensal = limparNumero($_POST['renda_mensal'] ?? '');
    $valor_projeto = limparNumero($_POST['valor_projeto'] ?? '');
    $dt_nascfund = $_POST['dt_nascfund'] ?? '';
    date_default_timezone_set('America/Sao_Paulo');
    $data_input = date('Y-m-d H:i:s');
    $celular = limparDocumento($_POST['celular'] ?? '');
    $cnpj_integrador = limparDocumento($_POST['cnpj_integrador'] ?? '');
    $operador = $_POST['operador'];

    if (!empty($dt_nascfund)) {
        // Formatar para o formato Y-m-d para o MySQL
        $dt_nascfund = date('d-m-Y', strtotime($dt_nascfund)); // Exemplo: 2025-05-05
    } else {
        $dt_nascfund = null;
    }
    // Se houver a data
    if (!empty($dt_nascfund)) {
        // Remover os separadores e transformar a data no formato dmy (sem hífens)
        $dt_nascimento_limpa = str_replace(['-', '/'], '', $dt_nascfund); // Exemplo: 05052025
    } else {
        $dt_nascimento_limpa = null;
    }


    $sql = "INSERT INTO clientes (
            cpf_cnpj, dt_nascfund, estado, valor_projeto, data_input, celular, operador, cnpj_integrador, renda_mensal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssss",
        $cpf_cnpj,       // s: string
        $dt_nascimento_limpa,  // s: string
        $estado,         // s: string
        $valor_projeto,  // s: string
        $data_input,     // s: string
        $celular,
        $operador,
        $cnpj_integrador,
        $renda_mensal
    );

    if (mysqli_stmt_execute($stmt)) {
        $last_id = mysqli_insert_id($conn);
        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=1");
        exit;
    } else {
        echo "<script>alert('Erro ao inserir dados.');</script>";
        echo "<pre>";
        print_r(mysqli_error($conn));
        echo "</pre>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        alert('✅ Dados inseridos com sucesso!');
        setTimeout(function() {
            window.location.href = window.location.pathname;
        }, 1000);
    </script>
<?php endif; ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="AptosDisplay.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="shortcut icon" href="https://www.jng.com.br/site/img/favicon.ico">
    <title>Cadastro de Financiamentos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Aptos Display', sans-serif;
            background: #f2f2f2;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #003366;
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        header .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
            flex: 1;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.3s, transform 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: "";
            display: block;
            height: 2px;
            width: 0%;
            background: #00aae3;
            transition: width 0.3s;
            margin-top: 5px;
        }

        .nav-links a:hover {
            color: #00aae3;
            transform: translateY(-2px);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .alert {
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 6px;
            font-size: 16px;
            color: #fff;
            background-color: #e74c3c;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            border-left: 6px solid #c0392b;
            transition: opacity 0.3s ease;
        }

        .alert-success {
            background-color: #2ecc71;
            border-left: 6px solid #27ae60;
        }

        .alert-error {
            background-color: #e74c3c;
            border-left: 6px solid #c0392b;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="checkbox"] {
            appearance: none;
            width: 22px;
            height: 22px;
            border: 2.5px solid #007bff;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            transition: background-color 0.25s, border-color 0.25s;
            outline-offset: 2px;
        }

        input[type="checkbox"]:hover {
            border-color: #0056b3;
        }

        input[type="checkbox"]:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 4px;
            left: 7px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        input[type="checkbox"]:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.4);
        }

        .input-wrapper {
            display: flex;
            flex-direction: column;
        }

        .input-wrapper label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        .input-wrapper input[type="number"] {
            width: 120px;
            padding: 6px 10px;
            border: 1.8px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .input-wrapper input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="time"],
        textarea[id="assunto"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #fff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #00aae3;
            box-shadow: 0 0 5px #003366;
            outline: none;
        }

        .dark-mode .form-container {
            background-color: #1f1f1f;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .dark-mode .form-side h2,
        .dark-mode .form-group label {
            color: #ccc;
        }

        .dark-mode .form-group input,
        .dark-mode .form-group select,
        .dark-mode .form-group textarea {
            background-color: #2c2c2c;
            color: #f5f5f5;
            border: 1px solid #444;
        }

        .dark-mode .form-actions button,
        .dark-mode .a-button {
            background: #00aae3;
        }


        .dark-mode-btn {
            background: #00aae3;
            color: white;
            border: none;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .dark-mode-btn:hover {
            background: #007ba1;
            transform: rotate(20deg);
        }

        .dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .form-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .form-side {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-side h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-actions {
            margin-top: 25px;
        }

        .form-actions button,
        .a-button {
            padding: 14px;
            background: #003366;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
            font-family: 'Aptos Display';
            display: inline-block;
            text-align: center;
        }

        .form-actions button:hover,
        .form-actions .a-button:hover {
            background: #00aae3;
        }

        .image-side {
            flex: 1;
            background: url('https://www.embraplan.com.br/imagens/noticias/ba23900d-38ca-4a77-8b63-0d25f16f8f74.jpg') no-repeat center center;
            background-size: cover;
        }

        footer {
            background: #003366;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 60px;
        }

        footer a {
            color: #00aae3;
            text-decoration: none;
        }

        /* Container flexível para logo + menu */
        .logo-menu-group {
            display: flex;
            align-items: center;
            gap: 70px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            color: white;
            cursor: pointer;
        }

        .dark-mode-btn.mobile {
            display: none;
        }

        .dark-mode-btn.desktop {
            display: inline-block;
        }

        .back-link {
            display: none;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                background-color: #003366;
                width: 100%;
                position: absolute;
                top: 60px;
                left: 0;
                z-index: 1000;
            }

            .nav-links.show {
                display: flex;
                top: 70px;
            }

            .nav-links a,
            .nav-links button {
                padding: 12px 20px;
                color: white;
                text-align: left;
                background: none;
                font-size: 16px;
                top: 20px;
            }

            .back-link {
                display: block;
                font-weight: bold;
                font-size: 18px;
                color: #ccc;
            }

            .dark-mode-btn.desktop {
                display: none;
            }

            .dark-mode-btn.mobile {
                display: block;
                width: 150px;
            }
        }

        /* Responsivo */
        @media (max-width: 1024px) {
            .form-side {
                padding: 40px;
            }
        }

        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .form-container {
                flex-direction: column;
            }

            .image-side {
                height: 180px;
                order: -1;
            }

            .form-side h2 {
                font-size: 24px;
                text-align: center;
            }

            .form-side {
                padding: 30px 20px;
            }

            .nav-links {
                gap: 15px;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {

            .form-actions button,
            .a-button {
                width: 100%;
                font-size: 15px;
                margin: 5px;
            }

            .alert {
                font-size: 14px;
            }

            .form-side h2 {
                font-size: 22px;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="preload" href="AptosDisplay.woff2" as="font" type="font/woff2" crossorigin>
</head>

<body>
    <header>
        <div class="container">
            <!-- Ícone do menu ao lado do logo -->
            <div class="logo-menu-group">
                <button class="menu-toggle" onclick="toggleMenu()">☰</button>
                <img src="https://www.jng.com.br/site/img/logos/logo.svg" width="120" alt="Logo">
            </div>

            <!-- Navegação -->
            <nav class="nav-links" id="navLinks">
                <a href="./forms.php">Cadastro de Financiamentos</a>
                <a href="./dashboard.php">Simulações</a>
                <button onclick="toggleDarkMode()" class="dark-mode-btn mobile">Dark Mode 🌓</button>
            </nav>

            <!-- Botão de modo escuro (fora do menu) -->
            <button onclick="toggleDarkMode()" class="dark-mode-btn desktop">🌓</button>
        </div>
    </header>


    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    <main>
        <div class="form-container">
            <div class="form-side">
                <h2>Cadastro de Financiamentos</h2>
                <form method="POST" id="clienteForm">
                    <div class="form-group">
                        <?php
                        // Gera o campo CPF/CNPJ fora do foreach
                        $valorCpfCnpj = isset($valoresPadrao['cpf_cnpj']) ? htmlspecialchars($valoresPadrao['cpf_cnpj']) : '';
                        echo "<div class='input-row'>";
                        echo "<label for='cpf_cnpj'>CPF/CNPJ:</label>";
                        echo "<input type='text' name='cpf_cnpj' id='cpf_cnpj' value='$valorCpfCnpj' required>";
                        echo "</div>";

                        $camposSelect = [
                            "estado" => [
                                "AC",
                                "AL",
                                "AP",
                                "AM",
                                "BA",
                                "CE",
                                "DF",
                                "ES",
                                "GO",
                                "MA",
                                "MT",
                                "MS",
                                "MG",
                                "PA",
                                "PB",
                                "PR",
                                "PE",
                                "PI",
                                "RJ",
                                "RN",
                                "RS",
                                "RO",
                                "RR",
                                "SC",
                                "SP",
                                "SE",
                                "TO"
                            ],
                            "operador" => [
                                "Pedro Oliveira",
                                "Pamela Ribeiro"
                            ]
                        ];

                        $fields = ["dt_nascfund", "cnpj_integrador", "estado", "valor_projeto", "renda_mensal", "celular", "operador"];

                        $labels = [
                            "dt_nascfund" => "Data de Nascimento/Fundação:",
                            "estado" => "Estado:",
                            "valor_projeto" => "Valor Projeto:",
                            "celular" => "Celular:",
                            "renda_mensal" => "Renda Mensal:",
                            "operador" => "Operador:",
                            "cnpj_integrador" => "Cnpj Integrador:"
                        ];

                        foreach ($fields as $field) {
                            echo "<div class='input-row'>";
                            echo "<label for='$field'>{$labels[$field]}</label>";
                            $isRequired = "required";

                            if (array_key_exists($field, $camposSelect)) {
                                echo "<select name='$field' id='$field' $isRequired>";
                                echo "<option value=''>Selecione</option>";

                                foreach ($camposSelect[$field] as $index => $option) {
                                    // Se for operador, usamos o índice + 1 como value
                                    $value = ($field === 'operador') ? $index + 1 : $option;

                                    $selected = (isset($valoresPadrao[$field]) && $valoresPadrao[$field] == $value) ? 'selected' : '';
                                    echo "<option value='$value' $selected>$option</option>";
                                }

                                echo "</select>";
                            } else {
                                $inputType = ($field === 'dt_nascfund') ? 'date' : 'text';
                                $value = isset($valoresPadrao[$field]) ? htmlspecialchars($valoresPadrao[$field]) : '';
                                echo "<input type='$inputType' name='$field' id='$field' value='$value' $isRequired>";
                            }

                            echo "</div>";
                        }
                        ?>
                    </div>


                    <div class="form-actions">
                        <button type="submit" class="button-salvar">Enviar</button>
                        <button type="reset" class="button-limprar">Limpar</button>
                    </div>
                </form>
            </div>
            <div class="image-side"></div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Intranet | JNG — <a href="https://www.jng.com.br" target="_blank">GRUPO JNG</a></p>
    </footer>

    <script>
        document.getElementById("currentYear").textContent = new Date().getFullYear();
    </script>

    <script>
        $(document).ready(function() {
            var cpfCnpjField = $('#cpf_cnpj');

            cpfCnpjField.on('input', function() {
                var value = cpfCnpjField.val().replace(/\D/g, '');

                if (value.length >= 11) {
                    cpfCnpjField.mask('00.000.000/0000-00', {
                        reverse: true
                    });
                } else {
                    cpfCnpjField.mask('000.000.000-00', {
                        reverse: true
                    });
                }
            });

            // Máscara para o campo de celular
            $('#celular').mask('(00) 00000-0000');

            $('#valor_projeto').mask('000.000.000,00', {
                reverse: true
            });

            $('#renda_mensal').mask('000.000.000,00', {
                reverse: true
            });

            $('#cnpj_integrador').mask('00.000.000/0000-00', {
                reverse: true
            });
        });

        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g, '');
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

            let soma = 0;
            for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
            let resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.charAt(9))) return false;

            soma = 0;
            for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;

            return resto === parseInt(cpf.charAt(10));
        }

        function validarCNPJ(cnpj) {
            cnpj = cnpj.replace(/[^\d]+/g, '');
            if (cnpj.length !== 14 || /^(\d)\1+$/.test(cnpj)) return false;

            let t = cnpj.length - 2,
                d = cnpj.substring(t),
                d1 = parseInt(d.charAt(0)),
                d2 = parseInt(d.charAt(1)),
                calc = x => {
                    let n = cnpj.substring(0, x),
                        r = 0,
                        pos = x - 7;
                    for (let i = x; i >= 1; i--) {
                        r += n.charAt(x - i) * pos--;
                        if (pos < 2) pos = 9;
                    }
                    return ((r % 11) < 2) ? 0 : 11 - (r % 11);
                };
            return calc(t) === d1 && calc(t + 1) === d2;
        }

        function validarNascimento(data) {
            if (!data) {
                // Campo vazio, não exige validação
                return true;
            }

            if (!maiorDe18(data)) {
                alert("É necessário ter 18 anos ou mais.");
                return false;
            }

            return true;
        }

        function maiorDe18(data) {
            const hoje = new Date();
            const nascimento = new Date(data);
            const idade = hoje.getFullYear() - nascimento.getFullYear();
            const mes = hoje.getMonth() - nascimento.getMonth();
            const dia = hoje.getDate() - nascimento.getDate();

            return idade > 18 || (idade === 18 && (mes > 0 || (mes === 0 && dia >= 0)));
        }

        document.getElementById("clienteForm").addEventListener("submit", function(e) {
            const doc = document.getElementById('cpf_cnpj').value.trim().replace(/\D/g, '');
            const data = document.getElementById("dt_nascfund").value;

            if (doc.length <= 11) { // CPF
                if (!validarNascimento(data)) {
                    e.preventDefault(); // Impede envio se for inválido
                }
            }
        });


        function validarCelular(celular) {
            celular = celular.replace(/\D/g, '');
            return celular.length === 10 || celular.length === 11;
        }


        document.getElementById('clienteForm').addEventListener('submit', function(e) {
            const doc = document.getElementById('cpf_cnpj').value.trim().replace(/\D/g, ''); // Remove máscara
            const dataNascimento = document.getElementById('dt_nascfund').value;
            const celular = document.getElementById('celular').value.trim().replace(/\D/g, ''); // Remove máscara

            let erro = '';

            if (doc.length <= 11) {
                if (!validarCPF(doc)) {
                    erro += 'CPF inválido.\n';
                }
            } else {
                if (!validarCNPJ(doc)) {
                    erro += 'CNPJ inválido.\n';
                }
            }



            if (!validarCelular(celular)) {
                erro += 'Número de celular inválido. Deve conter 10 e 11 dígitos.\n';
            }

            if (erro !== '') {
                e.preventDefault();
                alert('⚠️ Erro no formulário:\n' + erro);
            }
        });
    </script>

    <script>
        function toggleMenu() {
            const nav = document.getElementById('navLinks');
            nav.classList.toggle('show');
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");

            // Salva o estado atual no localStorage
            const isDark = document.body.classList.contains("dark-mode");
            localStorage.setItem("theme", isDark ? "dark" : "light");
        }

        // Aplica o tema salvo ao carregar a página
        window.addEventListener("DOMContentLoaded", function() {
            const savedTheme = localStorage.getItem("theme");
            if (savedTheme === "dark") {
                document.body.classList.add("dark-mode");
            }
        });

        // Evento no botão (caso ainda não tenha)
        document.getElementById("toggle-theme").addEventListener("click", toggleDarkMode);
    </script>
</body>

</html>