<?php
$servername = 'localhost:3312'; // ou IP do servidor MySQL
$username = 'root';
$password = '';
$database = 'FINANCEIRAS';


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
    $dt_nascimento = $_POST['dt_nascimento'] ?? '';
    date_default_timezone_set('America/Sao_Paulo');
    $data_input = date('Y-m-d H:i:s');
    $celular = limparDocumento($_POST['celular'] ?? '');
    $cnpj_integrador = limparDocumento($_POST['cnpj_integrador'] ?? '');

    if (!empty($dt_nascimento)) {
        // Formatar para o formato Y-m-d para o MySQL
        $dt_nascimento = date('d-m-Y', strtotime($dt_nascimento)); // Exemplo: 2025-05-05
    } else {
        $dt_nascimento = null;
    }
    // Se houver a data
    if (!empty($dt_nascimento)) {
        // Remover os separadores e transformar a data no formato dmy (sem hífens)
        $dt_nascimento_limpa = str_replace(['-', '/'], '', $dt_nascimento); // Exemplo: 05052025
    } else {
        $dt_nascimento_limpa = null;
    }


    $sql = "INSERT INTO clientes (
            cpf_cnpj, dt_nascimento, estado, valor_projeto, data_input, celular, cnpj_integrador
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $cpf_cnpj,       // s: string
        $dt_nascimento_limpa,  // s: string
        $estado,         // s: string
        $valor_projeto,  // s: string
        $data_input,     // s: string
        $celular,
        $cnpj_integrador
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
    <link rel="icon" href="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" sizes="32x32">
    <title>Cadastro de Financiamentos</title>
    <style>
        @font-face {
            font-family: 'Aptos Display';
            src: url('AptosDisplay.woff2') format('woff2'),
                url('AptosDisplay.woff') format('woff');
        }

        * {
            margin: 0;
            padding: 0;
            outline: none;
            box-sizing: border-box;
        }

        body {
            font-family: 'Aptos Display';
            background-image: linear-gradient(to right, #003676, #00aae3);
        }

        .link {
            font-family: 'Aptos Display';
            color: #001d40;
            padding: 5px 10px;
            border-radius: 5px;
            overflow: hidden;
            margin: 10px;
            text-decoration: none;
        }

        .link:hover {
            background-color: #001d40;
            color: white;
        }

        .active {
            background-color: #04AA6D;
        }

        .box {
            display: inline-block;
            margin: 20px;
        }

        .box-text {
            margin: 10px;
            margin-left: 550px;
        }

        .box-text a {
            color: #001d40;
            font-size: 26px;
        }

        .box-imagem {
            margin-left: 400px;
        }

        navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }

        .hideblk {
            display: none;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
            flex-wrap: wrap;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 0 10px #00000030;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .input-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            align-items: center;
            gap: 10px;
            flex: 1 1 45%;
        }

        .input-row label {
            font-weight: bold;
        }

        .input-row input,
        .input-row select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .full {
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }

        .button {
            background-color: #001d40;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 5px;
        }

        .button:hover {
            background-color: #0091E4;
        }

        .button-salvar {
            background-color: rgb(5, 64, 0);
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 5px;
        }

        .button-salvar:hover {
            background-color: rgba(5, 64, 0, 0.67);
        }

        .button-limprar {
            background-color: rgb(190, 11, 11);
            color: white;
            padding: 10px 30px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 5px;
        }

        .button-limprar:hover {
            background-color: rgba(190, 11, 11, 0.77);
        }

        h1 {
            text-align: center;
            color: white;
            margin-top: 80px;
        }

        .footer-link {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        .footer-link:hover {
            color: #0091E4;
        }

        .select2 {
            border: 0;
            box-shadow: none;
            padding: 0 1em;
            background-color: transparent;
            height: 3em;
            border-radius: .25em;
            color: black;
        }

        /* CAMPO ESPECIAL: TEMPO NA EMPRESA */
        .tempo-empresa-wrapper {
            display: flex;
            flex-direction: row;
            gap: 10px;
            width: 100%;
        }

        .tempo-empresa-wrapper input {
            flex: 1;
            min-width: 0;
        }

        footer {
            background-color: #000000;
            bottom: 0;
            width: 100%;
            position: fixed;
        }

        .container-footer {
            display: flex;
            justify-content: space-between;
            color: white;
            padding: 10px;
        }

        .input-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            /* Label e Input lado a lado */
            align-items: center;
            gap: 10px;
        }


        @media (max-width: 600px) {
            h1 {
                font-size: 20px;
            }

            a {
                font-size: 14px;
                margin: 0 5px;
            }

            .btn-simulacao,
            .button {
                padding: 8px 12px;
                font-size: 14px;
            }

            .container-footer {
                flex-direction: column;
                text-align: center;
                gap: 5px;
            }
        }


        @media (max-width: 768px) {
            .client-info-pair {
                flex: 1 1 100%;
            }

            .box-text {
                margin: 10px auto;
                text-align: center;
            }

            .box-imagem {
                display: block;
                margin: 0 auto;
            }

            .container {
                margin: 20px 10px;
                padding: 10px;
                flex-direction: column;
                align-items: center;
            }

            .client-info {
                width: 100%;
                max-width: 100%;
                padding: 10px;
            }

            .client-info label,
            .client-info span {
                display: block;
            }

            .container-footer {
                flex-direction: column;
                text-align: center;
                gap: 5px;
            }

            h1 {
                font-size: 22px;
                padding: 0 10px;
            }

            .btn-back {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="preload" href="AptosDisplay.woff2" as="font" type="font/woff2" crossorigin>
</head>

<body>
    <header>
        <navbar>
            <div class="box">
                <img src="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" width="100px" class="box-imagem">
            </div>
            <a href="./forms.php" class="link">Cadastro de Financiamentos</a>
            <a href="./dashboard.php" class="link">Simulações</a>
        </navbar>
    </header>
    <h1>Cadastro de Financiamentos</h1>
    <div class="container">
        <form method="POST" id="clienteForm">
            <?php
            // Gera o campo CPF/CNPJ fora do foreach
            $valorCpfCnpj = isset($valoresPadrao['cpf_cnpj']) ? htmlspecialchars($valoresPadrao['cpf_cnpj']) : '';
            echo "<div class='input-row'>";
            echo "<label for='cpf_cnpj'>CPF/CNPJ:</label>";
            echo "<input type='text' name='cpf_cnpj' id='cpf_cnpj' value='$valorCpfCnpj' required>";
            echo "</div>";

            // Campos restantes
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
                ]
            ];

            $fields = ["dt_nascimento", "estado", "valor_projeto", "celular", "cpnj_integrador"];

            $labels = [
                "dt_nascimento" => "Data de Nascimento:",
                "estado" => "Estado:",
                "valor_projeto" => "Valor Projeto:",
                "celular" => "Celular:",
                "cpnj_integrador" => "CNPJ Integrador:"
            ];

            foreach ($fields as $field) {
                echo "<div class='input-row'>";
                echo "<label for='$field'>{$labels[$field]}</label>";
                $isRequired = "required";

                if (array_key_exists($field, $camposSelect)) {
                    echo "<select name='$field' id='$field'>";
                    echo "<option value=''>Selecione</option>";

                    foreach ($camposSelect[$field] as $option) {
                        $selected = (isset($valoresPadrao[$field]) && $option === $valoresPadrao[$field]) ? 'selected' : '';
                        echo "<option value='$option' $selected>$option</option>";
                    }
                    echo "</select>";
                } else {
                    $inputType = ($field === 'dt_nascimento') ? 'date' : 'text';
                    $value = isset($valoresPadrao[$field]) ? htmlspecialchars($valoresPadrao[$field]) : '';
                    echo "<input type='$inputType' name='$field' id='$field' value='$value'>";
                }

                echo "</div>";
            }
            ?>


            <div class="full">
                <button type="submit" class="button-salvar">Enviar</button>
                <button type="reset" class="button-limprar">Limpar</button>
            </div>
        </form>
    </div>

    <footer>
        <div class="container-footer">

            <p class="credits-left">
                © <span id="currentYear"></span> <a href="/home.html" class="footer-link">Intranet | JNG</a>
            </p>

            <p class="credits-right">
                <span>Desenvolvido por Tecnologia <a href="http://jng.com.br" class="footer-link">JNG</a></span>
            </p>
        </div>
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

            $('#cpnj_integrador').mask('000.000.000/0000-00', {
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
            const data = document.getElementById("dt_nascimento").value;

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
            const dataNascimento = document.getElementById('dt_nascimento').value;
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

</body>

</html>