<?php
    date_default_timezone_set('America/Sao_Paulo');
    header('Content-Type: text/html; charset=UTF-8');

    $servername = 'GRPJNG011204080';
    $username = 'root';
    $password = 'password';
    $database = 'FINANCEIRAS';

    // Conexão com MySQL
    $conn = mysqli_connect($servername, $username, $password, $database);
    mysqli_set_charset($conn, "utf8");

    $date_inicial = isset($_GET['date_inicial']) ? mysqli_real_escape_string($conn, $_GET['date_inicial']) : null;
    $date_final = isset($_GET['date_final']) ? mysqli_real_escape_string($conn, $_GET['date_final']) : null;

    $whereClause = "";
    if ($date_inicial && $date_final) {
        $whereClause = "WHERE DATE(data_input) BETWEEN '$date_inicial' AND '$date_final'";
    }

    if (!$conn) {
        die("Erro de conexão: " . mysqli_connect_error());
    }

    function isTrueValue($value) {
        $value = strtolower(trim($value));
        return in_array($value, ['sim', 's', 'sim.', 'yes', '✔']);
    }

    function formatCpfCnpj($value) {
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
        natureza_ocupacao, profissao, tempo_empresa_anos, tempo_empresa_meses, renda_mensal, integrador, agente, gerente, valor_projeto, parcela, carencia, data_input,
        banco_bv, banco_santander, simulacao_bv, simulacao_sant, data_inicial, data_final
        FROM clientes 
        $whereClause
        ORDER BY id DESC";

    if (!$date_inicial || !$date_final) {
        $sql .= " LIMIT 20";
    }
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Erro na consulta: " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" sizes="32x32">
    <title>Acompanhamento das Simulações</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style>
        @font-face {
            font-family: 'Aptos Display';
            src: url('AptosDisplay.woff2') format('woff2'),
                url('AptosDisplay.woff') format('woff');
        }

        h3 {
            margin-bottom: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            outline: none;
            box-sizing: border-box; 
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


        body {
            font-family: 'Aptos Display';
            background-image: linear-gradient(to right, #003676, #00aae3);
            height:100%;
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
            padding: 10px;
            margin: 20px;
        }

        .input-row {
            flex: 1 1 45%; /* ajusta para 2 colunas */
            display: flex;
            flex-direction: column;
        }

        .input-row label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-row input {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .input-row input,
        .input-row select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .full {
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        .btn-simulacao {
            background-color: #001d40;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn-simulacao:hover {
            background-color: #0091E4;
        }

        .button{
            background-color: #001d40;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0091E4;
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


        .select2::-ms-expand {
            display: none;
        }
        .select2::after {
            content: '\25BC';
            position: absolute;
            top: 0;
            right: 0;
            padding: 1em;
            background-color: #34495e;
            transition: .25s all ease;
            pointer-events: none;
        }


        .select2{
            border: 0;
            box-shadow: none;
            padding: 0 1em;
            color: #fff;
            background-color: transparent;
            background-image: none;
            height: 3em;
            border-radius: .25em;
            color: black;
            overflow: hidden;
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

        .form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 900px;
            box-shadow: 0 0 10px #00000030;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }


        table {
            border-radius: 10px;
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            border-radius: 10px;
            background: #001d40;
            color: white;
        }
        .aguardando {
            color: orange;
            font-weight: bold;
            text-align: center;
        }


        .container-footer {
            display: flex;
            justify-content: space-between;
            color: white;
            padding: 10px;
        }

        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; }
        .close:hover, .close:focus { color: black; text-decoration: none; cursor: pointer; }

        @media (max-width: 1024px) {
            .box-text {
                margin-left: 0;
                text-align: center;
            }

            .box-imagem {
                margin-left: 0;
                text-align: center;
            }

            .container {
                padding: 10px;
                flex-direction: column;
            }

            form, .form {
                width: 100%;
                padding: 20px;
            }

            .input-row {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            
            .container-footer {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
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

    </style>
</head>
<body>
    <header>
        <navbar>
            <div class="box">
                <img src="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" width="100px" class="box-imagem">
            </div>
            <a href="../forms-jng/forms.php" class="link">Cadastro de Financiamentos</a>
            <a href="./dashboard.php" class="link">Simulações</a>
        </navbar>
    </header>
    <h1>Acompanhamento das Simulações</h1>
    <div class="container">
        <div class="form">
            <div style="overflow-x:auto;">
            <form method="get">
                    <table>
                        <tr>
                            <th>Data Inicial</th>
                            <th colspan="2">Data Final</th>
                        </tr>
                        <tr>
                            <td ><input type="date" name="date_inicial" id="date_inicial" value="<?= $_GET['date_inicial'] ?? '' ?>"></td>
                            <td><input type="date" name="date_final" id="date_final" value="<?= $_GET['date_final'] ?? '' ?>"></td>
                            <td><button type="submit" class="button">Confirmar</button></td>
                        </tr>
                    </table>
                    <div class="full">
                        
                    </div>
            </form>
                <table>
                    <tr>
                        <th>CPF/CNPJ</th>
                        <th>Data de Cadastro</th>
                        <th>Simulação</th>
                        <th>Parcelas</th>
                        <th>Banco BV</th>
                        <th>Banco Santander</th>
                    </tr>

                    <?php while ($row = mysqli_fetch_assoc($result)):

                        $id = $row['id'];
                        $cpf               = $row['cpf_cnpj'];
                        $dt_nascimento     = $row['dt_nascimento'];
                        $parcela           = $row['parcela'];
                        $banco_bv          = trim($row['banco_bv'] ?? '');
                        $banco_santander   = trim($row['banco_santander'] ?? '');
                        $simulacao_bv      = trim($row['simulacao_bv'] ?? '');
                        $simulacao_sant    = trim($row['simulacao_sant'] ?? '');
                        $data_inicial      = trim($row['data_inicial'] ?? '');
                        $data_final        = trim($row['data_final'] ?? '');

                        $dataInput = '';
                        if (!empty($row['data_input'])) {
                            $dataInput = date('d/m/Y H:i', strtotime($row['data_input']));
                        }

                        // Se NÃO tem banco_bv E NÃO tem banco_santander
                        if (empty($banco_bv) && empty($banco_santander)) {
                            echo "<tr>
                                    <td>" . formatCpfCnpj($cpf) . "</td>
                                    <td>$dataInput</td>
                                    <td>-</td>
                                    <td>
                                        <button class='btn-simulacao'
                                            data-Simulacao--Banco='" . htmlspecialchars($simulacao_bv, ENT_QUOTES) . "'  
                                            data-Simulacao--Santander='" . htmlspecialchars($simulacao_sant, ENT_QUOTES) . "' 
                                        >Ver</button>
                                    </td>
                                    <td colspan='2' class='aguardando'>⏳ Aguardando Processamento</td>
                                </tr>";
                            continue; // Pula para o próximo registro
                        }

                        // Se tem banco_bv ou banco_santander
                        $bvIcon = isTrueValue($banco_bv) ? '✅' : '❌';
                        $santanderIcon = isTrueValue($banco_santander) ? '✅' : '❌';
                        ?>

                        <tr>
                        <td><?= formatCpfCnpj($cpf) ?></td>
                        <td><?= $dataInput ?></td>
                        <td>
                            <a href="dadospessoais.php?id=<?= htmlspecialchars($id, ENT_QUOTES) ?>" style="text-decoration: none; color: white;" class="button">Continuar Simulação</a>
                        </td>
                        <td>
                            <button class="btn-simulacao"
                                data-Simulacao--Banco="<?= htmlspecialchars($simulacao_bv) ?>"
                                data-Simulacao--Santander="<?= htmlspecialchars($simulacao_sant) ?>"
                            >Ver</button>
                        </td>
                        <td><?= $bvIcon ?></td>
                        <td><?= $santanderIcon ?></td>
                        </tr>

                    <?php endwhile; ?>

                </table>
            </div>
            <div id="modalSimulacao" class="modal">
                    <div class="modal-content">
                        <span class="close close-simulacao">&times;</span>
                            <h3>Parcelas<h3>
                            <div id="contentSimulacao"></div>
                    </div>
            </div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".btn-simulacao").forEach(btn => {
            btn.addEventListener("click", function () {
                document.querySelectorAll(".btn-simulacao").forEach(b => b.removeAttribute("data-last-clicked"));
                this.setAttribute("data-last-clicked", "true");

                const fields = this.dataset;
                let html = "<table>";
                
                // Cabeçalho da tabela
                html += "<tr>";
                for (const key in fields) {
                    if (key.startsWith("simulacao")) {
                        const label = key.replace(/-/g, " ").replace(/\b\w/g, l => l.toUpperCase());
                        html += `<th>${label}</th>`;  // Adiciona os cabeçalhos
                    }
                }
                html += "</tr>";

                // Dados da tabela
                html += "<tr>";
                for (const key in fields) {
                    if (key.startsWith("simulacao")) {
                        html += `<td>${fields[key]}</td>`;  // Adiciona os dados
                    }
                }
                html += "</tr>";

                html += "</table>";
                document.getElementById("contentSimulacao").innerHTML = html;
                document.getElementById("modalSimulacao").style.display = "block";
            });
        });
            const closeSimulacao = document.querySelector(".close-simulacao");
            closeSimulacao.onclick = () => document.getElementById("modalSimulacao").style.display = "none";
        });
    </script>
    <footer>
        <div class="container-footer">

            <p class="credits-left">
                © 2024 <a href="/home.html" class="footer-link">Intranet | JNG</a>
            </p>
            
            <p class="credits-right">
                <span>Desenvolvido por Tecnologia <a href="http://jng.com.br" class="footer-link">JNG</a></span>
            </p>
        </div> 
    </footer>
</body>
</html>
