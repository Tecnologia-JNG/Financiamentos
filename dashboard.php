<?php
date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=UTF-8');

$servername = 'GRPJNG011204080'; // ou IP do servidor MySQL
$username = 'root';
$password = 'password';
$database = 'FINANCEIRAS';

// Conexão com MySQL
$conn = mysqli_connect($servername, $username, $password, $database);
mysqli_set_charset($conn, "utf8");

$whereClause = "";
$data_inicial = $_GET['data_inicial'] ?? '';
$data_final = $_GET['data_final'] ?? '';
$whereClause = "";
$limitClause = "LIMIT 20"; // padrão

if (
    preg_match('/\d{4}-\d{2}-\d{2}/', $data_inicial) &&
    preg_match('/\d{4}-\d{2}-\d{2}/', $data_final)
) {
    // Se datas válidas forem fornecidas, filtra pelo intervalo e REMOVE o LIMIT
    $whereClause = "WHERE DATE(data_input) BETWEEN '$data_inicial' AND '$data_final'";
    $limitClause = ""; // Remove o LIMIT
}


if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}

function isTrueValue($value)
{
    $value = strtolower(trim($value));
    return in_array($value, ['sim', 's', 'sim.', 'yes', '✔']);
}

function formatCpfCnpj($value)
{
    $value = preg_replace('/\D/', '', $value);
    if (strlen($value) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
    } elseif (strlen($value) === 14) {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value);
    }
    return $value;
}
// Consulta TOP 20
$sql = "SELECT id, cpf_cnpj, nome, celular, email, rg, dt_nascimento, nacionalidade, genero, estado_civil, valor_patrimonio, nome_mae, cep, endereco, numero, bairro, cidade, estado, tipo_imovel,
    natureza_ocupacao, profissao, tempo_prof_anos, tempo_prof_meses, renda_mensal, integrador, agente, gerente, valor_projeto, parcela, carencia, data_input,
    banco_bv, banco_santander, simulacao_bv, simulacao_sant, status
    FROM clientes 
    $whereClause
    ORDER BY id DESC 
    $limitClause";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="180">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://www.jng.com.br/site/img/favicon.ico">
    <title>Acompanhamento das Simulações</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
            background-color: #003366;
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

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
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
            background: #003366;
        }

        .dark-mode .a-button {
            background: #003366;
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

        .dark-mode .modal-content {
            background-color: #121212;
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
            height: auto;
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
            padding: 6px 12px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
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
        <div class="container">
            <h1>Acompanhamento das Simulações</h1>
            <div class="form">
                <div style="overflow-x:auto;">
                    <form method="get">
                        <table>
                            <tr>
                                <th>Data Inicial</th>
                                <th colspan="2">Data Final</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="date" name="data_inicial" value="<?= htmlspecialchars($_GET['data_inicial'] ?? '') ?>">
                                </td>
                                <td>
                                    <input type="date" name="data_final" value="<?= htmlspecialchars($_GET['data_final'] ?? '') ?>">
                                </td>
                                <td>
                                    <button type="submit" class="button">Confirmar</button>
                                </td>
                            </tr>
                        </table>
                    </form>


                    <table>
                        <tr>
                            <th>CPF/CNPJ</th>
                            <th>Data de Cadastro</th>
                            <th>Proposta</th>
                            <th>Simulação</th>
                            <th>Banco BV</th>
                            <th>Banco Santander</th>
                        </tr>
                        <!-- while para criar tabela com as informações do banco de dados -->
                        <?php while ($row = mysqli_fetch_assoc($result)):

                            $id = $row['id'];
                            $cpf               = $row['cpf_cnpj'];
                            $parcela           = $row['parcela'];
                            $banco_bv          = trim($row['banco_bv'] ?? '');
                            $banco_santander   = trim($row['banco_santander'] ?? '');
                            $simulacao_bv      = trim($row['simulacao_bv'] ?? '');
                            $simulacao_sant    = trim($row['simulacao_sant'] ?? '');
                            $statusValue       = strtolower(trim($row['status'] ?? ''));

                            $dataInput = '';
                            if (!empty($row['data_input'])) {
                                $dataInput = date('d/m/Y H:i', strtotime($row['data_input']));
                            }


                            // Se NÃO tem banco_bv E NÃO tem banco_santander
                            if (empty($banco_bv) && empty($banco_santander)) {
                                echo "<tr>
                        <td>" . formatCpfCnpj($cpf) . "</td>
                        <td>$dataInput</td>
                        <td></td>
                        <td></td>
                        <td colspan='2' class='aguardando'>⏳ Aguardando Processamento</td>
                        </tr>";
                                continue;
                            }

                            // banco_bv
                            if (strtoupper($banco_bv) === 'S' &&  $statusValue === 'processado') {
                                $bvIcon = '✅ Processado';
                            } elseif (strtoupper($banco_bv) === 'S' &&  $statusValue === 'integrado') {
                                $bvIcon = '💰 Integrado';
                            } elseif (strtoupper($banco_bv) === 'N' &&  $statusValue === 'err cpf/cnpj') {
                                $bvIcon = '❌ ERR CPF/CNPJ';
                            } else {
                                $bvIcon = '⏳ Aguardando';
                            }

                            //  banco Santander
                            if (strtoupper($banco_santander) === 'S' &&  $statusValue === 'processado') {
                                $santanderIcon = '✅ Processado';
                            } elseif (strtoupper($banco_santander) === 'S' &&  $statusValue === 'integrado') {
                                $santanderIcon = '💰 Integrado';
                            } elseif (strtoupper($banco_santander) === 'N' &&  $statusValue === 'err cpf/cnpj') {
                                $santanderIcon = '❌ ERR CPF/CNPJ';
                            } else {
                                $santanderIcon = '⏳ Aguardando';
                            }

                            // Simulação Banco BV
                            if (!empty($simulacao_bv)) {
                                $simulacaoBvLabel = htmlspecialchars($simulacao_bv);
                            } elseif (in_array(strtolower($banco_bv), ['n', 'nao']) && $statusValue === 'err cpf/cnpj') {
                                $simulacaoBvLabel = '❌ ERR CPF/CNPJ';
                            } elseif (in_array(strtolower($banco_bv), ['s', 'sim'])) {
                                $simulacaoBvLabel = '⚠️ Sem resultado';
                            } else {
                                $simulacaoBvLabel = '⏳ Aguardando';
                            }

                            // Simulação Santander
                            if (!empty($simulacao_sant)) {
                                $simulacaoSantLabel = htmlspecialchars($simulacao_sant);
                            } elseif (in_array(strtolower($banco_santander), ['n', 'nao']) && $statusValue === 'err cpf/cnpj') {
                                $simulacaoSantLabel = '❌ ERR CPF/CNPJ';
                            } elseif (in_array(strtolower($banco_santander), ['s', 'sim'])) {
                                $simulacaoSantLabel = '⚠️ Sem resultado';
                            } elseif (is_null($banco_santander)) {
                                $simulacaoSantLabel = '⏳ Não processado';
                            } else {
                                $simulacaoSantLabel = '⏳ Aguardando';
                            }

                        ?>
                            <tr>
                                <td><?= formatCpfCnpj($cpf) ?></td>
                                <td><?= $dataInput ?></td>
                                <td>
                                    <a href="dadospessoais.php?id=<?= htmlspecialchars($id, ENT_QUOTES) ?>" class="a-button">Continuar Proposta</a>
                                </td>
                                <td>
                                    <button class="btn-simulacao"
                                        data-Simulacao-Bv="<?= $simulacaoBvLabel ?>"
                                        data-Simulacao-Santander="<?= $simulacaoSantLabel ?>">Ver</button>
                                </td>
                                <td><?= $bvIcon ?></td>
                                <td><?= $santanderIcon ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
                <div id="modalSimulacao" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close close-simulacao" style="float:right;cursor:pointer;">&times;</span>
                        <h3>Simulação</h3>
                        <div id="contentSimulacao" style="white-space: pre-wrap; "></div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Intranet | JNG — <a href="https://www.jng.com.br" target="_blank">GRUPO JNG</a></p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const labels = {
                simulacaobv: "Simulação BV",
                simulacaosantander: "Simulação Santander"
            };


            document.querySelectorAll(".btn-simulacao").forEach(btn => {
                btn.addEventListener("click", function() {
                    const fields = this.dataset;
                    let html = "<table border='1'><tr>";

                    // Cabeçalhos
                    for (const key in fields) {
                        const label = labels[key.toLowerCase()] || key.replace(/-/g, ' ');
                        html += `<th>${label}</th>`;
                    }

                    html += "</tr><tr>";

                    // Conteúdo
                    for (const key in fields) {
                        html += `<td>${fields[key]}</td>`;
                    }

                    html += "</tr></table>";

                    document.getElementById("contentSimulacao").innerHTML = html;
                    document.getElementById("modalSimulacao").style.display = "block";
                });
            });

            document.querySelector(".close-simulacao").onclick = () => {
                document.getElementById("modalSimulacao").style.display = "none";
            };
        });
    </script>

    <script>
        document.getElementById("currentYear").textContent = new Date().getFullYear();
    </script>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
        }
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