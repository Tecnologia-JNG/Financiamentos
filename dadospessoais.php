<?php
date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=UTF-8');

$servername = '127.0.0.1:3312'; // ou IP do servidor MySQL
$username = 'root';
$password = '';
$database = 'FINANCEIRAS';

// Conexão com MySQL
$conn = mysqli_connect($servername, $username, $password, $database);
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
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

function limparData($valor)
{
    // Remove qualquer coisa que não seja número ou barra
    return preg_replace('/[^0-9\/]/', '', $valor);
}


// Verifica se o id foi passado via URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para pegar os dados do cliente
    $sql = "SELECT * FROM clientes WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Erro na consulta: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $cliente = mysqli_fetch_assoc($result); // Armazena os dados do cliente
    } else {
        echo "Cliente não encontrado.";
        exit;
    }
} else {
    echo "ID não especificado.";
    exit;
}

// Atualização dos dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('America/Sao_Paulo');
    $cpf_cnpj = limparDocumento($_POST['cpf_cnpj'] ?? '');

    // Se vier vazio, mantém o valor original do banco
    if (empty($cpf_cnpj)) {
        $cpf_cnpj = $cliente['cpf_cnpj'];
    }

    $nome = $_POST['nome'];
    $celular = limparDocumento($_POST['celular'] ?? '');
    $email = $_POST['email'];
    $rg = limparDocumento($_POST['rg'] ?? '');
    $dt_nascimento = limparData($_POST['dt_nascimento'] ?? '');
    $dt_nascfund = limparData($_POST['dt_nascfund'] ?? '');
    $dt_fundacao = limparData($_POST['dt_nascfund'] ?? '');
    $cnpj_integrador = limparDocumento($_POST['cnpj_integrador'] ?? '');

    if (!empty($dt_nascimento)) {
        // Converte para o formato d-m-Y
        $dt_nascimento = date('dmY', strtotime(str_replace('/', '-', $dt_nascimento)));
    } else {
        $dt_nascimento = '';
    }

    $nacionalidade = $_POST['nacionalidade'];
    $genero = $_POST['genero'];
    $estado_civil = $_POST['estado_civil'];
    $valor_patrimonio = limparDocumento($_POST['valor_patrimonio'] ?? '');
    $nome_mae = $_POST['nome_mae'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $tipo_imovel = $_POST['tipo_imovel'];
    $natureza_ocupacao = $_POST['natureza_ocupacao'];
    $profissao = $_POST['profissao'];
    $tempo_prof_anos = $_POST['tempo_prof_anos'] ?? '';
    $tempo_prof_meses = $_POST['tempo_prof_meses'] ?? '';
    $renda_mensal = $_POST['renda_mensal'] ?? '';
    $integrador = $_POST['integrador'];
    $parcela = $_POST['parcela'];
    $carencia = $_POST['carencia'];
    $agente = $_POST['agente'];
    $gerente = $_POST['gerente'];
    $valor_projeto = limparNumero($_POST['valor_projeto'] ?? '');
    $clienteProfissao = $_POST['profissao'];
    $cpf = $_POST['cpf'];
    $cep_com = $_POST['cep_com'];
    $end_com = $_POST['end_com'];
    $nmr_com = $_POST['nmr_com'];
    $cid_com = $_POST['cid_com'];
    $est_com = $_POST['est_com'];
    $bairro_com = $_POST['bairro_com'];
    $raz_soc = $_POST['raz_soc'];
    $nome_fant = $_POST['nome_fant'];
    $tempo_emp_ano = $_POST['tempo_emp_ano'];
    $tempo_emp_mes = $_POST['tempo_emp_mes'];
    $cap_social = limparDocumento($_POST['cap_social'] ?? '');
    $tel_com = $_POST['tel_com'];
    $fat_med = limparDocumento($_POST['fat_med'] ?? '');

    $sql_update = "UPDATE clientes SET 
                                                cpf_cnpj = '$cpf_cnpj', 
                                                nome = '$nome',
                                                celular = '$celular',
                                                email = '$email',
                                                rg = '$rg',
                                                dt_nascimento = '$dt_nascimento',
                                                nacionalidade = '$nacionalidade',
                                                genero = '$genero',
                                                estado_civil = '$estado_civil',
                                                valor_patrimonio = '$valor_patrimonio',
                                                nome_mae = '$nome_mae',
                                                cep = '$cep',
                                                endereco = '$endereco',
                                                numero = '$numero',
                                                bairro = '$bairro',
                                                cidade = '$cidade',
                                                estado = '$estado',
                                                tipo_imovel = '$tipo_imovel',
                                                natureza_ocupacao = '$natureza_ocupacao',
                                                profissao = '$profissao',
                                                tempo_prof_anos = '$tempo_prof_anos',
                                                tempo_prof_meses = '$tempo_prof_meses',
                                                renda_mensal = '$renda_mensal',
                                                integrador = '$integrador',
                                                parcela = '$parcela',
                                                carencia = '$carencia',
                                                agente = '$agente',
                                                gerente = '$gerente',
                                                valor_projeto = '$valor_projeto',
                                                cpf = '$cpf',
                                                cep_com = '$cep_com',
                                                end_com = '$end_com',
                                                nmr_com = '$nmr_com',
                                                cid_com = '$cid_com',
                                                est_com = '$est_com',
                                                bairro_com = '$bairro_com',
                                                raz_soc = '$raz_soc',
                                                nome_fant = '$nome_fant',
                                                tempo_emp_ano = '$tempo_emp_ano',
                                                tempo_emp_mes = '$tempo_emp_mes',
                                                cap_social = '$cap_social',
                                                tel_com = '$tel_com',
                                                fat_med = '$fat_med',
                                                cnpj_integrador = '$cnpj_integrador',
                                                dt_nascfund = '$dt_nascfund',
                                                dt_nascfund = '$dt_fundacao',
                                                status = 'simular'
                                            WHERE id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id . "&sucesso=1");
        exit;
    } else {
        echo "Erro ao atualizar os dados: " . mysqli_error($conn);
    }
}
?>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        alert('✅ Dados inseridos com sucesso!');
        setTimeout(function() {
            window.location.href = window.location.pathname + '?id=<?= $id ?>';
        }, 1000);
    </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" sizes="32x32">
    <title>Dados Pessoais do Cliente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        /* Container geral de alertas */
        .alert {
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 6px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            color: #fff;
            background-color: #e74c3c;
            /* vermelho suave para erro */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            border-left: 6px solid #c0392b;
            transition: opacity 0.3s ease;
        }

        /* Mensagem de sucesso */
        .alert-success {
            background-color: #2ecc71;
            /* verde suave */
            border-left: 6px solid #27ae60;
            color: #fff;
        }

        /* Mensagem de erro mais suave (opcional) */
        .alert-error {
            background-color: #e74c3c;
            border-left: 6px solid #c0392b;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 15px;
            text-align: left;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        table thead {
            background-color: #003366;
            color: #ffffff;
        }

        table th,
        table td {
            padding: 12px 18px;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:hover {
            background-color: #f5f5f5;
        }

        table button {
            padding: 6px 12px;
            background-color: #00aae3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        table button:hover {
            background-color: #007ba1;
        }

        .dark-mode table {
            background-color: #1f1f1f;
            color: #f5f5f5;
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.05);
        }

        .dark-mode table thead {
            background-color: #003366;
            color: #ffffff;
        }

        .dark-mode table tbody tr:hover {
            background-color: #2a2a2a;
        }

        .dark-mode table th,
        .dark-mode table td {
            border-color: #444;
        }

        /* Input de número e seu label alinhados verticalmente */
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

        /* Estilo para os inputs focados */
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="number"]:focus,
        input[type="time"]:focus,
        textarea[id="assunto"]:focus,
        select:focus {
            border-color: #00aae3;
            box-shadow: 0 0 5px #003366;
            outline: none;
        }

        .dark-mode .form-container {
            background-color: #1f1f1f;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .dark-mode .form-side h2 {
            color: #ffffff;
        }

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


        .dark-mode .form-actions button {
            background: #00aae3;
        }

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

        .dark-mode .dark-mode-btn {
            background: #00aae3;
        }

        .dark-mode .dark-mode-btn:hover {
            background: #00aae3;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-actions {
            margin-top: 25px;
        }

        .form-actions button {
            padding: 14px;
            background: #003366;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
            font-family: 'Aptos Display';
        }

        .a-button {
            text-decoration: none;
            color: white;
            padding: 14px;
            background: #003366;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .form-actions button:hover {
            background: #00aae3;
        }

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



        @media (max-width: 768px) {
            .form-container {
                flex-direction: column;
                height: auto;
            }

            .image-side {
                height: 200px;
            }
        }

        /* Container principal */
        .form-container {
            width: 100%;
            max-width: 1000px;
            height: auto;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
            padding: 40px;
            margin: 40px auto;
        }

        .dark-mode .client-info-pair span {
            background-color: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }

        .dark-mode .section-header {
            background-color: #003366;
            color: #ffffff;
        }

        .dark-mode .client-info-pair label {
            color: #fff;
        }

        /* Container de pares de info */
        .client-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
        }

        /* Cada par (label + span) */
        .client-info-pair {
            display: flex;
            flex: 1 1 48%;
            align-items: center;
            gap: 12px;
            min-width: 280px;
        }

        /* Label */
        .client-info-pair label {
            flex: 0 0 40%;
            font-weight: 600;
            color: #374151;
            font-size: 16px;
        }

        /* Valor exibido */
        .client-info-pair span {
            flex: 1;
            display: inline-block;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f3f4f6;
            color: #111827;
            font-size: 15px;
            margin-bottom: 10px;
        }

        /* Botão Voltar */
        .btn-back {
            align-self: flex-start;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 30px;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .btn-back:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
        }

        /* Seções dobráveis */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f3f4f6;
            padding: 14px 20px;
            border-radius: 8px;
            margin-top: 30px;
            color: #111827;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .section-header:hover {
            background-color: #e5e7eb;
        }

        .section-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .arrow {
            font-size: 18px;
            transition: transform 0.3s;
        }

        .section-content {
            display: none;
            margin-top: 15px;
        }

        .section.open .section-content {
            display: block;
        }

        .section.open .arrow {
            transform: rotate(180deg);
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

        .dark-mode-btn {
            background: none;
            border: none;
            font-size: 22px;
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

        /* Continuações necessárias */
        .client-info-pair span {
            flex: 1;
            display: inline-block;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f3f4f6;
            color: #111827;
            font-size: 15px;
        }

        /* === RESPONSIVIDADE GERAL === */

        /* Grandes tablets e desktops até 1024px */
        @media (max-width: 1024px) {
            .form-side {
                padding: 40px;
            }
        }

        /* Tablets, telas médias até 992px */
        @media (max-width: 992px) {
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
                gap: 15px;
                /* para espaçar links */
                justify-content: center;
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
                /* removi 'top:20px' pois não afeta elementos inline */
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

            .form-side {
                padding: 40px 30px;
            }

            .form-actions button,
            .a-button {
                width: 100%;
                font-size: 15px;
                margin: 5px 0;
                text-align: center;
            }

            table th,
            table td {
                padding: 10px 14px;
                font-size: 14px;
            }
        }

        /* Tablets e telas pequenas até 768px */
        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

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
                gap: 15px;
                justify-content: center;
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

            .form-container {
                flex-direction: column;
                padding: 30px 20px;
            }

            .form-side {
                padding: 30px 20px;
            }

            .form-side h2 {
                font-size: 24px;
                text-align: center;
            }

            .form-group label,
            .client-info-pair label {
                font-size: 14px;
            }

            .form-actions {
                display: flex;
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .form-actions button,
            .a-button {
                width: 100%;
                font-size: 15px;
                margin: 5px 0;
                text-align: center;
            }

            .client-info-pair {
                flex: 1 1 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            .client-info-pair label,
            .client-info-pair span {
                width: 100%;
            }

            table {
                font-size: 14px;
                border-collapse: collapse;
            }

            table thead {
                display: none;
            }

            table,
            table tbody,
            table tr,
            table td {
                display: block;
                width: 100%;
            }

            table tr {
                margin-bottom: 15px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
                border-radius: 8px;
                overflow: hidden;
            }

            table td {
                padding: 10px 15px;
                text-align: right;
                position: relative;
                border-bottom: 1px solid #eee;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 50%;
                padding-right: 10px;
                font-weight: bold;
                text-align: left;
                color: #333;
            }

            .image-side {
                height: 180px;
                order: -1;
            }

            .nav-links {
                gap: 15px;
                justify-content: center;
            }
        }

        /* Smartphones até 480px */
        @media (max-width: 480px) {
            body {
                font-size: 14px;
            }

            .form-side {
                padding: 30px 15px;
            }

            .form-actions button,
            .a-button {
                font-size: 15px;
                padding: 12px;
                width: 100%;
                margin: 5px 0;
                text-align: center;
            }

            footer {
                font-size: 14px;
                padding: 15px;
            }

            .alert {
                font-size: 14px;
            }

            .form-side h2 {
                font-size: 22px;
            }
        }
    </style>
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

    <main>
        <div class="form-container">
            <div class="form-side">
                <div class="form-group">
                    <h1>Dados Pessoais do Cliente</h1>
                    <form action="" method="POST">
                        <div class="client-info">
                            <!-- Bloco Pessoa Física -->
                            <div style="width: 1100px;">
                                <div class="section">
                                    <div class="section-header" onclick="toggleSection(this)">

                                        <h2>Dados <span id="exibirTipoPessoa"></span></h2>
                                        <span class="arrow">▼</span>
                                    </div>
                                    <div class="section-content">
                                        <div class="client-info"> <!--Dados do Avalista CPF -->
                                            <!-- campos de pessoa física -->
                                            <div class="client-info-pair">
                                                <label for="cpf_cnpj">CPF/CNPJ:</label>
                                                <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="<?= htmlspecialchars($cliente['cpf_cnpj']) ?>" disabled>
                                            </div>
                                            <div id="form-pj" style="display: none;">
                                                <div class="client-info-pair">
                                                    <label for="cpf">CPF:</label>
                                                    <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($cliente['cpf']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="nome">Nome:</label>
                                                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="celular">Celular:</label>
                                                <input type="text" id="celular" name="celular" value="<?= htmlspecialchars($cliente['celular']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="email">Email:</label>
                                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="rg">RG:</label>
                                                <input type="text" id="rg" name="rg" value="<?= htmlspecialchars($cliente['rg']) ?>" required>
                                            </div>
                                            <div id="form-cpf" style="display: none;">
                                                <div class="client-info-pair">
                                                    <label for="dt_nascfund">Data de Nascimento:</label>
                                                    <input type="text" id="dt_nascfund" name="dt_nascfund" value="<?= htmlspecialchars($cliente['dt_nascfund']) ?>" required>
                                                </div>
                                            </div>
                                            <div id="formPJ" style="display: none;">
                                                <div class="client-info-pair">
                                                    <label for="dt_nascimento">Data de Nascimento</label>
                                                    <input type="text" id="dt_nascimento" name="dt_nascimento" value="<?= htmlspecialchars($cliente['dt_nascimento']) ?>">
                                                </div>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="nacionalidade">Nacionalidade:</label>
                                                <select id="nacionalidade" name="nacionalidade" required>
                                                    <option value="">Selecione</option>
                                                    <option value="Brasileiro" <?= $cliente['nacionalidade'] == 'Brasileiro' ? 'selected' : '' ?>>Brasileiro</option>
                                                    <option value="Estrangeiro" <?= $cliente['nacionalidade'] == 'Estrangeiro' ? 'selected' : '' ?>>Estrangeiro</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="genero">Gênero:</label>
                                                <select id="genero" name="genero" required>
                                                    <option value="">Selecione</option>
                                                    <option value="Masculino" <?= $cliente['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                                    <option value="Feminino" <?= $cliente['genero'] == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                                    <option value="Outro" <?= $cliente['genero'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="estado_civil">Estado Civil:</label>
                                                <select id="estado_civil" name="estado_civil" required>
                                                    <option value="">Selecione</option>
                                                    <option value="Solteiro" <?= $cliente['estado_civil'] == 'Solteiro' ? 'selected' : '' ?>>Solteiro</option>
                                                    <option value="Casado" <?= $cliente['estado_civil'] == 'Casado' ? 'selected' : '' ?>>Casado</option>
                                                    <option value="Divorciado" <?= $cliente['estado_civil'] == 'Divorciado' ? 'selected' : '' ?>>Divorciado</option>
                                                    <option value="Viúvo" <?= $cliente['estado_civil'] == 'Viúvo' ? 'selected' : '' ?>>Viúvo</option>
                                                    <option value="Separado" <?= $cliente['estado_civil'] == 'Separado' ? 'selected' : '' ?>>Separado</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="valor_patrimonio">Valor Patrimônio:</label>
                                                <input type="text" id="valor_patrimonio" name="valor_patrimonio" value="<?= htmlspecialchars($cliente['valor_patrimonio']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="nome_mae">Nome da Mãe:</label>
                                                <input type="text" id="nome_mae" name="nome_mae" value="<?= htmlspecialchars($cliente['nome_mae']) ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section">
                                    <div class="section-header" onclick="toggleSection(this)">
                                        <h2>Endereço Residencial</h2>
                                        <span class="arrow">▼</span>
                                    </div>
                                    <div class="section-content">
                                        <div class="client-info"> <!--Endereço Residencial -->
                                            <div class="client-info-pair">
                                                <label for="cep">CEP:</label>
                                                <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($cliente['cep']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="endereco">Endereço:</label>
                                                <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="numero">Número:</label>
                                                <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($cliente['numero']) ?>">
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="bairro">Bairro:</label>
                                                <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($cliente['bairro']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="cidade">Cidade:</label>
                                                <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="estado">Estado:</label>
                                                <select id="estado" name="estado" required>
                                                    <option value="">Selecione</option>
                                                    <option value="AC" <?= $cliente['estado'] == 'AC' ? 'selected' : '' ?>>AC</option>
                                                    <option value="AL" <?= $cliente['estado'] == 'AL' ? 'selected' : '' ?>>AL</option>
                                                    <option value="AP" <?= $cliente['estado'] == 'AP' ? 'selected' : '' ?>>AP</option>
                                                    <option value="AM" <?= $cliente['estado'] == 'AM' ? 'selected' : '' ?>>AM</option>
                                                    <option value="BA" <?= $cliente['estado'] == 'BA' ? 'selected' : '' ?>>BA</option>
                                                    <option value="CE" <?= $cliente['estado'] == 'CE' ? 'selected' : '' ?>>CE</option>
                                                    <option value="DF" <?= $cliente['estado'] == 'DF' ? 'selected' : '' ?>>DF</option>
                                                    <option value="ES" <?= $cliente['estado'] == 'ES' ? 'selected' : '' ?>>ES</option>
                                                    <option value="GO" <?= $cliente['estado'] == 'GO' ? 'selected' : '' ?>>GO</option>
                                                    <option value="MA" <?= $cliente['estado'] == 'MA' ? 'selected' : '' ?>>MA</option>
                                                    <option value="MT" <?= $cliente['estado'] == 'MT' ? 'selected' : '' ?>>MT</option>
                                                    <option value="MS" <?= $cliente['estado'] == 'MS' ? 'selected' : '' ?>>MS</option>
                                                    <option value="MG" <?= $cliente['estado'] == 'MG' ? 'selected' : '' ?>>MG</option>
                                                    <option value="PA" <?= $cliente['estado'] == 'PA' ? 'selected' : '' ?>>PA</option>
                                                    <option value="PB" <?= $cliente['estado'] == 'PB' ? 'selected' : '' ?>>PB</option>
                                                    <option value="PR" <?= $cliente['estado'] == 'PR' ? 'selected' : '' ?>>PR</option>
                                                    <option value="PE" <?= $cliente['estado'] == 'PE' ? 'selected' : '' ?>>PE</option>
                                                    <option value="PI" <?= $cliente['estado'] == 'PI' ? 'selected' : '' ?>>PI</option>
                                                    <option value="RJ" <?= $cliente['estado'] == 'RJ' ? 'selected' : '' ?>>RJ</option>
                                                    <option value="RN" <?= $cliente['estado'] == 'RN' ? 'selected' : '' ?>>RN</option>
                                                    <option value="RS" <?= $cliente['estado'] == 'RS' ? 'selected' : '' ?>>RS</option>
                                                    <option value="RO" <?= $cliente['estado'] == 'RO' ? 'selected' : '' ?>>RO</option>
                                                    <option value="RR" <?= $cliente['estado'] == 'RR' ? 'selected' : '' ?>>RR</option>
                                                    <option value="SC" <?= $cliente['estado'] == 'SC' ? 'selected' : '' ?>>SC</option>
                                                    <option value="SP" <?= $cliente['estado'] == 'SP' ? 'selected' : '' ?>>SP</option>
                                                    <option value="SE" <?= $cliente['estado'] == 'SE' ? 'selected' : '' ?>>SE</option>
                                                    <option value="TO" <?= $cliente['estado'] == 'TO' ? 'selected' : '' ?>>TO</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="tipo_imovel">Tipo Imóvel:</label>
                                                <select id="tipo_imovel" name="tipo_imovel" required>
                                                    <option value="">Selecione</option>
                                                    <option value="PROPRIO" <?= $cliente['tipo_imovel'] == 'PROPRIO' ? 'selected' : '' ?>>PRÓPRIO</option>
                                                    <option value="FAMILIAR" <?= $cliente['tipo_imovel'] == 'FAMILIAR' ? 'selected' : '' ?>>FAMILIAR</option>
                                                    <option value="ALUGADO" <?= $cliente['tipo_imovel'] == 'ALUGADO' ? 'selected' : '' ?>>ALUGADO</option>
                                                    <option value="PROPRIO FINANCIADO" <?= $cliente['tipo_imovel'] == 'PROPRIO FINANCIADO' ? 'selected' : '' ?>>PRÓPRIO FINANCIADO</option>
                                                    <option value="CEDIDO" <?= $cliente['tipo_imovel'] == 'CEDIDO' ? 'selected' : '' ?>>CEDIDO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section">
                                    <div class="section-header" onclick="toggleSection(this)">
                                        <h2>Dados Profissionais</h2>
                                        <span class="arrow">▼</span>
                                    </div>
                                    <div class="section-content">
                                        <div class="client-info"> <!--Dados Profissionais -->
                                            <div class="client-info-pair">
                                                <label for="agente">Agente:</label>
                                                <select id="agente" name="agente" required>
                                                    <option value="">Selecione</option>
                                                    <option value="ANDRIELE REGINA BENETTI" <?= $cliente['agente'] == 'ANDRIELE REGINA BENETTI' ? 'selected' : '' ?>>ANDRIELE REGINA BENETTI</option>
                                                    <option value="LAW HWAN HUEI" <?= $cliente['agente'] == 'LAW HWAN HUEI' ? 'selected' : '' ?>>LAW HWAN HUEI</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="gerente">Gerente:</label>
                                                <select id="gerente" name="gerente" required>
                                                    <option value="">Selecione</option>
                                                    <option value="INGRID BRITO BARBOZA" <?= $cliente['gerente'] == 'INGRID BRITO BARBOZA' ? 'selected' : '' ?>>INGRID BRITO BARBOZA</option>
                                                    <option value="BRUNA BRASIL DA SILVA" <?= $cliente['gerente'] == 'BRUNA BRASIL DA SILVA' ? 'selected' : '' ?>>BRUNA BRASIL DA SILVA</option>
                                                    <option value="CARLA CRISTINA FERREIRA ALEDES" <?= $cliente['gerente'] == 'CARLA CRISTINA FERREIRA ALEDES' ? 'selected' : '' ?>>CARLA CRISTINA FERREIRA ALEDES</option>
                                                    <option value="SIMONE MACENO DA SILVA" <?= $cliente['gerente'] == 'SIMONE MACENO DA SILVA' ? 'selected' : '' ?>>SIMONE MACENO DA SILVA</option>
                                                    <option value="ADRIANA PEREIRA DE LIMA" <?= $cliente['gerente'] == 'ADRIANA PEREIRA DE LIMA' ? 'selected' : '' ?>>ADRIANA PEREIRA DE LIMA</option>
                                                    <option value="AILTON LIRA" <?= $cliente['gerente'] == 'AILTON LIRA' ? 'selected' : '' ?>>AILTON LIRA</option>
                                                    <option value="CAVALCANTI" <?= $cliente['gerente'] == 'CAVALCANTI' ? 'selected' : '' ?>>CAVALCANTI</option>
                                                    <option value="THIAGO SOUZA DOS REIS" <?= $cliente['gerente'] == 'THIAGO SOUZA DOS REIS' ? 'selected' : '' ?>>THIAGO SOUZA DOS REIS</option>
                                                    <option value="JOSMAR MENESES LIMA" <?= $cliente['gerente'] == 'JOSMAR MENESES LIMA' ? 'selected' : '' ?>>JOSMAR MENESES LIMA</option>
                                                    <option value="MANOELA BRITO" <?= $cliente['gerente'] == 'MANOELA BRITO' ? 'selected' : '' ?>>MANOELA BRITO</option>
                                                    <option value="ARTHUR MOREIRA" <?= $cliente['gerente'] == 'ARTHUR MOREIRA' ? 'selected' : '' ?>>ARTHUR MOREIRA</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="profissao">Profissão:</label>
                                                <input type="hidden" id="profissaoAtual" value="<?= htmlspecialchars($cliente['profissao']) ?>">
                                                <select id="profissaoSelect" name="profissao" required></select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="natureza_ocupacao">Natureza Ocupação:</label>
                                                <select id="natureza_ocupacao" name="natureza_ocupacao" required>
                                                    <option value="">Selecione</option>
                                                    <option value="APOSENTADO OU PENSIONISTA" <?= $cliente['natureza_ocupacao'] == 'APOSENTADO OU PENSIONISTA' ? 'selected' : '' ?>>APOSENTADO OU PENSIONISTA</option>
                                                    <option value="ASSALARIADO" <?= $cliente['natureza_ocupacao'] == 'ASSALARIADO' ? 'selected' : '' ?>>ASSALARIADO</option>
                                                    <option value="AUTONOMO" <?= $cliente['natureza_ocupacao'] == 'AUTONOMO' ? 'selected' : '' ?>>AUTÔNOMO</option>
                                                    <option value="EMPRESARIO" <?= $cliente['natureza_ocupacao'] == 'EMPRESARIO' ? 'selected' : '' ?>>EMPRESÁRIO</option>
                                                    <option value="FUNCIONARIO PUBLICO" <?= $cliente['natureza_ocupacao'] == 'FUNCIONARIO PUBLICO' ? 'selected' : '' ?>>FUNCIONÁRIO PÚBLICO</option>
                                                    <option value="LIBERAL" <?= $cliente['natureza_ocupacao'] == 'LIBERAL' ? 'selected' : '' ?>>LIBERAL</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="tempo_prof_anos">Tempo de Empresa (Anos):</label>
                                                <input type="number" id="tempo_prof_anos" name="tempo_prof_anos" value="<?= htmlspecialchars($cliente['tempo_prof_anos']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="tempo_prof_meses">Tempo de Empresa (Meses):</label>
                                                <input type="number" id="tempo_prof_meses" name="tempo_prof_meses" value="<?= htmlspecialchars($cliente['tempo_prof_meses']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="cnpj_integrador">Cnpj Integrador:</label>
                                                <input type="text" id="cnpj_integrador" name="cnpj_integrador" value="<?= htmlspecialchars($cliente['cnpj_integrador']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="integrador">Integrador:</label>
                                                <input type="text" id="integrador" name="integrador" value="<?= htmlspecialchars($cliente['integrador']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="parcela">Parcela:</label>
                                                <select id="parcela" name="parcela" required>
                                                    <option value="">Selecione</option>
                                                    <option value="12" <?= $cliente['parcela'] == '12' ? 'selected' : '' ?>>12</option>
                                                    <option value="24" <?= $cliente['parcela'] == '24' ? 'selected' : '' ?>>24</option>
                                                    <option value="36" <?= $cliente['parcela'] == '36' ? 'selected' : '' ?>>36</option>
                                                    <option value="48" <?= $cliente['parcela'] == '48' ? 'selected' : '' ?>>48</option>
                                                    <option value="60" <?= $cliente['parcela'] == '60' ? 'selected' : '' ?>>60</option>
                                                    <option value="72" <?= $cliente['parcela'] == '72' ? 'selected' : '' ?>>72</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="carencia">Carência:</label>
                                                <select id="carencia" name="carencia" required>
                                                    <option value="">Selecione</option>
                                                    <option value="30" <?= $cliente['carencia'] == '30' ? 'selected' : '' ?>>30</option>
                                                    <option value="60" <?= $cliente['carencia'] == '60' ? 'selected' : '' ?>>60</option>
                                                    <option value="90" <?= $cliente['carencia'] == '90' ? 'selected' : '' ?>>90</option>
                                                    <option value="120" <?= $cliente['carencia'] == '120' ? 'selected' : '' ?>>120</option>
                                                </select>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="valor_projeto">Valor do Projeto:</label>
                                                <input type="text" id="valor_projeto" name="valor_projeto" value="<?= htmlspecialchars($cliente['valor_projeto']) ?>" required>
                                            </div>
                                            <div class="client-info-pair">
                                                <label for="renda_mensal">Renda Mensal:</label>
                                                <input type="text" id="renda_mensal" name="renda_mensal" value="<?= htmlspecialchars($cliente['renda_mensal']) ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Bloco Pessoa Jurídica -->
                                <div id="form-pj" style="display: block;">
                                    <div id="endereco_comercial" style="display: none;">
                                        <div class="section">
                                            <div class="section-header" onclick="toggleSection(this)">
                                                <h2>Dados Empresa</h2>
                                                <span class="arrow">▼</span>
                                            </div>
                                            <div class="section-content">
                                                <div class="client-info"> <!--CNPJ -->
                                                    <div class="client-info-pair">
                                                        <label for="raz_soc">Razão Social:</label>
                                                        <input type="text" id="raz_soc" name="raz_soc" value="<?= htmlspecialchars($cliente['raz_soc']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="nome_fant">Nome Fantasia:</label>
                                                        <input type="text" id="nome_fant" name="nome_fant" value="<?= htmlspecialchars($cliente['nome_fant']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="tempo_emp_ano"> Anos :</label>
                                                        <input type="text" id="tempo_emp_ano" name="tempo_emp_ano" value="<?= htmlspecialchars($cliente['tempo_emp_ano']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="tempo_emp_mes"> Meses:</label>
                                                        <input type="text" id="tempo_emp_mes" name="tempo_emp_mes" value="<?= htmlspecialchars($cliente['tempo_emp_mes']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="cap_social">Capital Social:</label>
                                                        <input type="text" id="cap_social" name="cap_social" value="<?= htmlspecialchars($cliente['cap_social']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="fat_med">Faturamento médio dos últimos 12:</label>
                                                        <input type="text" id="fat_med" name="fat_med" value="<?= htmlspecialchars($cliente['fat_med']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="tel_com">Telefone Comercial:</label>
                                                        <input type="tel" id="tel_com" name="tel_com" value="<?= htmlspecialchars($cliente['tel_com']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="dt_fundacao">Data de Fundação:</label>
                                                        <input type="text" id="dt_fundacao" name="dt_fundacao" value="<?= htmlspecialchars($cliente['dt_nascfund']) ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dados_empresa" style="display: none;">
                                        <div class="section">
                                            <div class="section-header" onclick="toggleSection(this)">
                                                <h2>Endereço Comercial</h2>
                                                <span class="arrow">▼</span>
                                            </div>
                                            <div class="section-content">
                                                <div class="client-info"> <!--Endereço Comercial -->
                                                    <div class="client-info-pair">
                                                        <label for="cep_com">CEP:</label>
                                                        <input type="text" id="cep_com" name="cep_com" value="<?= htmlspecialchars($cliente['cep_com']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="end_com">Endereço:</label>
                                                        <input type="text" id="end_com" name="end_com" value="<?= htmlspecialchars($cliente['end_com']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="nmr_com">Número:</label>
                                                        <input type="text" id="nmr_com" name="nmr_com" value="<?= htmlspecialchars($cliente['nmr_com']) ?>">
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="bairro_com">Bairro:</label>
                                                        <input type="text" id="bairro_com" name="bairro_com" value="<?= htmlspecialchars($cliente['bairro_com']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="cid_com">Cidade:</label>
                                                        <input type="text" id="cid_com" name="cid_com" value="<?= htmlspecialchars($cliente['cid_com']) ?>" required>
                                                    </div>
                                                    <div class="client-info-pair">
                                                        <label for="est_com">Estado:</label>
                                                        <select id="est_com" name="est_com" required>
                                                            <option value="">Selecione</option>
                                                            <option value="AC" <?= $cliente['est_com'] == 'AC' ? 'selected' : '' ?>>AC</option>
                                                            <option value="AL" <?= $cliente['est_com'] == 'AL' ? 'selected' : '' ?>>AL</option>
                                                            <option value="AP" <?= $cliente['est_com'] == 'AP' ? 'selected' : '' ?>>AP</option>
                                                            <option value="AM" <?= $cliente['est_com'] == 'AM' ? 'selected' : '' ?>>AM</option>
                                                            <option value="BA" <?= $cliente['est_com'] == 'BA' ? 'selected' : '' ?>>BA</option>
                                                            <option value="CE" <?= $cliente['est_com'] == 'CE' ? 'selected' : '' ?>>CE</option>
                                                            <option value="DF" <?= $cliente['est_com'] == 'DF' ? 'selected' : '' ?>>DF</option>
                                                            <option value="ES" <?= $cliente['est_com'] == 'ES' ? 'selected' : '' ?>>ES</option>
                                                            <option value="GO" <?= $cliente['est_com'] == 'GO' ? 'selected' : '' ?>>GO</option>
                                                            <option value="MA" <?= $cliente['est_com'] == 'MA' ? 'selected' : '' ?>>MA</option>
                                                            <option value="MT" <?= $cliente['est_com'] == 'MT' ? 'selected' : '' ?>>MT</option>
                                                            <option value="MS" <?= $cliente['est_com'] == 'MS' ? 'selected' : '' ?>>MS</option>
                                                            <option value="MG" <?= $cliente['est_com'] == 'MG' ? 'selected' : '' ?>>MG</option>
                                                            <option value="PA" <?= $cliente['est_com'] == 'PA' ? 'selected' : '' ?>>PA</option>
                                                            <option value="PB" <?= $cliente['est_com'] == 'PB' ? 'selected' : '' ?>>PB</option>
                                                            <option value="PR" <?= $cliente['est_com'] == 'PR' ? 'selected' : '' ?>>PR</option>
                                                            <option value="PE" <?= $cliente['est_com'] == 'PE' ? 'selected' : '' ?>>PE</option>
                                                            <option value="PI" <?= $cliente['est_com'] == 'PI' ? 'selected' : '' ?>>PI</option>
                                                            <option value="RJ" <?= $cliente['est_com'] == 'RJ' ? 'selected' : '' ?>>RJ</option>
                                                            <option value="RN" <?= $cliente['est_com'] == 'RN' ? 'selected' : '' ?>>RN</option>
                                                            <option value="RS" <?= $cliente['est_com'] == 'RS' ? 'selected' : '' ?>>RS</option>
                                                            <option value="RO" <?= $cliente['est_com'] == 'RO' ? 'selected' : '' ?>>RO</option>
                                                            <option value="RR" <?= $cliente['est_com'] == 'RR' ? 'selected' : '' ?>>RR</option>
                                                            <option value="SC" <?= $cliente['est_com'] == 'SC' ? 'selected' : '' ?>>SC</option>
                                                            <option value="SP" <?= $cliente['est_com'] == 'SP' ? 'selected' : '' ?>>SP</option>
                                                            <option value="SE" <?= $cliente['est_com'] == 'SE' ? 'selected' : '' ?>>SE</option>
                                                            <option value="TO" <?= $cliente['est_com'] == 'TO' ? 'selected' : '' ?>>TO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button-salvar">Salvar</button>
                            <button type="reset" class="button-limprar">Limpar</button>
                            <a href="dashboard.php" class="a-button">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Intranet | JNG — <a href="https://www.jng.com.br" target="_blank">GRUPO JNG</a></p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

    <script>
        document.getElementById("currentYear").textContent = new Date().getFullYear();
    </script>

    <script>
        // Função simples de máscara
        function aplicarMascara(input, tipo) {
            input.addEventListener('input', function() {
                let value = input.value.replace(/\D/g, '');

                if (tipo === 'cpf') {
                    if (value.length > 11) value = value.substring(0, 11);
                    value = value.replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }
                if (tipo === 'cnpj') {
                    if (value.length > 14) value = value.substring(0, 14);
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2')
                        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                        .replace(/\.(\d{3})(\d)/, '.$1/$2')
                        .replace(/(\d{4})(\d)/, '$1-$2');
                }
                if (tipo === 'celular') {
                    if (value.length > 11) value = value.substring(0, 11);
                    value = value.replace(/(\d{2})(\d)/, '($1) $2')
                        .replace(/(\d{5})(\d)/, '$1-$2');
                }
                if (tipo === 'rg') {
                    value = value.replace(/\D/g, ''); // remove tudo que não for número

                    if (value.length <= 9) {
                        // Formato tradicional: 9 dígitos
                        value = value.replace(/(\d{2})(\d{3})(\d{3})(\d)/, '$1.$2.$3-$4');
                    } else if (value.length === 11) {
                        // Formato com 11 dígitos
                        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                    }
                }

                if (tipo === 'moeda') {
                    value = (parseInt(value, 10) / 100).toFixed(2) + '';
                    value = value.replace(".", ",");
                    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                }

                if (tipo === 'dt_nascimento') {
                    if (value.length > 9) value = value.substring(0, 11);
                    value = value.replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }


                input.value = value;
            });
        }

        // Detectar automaticamente se é CPF ou CNPJ
        function mascaraCpfCnpj(input) {
            input.addEventListener('input', function() {
                let value = input.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    aplicarMascara(input, 'cpf');
                } else {
                    aplicarMascara(input, 'cnpj');
                }
            });
        }
    </script>

    <script>
        document.getElementById('dt_nascimento').addEventListener('input', function(event) {
            let valor = event.target.value;

            // Limpar tudo o que não for número ou barra
            valor = valor.replace(/[^0-9\/]/g, '');

            // Se o valor tiver mais de 10 caracteres (DD/MM/AAAA), cortar o excesso
            if (valor.length > 10) {
                valor = valor.substring(0, 10);
            }

            // Inserir as barras de forma automática
            if (valor.length > 2 && valor.charAt(2) !== '/') {
                valor = valor.substring(0, 2) + '/' + valor.substring(2);
            }
            if (valor.length > 5 && valor.charAt(5) !== '/') {
                valor = valor.substring(0, 5) + '/' + valor.substring(5);
            }

            // Atualizar o campo de data
            event.target.value = valor;

            // Validar se a data é válida (dia, mês e ano corretos)
            if (valor.length === 10) {
                const [dia, mes, ano] = valor.split('/');
                const data = new Date(ano, mes - 1, dia);

                // Verificar se a data é válida
                if (data.getDate() != dia || data.getMonth() + 1 != mes || data.getFullYear() != ano) {
                    event.target.setCustomValidity("Data de nascimento inválida");
                } else {
                    event.target.setCustomValidity(""); // Remove a mensagem de erro se a data for válida
                }
            }
        });
    </script>

    <script>
        function formatCPFouCNPJ(valor) {
            const apenasNumeros = valor.replace(/\D/g, '');
            if (apenasNumeros.length > 11) {
                return apenasNumeros.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
            } else {
                return apenasNumeros.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4");
            }
        }

        function formatValorBrasileiro(valor) {
            const numero = parseFloat(valor.toString().replace(/\./g, '').replace(',', '.'));
            return isNaN(numero) ? '' : numero.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }


        // Aplica formatação inicial nas <span> ao carregar a página
        window.onload = function() {
            const cpfInput = document.getElementById("cpf");
            const cpfTexto = document.getElementById("cpf-cnpj-text");

            if (cpfInput && cpfTexto) {
                const cpfValor = cpfInput.value;
                cpfTexto.innerText = formatCPFouCNPJ(cpfValor);
            }

            const valorProjetoInput = document.getElementById("valor_projeto");
            const valorProjetoTexto = document.getElementById("valor-projeto-text");

            if (valorProjetoInput && valorProjetoTexto) {
                const valorProjeto = valorProjetoInput.value;
                valorProjetoTexto.innerText = formatValorBrasileiro(valorProjeto);
            }
        };
    </script>

    <script>
        function formatarMoeda(elemento) {
            let valor = elemento.value.replace(/\D/g, '');
            valor = (valor / 100).toFixed(2) + '';
            valor = valor.replace(".", ",");
            valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            elemento.value = valor;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const campos = ['valor_projeto', 'valor_patrimonio'];

            campos.forEach(function(id) {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', function() {
                        formatarMoeda(input);
                    });

                    // Formatar valor inicial
                    if (input.value) {
                        formatarMoeda(input);
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00', {
                reverse: true
            });
            $('#celular').mask('(00) 00000-0000');
            $('#cep').mask('00000-000');
            $('#dt_nascimento').mask('00/00/0000');
            $('#dt_nascfund').mask('00/00/0000');
            $('#dt_fundacao').mask('00/00/0000');

            $('#valor_patrimonio').mask('000.000.000,00', {
                reverse: true
            });
            $('#renda_mensal').mask('000.000.000,00', {
                reverse: true
            });
            $('#cap_social').mask('0.000.000.000,00', {
                reverse: true
            });
            $('#fat_med').mask('0.000.000.000,00', {
                reverse: true
            });
            $('#valor_projeto').mask('000.000.000,00', {
                reverse: true
            });
        });
    </script>

    <script>
        function toggleSection(header) {
            const section = header.parentElement;
            section.classList.toggle('open');
        }
    </script>

    <script>
        function onlyNumbers(str) {
            return str.replace(/\D/g, '');
        }

        function atualizarFormulario() {
            const campo = document.getElementById("cpf_cnpj");
            if (!campo) return;

            const valor = campo.value || '';
            const numeroLimpo = onlyNumbers(valor);
            const isCNPJ = numeroLimpo.length === 14;

            const tipoPessoa = isCNPJ ? "Avalista" : "Pessoais";

            const tipoSpan = document.getElementById("exibirTipoPessoa");
            if (tipoSpan) tipoSpan.textContent = tipoPessoa;

            const formPJ = document.getElementById("form-pj");
            if (formPJ) formPJ.style.display = isCNPJ ? "block" : "none";

            console.log("Tipo definido:", tipoPessoa);
        }

        document.addEventListener("DOMContentLoaded", function() {
            atualizarFormulario(); // executa ao carregar
            const campo = document.getElementById("cpf_cnpj");
            if (campo) campo.addEventListener("input", atualizarFormulario);
        });
    </script>

    <script>
        function aplicarMascaraCPFouCNPJ(valor) {
            const numeros = valor.replace(/\D/g, '');

            if (numeros.length <= 11) {
                // Máscara CPF: 000.000.000-00
                return numeros.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, function(_, p1, p2, p3, p4) {
                    return `${p1}.${p2}.${p3}-${p4}`.replace(/[-.]$/, '');
                });
            } else {
                // Máscara CNPJ: 00.000.000/0000-00
                return numeros.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, function(_, p1, p2, p3, p4, p5) {
                    return `${p1}.${p2}.${p3}/${p4}-${p5}`.replace(/[-/.]$/, '');
                });
            }
        }

        function isCNPJ(value) {
            value = value.replace(/\D/g, '');
            return value.length === 14;
        }

        function isCPF(value) {
            value = value.replace(/\D/g, '');
            return value.length === 11;
        }

        function toggleCamposPorTipoPessoa() {
            const campo = document.getElementById("cpf_cnpj");
            const valor = campo.value;
            const numeros = valor.replace(/\D/g, '');

            // Aplica máscara no campo
            campo.value = aplicarMascaraCPFouCNPJ(valor);

            // Mostra/esconde blocos conforme tipo
            const dadosEmpresa = document.getElementById("dados_empresa");
            const enderecoComercial = document.getElementById("endereco_comercial");
            const formCPF = document.getElementById("form-cpf");

            if (isCNPJ(valor)) {
                if (dadosEmpresa) dadosEmpresa.style.display = "block";
                if (enderecoComercial) enderecoComercial.style.display = "block";
                if (formCPF) formCPF.style.display = "none";
                if (formPJ) formPJ.style.display = "block";
                document.getElementById("exibirTipoPessoa").textContent = "Avalista";
            } else {
                if (dadosEmpresa) dadosEmpresa.style.display = "none";
                if (enderecoComercial) enderecoComercial.style.display = "none";
                if (formCPF) formCPF.style.display = "block";
                if (formPJ) formPJ.style.display = "none";
                document.getElementById("exibirTipoPessoa").textContent = "Pessoais";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const campo = document.getElementById("cpf_cnpj");
            if (!campo) return;

            campo.addEventListener("input", toggleCamposPorTipoPessoa);
            toggleCamposPorTipoPessoa(); // Executa ao carregar
        });
    </script>


    <script>
        form.addEventListener("submit", function(event) {
            let valid = true;
            // sua lógica de verificação
            if (!campoObrigatorioVisivel.value) {
                valid = false;
                alert("Preencha o campo obrigatório.");
                campoObrigatorioVisivel.focus();
            }

            if (!valid) {
                event.preventDefault(); // impede envio do formulário
            }
        });
    </script>

    <script>
        document.querySelectorAll("form [required]").forEach(function(input) {
            if (input.offsetParent === null || input.disabled) {
                input.removeAttribute("required");
            }
        });
    </script>

    <script>
        const clienteProfissao = "<?= addslashes($clienteProfissao) ?>";
    </script>
    <script src="./profissao.js"></script>

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

    <script>
        function toggleMenu() {
            const nav = document.getElementById('navLinks');
            nav.classList.toggle('show');
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>

</html>

<?php
// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>