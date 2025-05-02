<?php
    $servername = 'GRPJNG011204080'; // ou IP do servidor MySQL
    $username = 'root';
    $password = 'password';
    $database = 'FINANCEIRAS';

    header('Content-Type: text/html; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = mysqli_connect($servername, $username, $password, $database);
        mysqli_set_charset($conn, "utf8");

        if (!$conn) {
            die("<script>alert('Erro na conexão com o banco MySQL.');</script>");
        }

        function limparNumero($valor) {
            if ($valor === null || $valor === '') return 0;
            return (int) preg_replace('/\D/', '', $valor);
        }         

        function limparDocumento($valor) {
            return preg_replace('/\D/', '', $valor);
        }

        $cpf_cnpj = limparDocumento($_POST['cpf_cnpj'] ?? '');
        $dt_nascimento = limparDocumento($_POST['dt_nascimento'] ?? '');
        $estado = $_POST['estado'];
        $valor_patrimonio = limparNumero($_POST['valor_patrimonio'] ?? '');
        $renda_mensal = limparNumero($_POST['renda_mensal'] ?? '');
        $valor_projeto = limparNumero($_POST['valor_projeto'] ?? '');
        $data_inicial = $_POST['data_inicial'] ?? null;  // Aqui
        $data_final = $_POST['data_final'] ?? null;  // Aqui
        
        date_default_timezone_set('America/Sao_Paulo');
        $data_input = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO clientes (
            cpf_cnpj, dt_nascimento, estado, valor_projeto, data_input, data_inicial, data_final
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "sssssss", 
        $cpf_cnpj,       // s: string
        $dt_nascimento,  // s: string
        $estado,         // s: string
        $valor_projeto,  // s: string
        $data_input,     // s: string
        $data_inicial,   // s: string
        $data_final      // s: string
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
        setTimeout(function () {
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

        .box{
            display: inline-block;
            margin: 20px;
        }

        .box-text{
            margin: 10px;
            margin-left: 550px;
        }

        .box-text a{
            color: #001d40;
            font-size: 26px;
        }

        .box-imagem{
            margin-left: 400px;
        }

        navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }

        .hideblk{
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

        .button-salvar{
                background-color:rgb(5, 64, 0);
                color: white;
                padding: 10px 30px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
                margin: 5px;
            }

        .button-salvar:hover{
            background-color: rgba(5, 64, 0, 0.67);
        }

        .button-limprar{
                background-color:rgb(190, 11, 11);
                color: white;
                padding: 10px 30px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
                margin: 5px;
            }

        .button-limprar:hover{
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
            grid-template-columns: 150px 1fr; /* Label e Input lado a lado */
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

            .btn-simulacao, .button {
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
            $camposSelect = [
                "estado" => ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG",
                             "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"]
            ];

            $fields = [
                "cpf_cnpj", "dt_nascimento", "estado", "valor_projeto"
            ];

            $labels = [
                "cpf_cnpj" => "CPF/CNPJ:",
                "dt_nascimento" => "Data de Nascimento:",
                "estado" => "Estado:",
                "valor_projeto" => "Valor Projeto:",
            ];

            $camposSelect = [
                "estado" => ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"],
            ];


            foreach ($fields as $field) {
                echo "<div class='input-row'>";
                echo "<label for='$field'>{$labels[$field]}</label>";
            
                $isRequired = ($field !== 'integrador') ? "required" : "";
                
                if (array_key_exists($field, $camposSelect)) {
                    echo "<select name='$field' id='$field' $isRequired>";
                    echo "<option value=''>Selecione</option>";
            
                    foreach ($camposSelect[$field] as $option) {
                        $selected = (isset($valoresPadrao[$field]) && $option === $valoresPadrao[$field]) ? 'selected' : '';
                        echo "<option value='$option' $selected>$option</option>";
                    } 
                    echo "</select>";
                } else {
                    // Usa input type="date" para data_inicial e data_final
                    $inputType = in_array($field, ['data_inicial', 'data_final']) ? 'date' : 'text';
                    echo "<input type='$inputType' name='$field' id='$field' $isRequired>";
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
        $(document).ready(function () {
            var cpfCnpjField = $('#cpf_cnpj');

            cpfCnpjField.on('input', function () {
                var value = cpfCnpjField.val().replace(/\D/g, '');

                if (value.length >= 11) {
                    cpfCnpjField.mask('00.000.000/0000-00', { reverse: true });
                } else {
                    cpfCnpjField.mask('000.000.000-00', { reverse: true });
                }
            });

            $('#dt_nascimento').mask("00/00/0000");
            $('#valor_projeto').mask('000.000.000,00', {reverse: true});
        });

    </script>
</body>
</html>
