<?php
    date_default_timezone_set('America/Sao_Paulo');
    header('Content-Type: text/html; charset=UTF-8');

    $servername = 'GRPJNG011204080';
    $username = 'root';
    $password = '';
    $database = 'FINANCEIRAS';

    // Conexão com MySQL
    $conn = mysqli_connect($servername, $username, $password, $database);
    mysqli_set_charset($conn, "utf8");

    if (!$conn) {
        die("Erro de conexão: " . mysqli_connect_error());
    }

    // Funções
    function limparNumero($valor) {
        if ($valor === null || $valor === '') return 0;
        return floatval(str_replace(['.', ','], ['', '.'], $valor));
    }

    function limparDocumento($valor) {
        return preg_replace('/\D/', '', $valor);
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
            $cliente = mysqli_fetch_assoc($result);
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
        $cpf_cnpj = limparDocumento($_POST['cpf_cnpj'] ?? '');
        $nome = $_POST['nome'];
        $celular = limparDocumento($_POST['celular'] ?? '');
        $email = $_POST['email'];
        $rg = $_POST['rg'];
        $data_nascimento = limparDocumento($_POST['dt_nascimento'] ?? '');
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
        $tempo_empresa_anos = $_POST['tempo_empresa_anos'] ?? '';
        $tempo_empresa_meses = $_POST['tempo_empresa_meses'] ?? '';
        $renda_mensal = $_POST['renda_mensal'] ?? '';
        $integrador = $_POST['integrador'];
        $parcela = $_POST['parcela'];
        $carencia = $_POST['carencia'];
        $agente = $_POST['agente'];
        $gerente = $_POST['gerente'];
        $valor_projeto = intval(limparNumero($_POST['valor_projeto'] ?? ''));

        $sql_update = "UPDATE clientes SET 
                        cpf_cnpj = '$cpf_cnpj', 
                        nome = '$nome',
                        celular = '$celular',
                        email = '$email',
                        rg = '$rg',
                        dt_nascimento = '$data_nascimento',
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
                        tempo_empresa_anos = '$tempo_empresa_anos',
                        tempo_empresa_meses = '$tempo_empresa_meses',
                        renda_mensal = '$renda_mensal',
                        integrador = '$integrador',
                        parcela = '$parcela',
                        carencia = '$carencia',
                        agente = '$agente',
                        gerente = '$gerente',
                        valor_projeto = '$valor_projeto',
                        status = 'simular'
                    WHERE id = '$id'";

        if (mysqli_query($conn, $sql_update)) {
            header("Location: ".$_SERVER['PHP_SELF']."?id=".$id."&sucesso=1");
            exit;
        } else {
            echo "Erro ao atualizar os dados: " . mysqli_error($conn);
        }
    }
?>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        alert('✅ Dados inseridos com sucesso!');
        setTimeout(function () {
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
        @font-face {
                    font-family: 'Aptos Display';
                    src: url('AptosDisplay.woff2') format('woff2'),
                    url('AptosDisplay.woff') format('woff');
        }

        h3 {
            margin-bottom: 20px;
        }
        /* Estilo para os rótulos */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        /* Estilo para os campos de input */
        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
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
        select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
            outline: none;
        }

        /* Estilo para os textos de leitura (não editáveis) */
        .client-info-pair span {
            display: inline-block;
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #333;
            font-size: 16px;
            text-align: left;
        }

        /* Estilo para o botão de editar */


            * {
                margin: 0;
                padding: 0;
                outline: none;
                box-sizing: border-box; 
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
                gap: 40px; /* espaçamento entre os blocos */
                flex-wrap: wrap; /* para responsividade */
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

            .button-salvar{
                background-color:rgb(5, 64, 0);
                color: white;
                padding: 10px 10px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
            }

            .button-salvar:hover{
                background-color: rgba(5, 64, 0, 0.67);
            }

            .button-link{
                background-color: #001d40;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                padding: 7px 20px;
                text-decoration: none; 
            }

            .button-link:hover {
                background-color: #0091E4;
            }

            .button-editar{
                background-color:rgb(253, 230, 21);
                color: black;
                padding: 10px 20px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
            }

            .button-editar:hover{
                background-color:rgba(253, 230, 21, 0.68);
            }

            .button-limprar{
                background-color:rgb(190, 11, 11);
                color: white;
                padding: 10px 20px;
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

            .link {
                color: white;
                text-decoration: none; 
                margin: 0 15px;
                font-size: 16px;
            }

            .link:hover {
                color: #0091E4;
            }

            footer {
                background-color: #000000;
                bottom:0px;
                width:100%;
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


            table {
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
                background: #003676;
                color: white;
            }

            .container-footer {
                display: flex;
                justify-content: space-between;
                color: white;
                padding: 10px;
            }

            .client-info {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                width: 100%;
                max-width: 1100px;
                margin: 30px auto;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 8px;
                border: 1px solid #ddd;
            }

            .client-info-pair {
                flex: 1 1 calc(50% - 20px);
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 280px;
            }

            .client-info-pair label {
                font-weight: bold;
                min-width: 140px;
                color: #333;
            }

            .client-info-pair span {
                background-color: #e9e9e9;
                padding: 6px 10px;
                border-radius: 5px;
                flex: 1;
            }

            .btn-back {
                display: inline-block;
                background-color: #001d40;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                margin-top: 20px;
                text-align: center;
            }

            .btn-back:hover {
                background-color: #0091E4;
                color: white;
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
</head>
<body>
    <header>
        <navbar>
            <div class="box">
                <img src="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" width="100px" class="box-imagem">
            </div>
        </navbar>
    </header>
    <h1>Dados Pessoais do Cliente</h1>
    <div class="container">
    <form method="POST">
        <div class="client-info">
            <div class="client-info-pair" id="cpf-cnpj-wrapper">
                <label for="cpf">CPF/CNPJ:</label>
                <span id="cpf-cnpj-text"><?= htmlspecialchars($cliente['cpf_cnpj']) ?></span>
                <input type="text" id="cpf" name="cpf_cnpj" value="<?= htmlspecialchars($cliente['cpf_cnpj']) ?>" required style="display: none;">
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
            <div class="client-info-pair" id="data-nascimento-wrapper">
                <label for="data_nascimento">Data de Nascimento:</label>
                <span id="data-nascimento-text"><?= date('d/m/Y', strtotime($cliente['dt_nascimento'])) ?></span>
                <input type="date" id="data_nascimento" name="dt_nascimento" value="<?= date('Y-m-d', strtotime($cliente['dt_nascimento'])) ?>" required style="display: none;">
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
                    <option value="Solteiro(a)" <?= $cliente['estado_civil'] == 'Solteiro(a)' ? 'selected' : '' ?>>Solteiro(a)</option>
                    <option value="Casado(a)" <?= $cliente['estado_civil'] == 'Casado(a)' ? 'selected' : '' ?>>Casado(a)</option>
                    <option value="Divorciado(a)" <?= $cliente['estado_civil'] == 'Divorciado(a)' ? 'selected' : '' ?>>Divorciado(a)</option>
                    <option value="Viúvo(a)" <?= $cliente['estado_civil'] == 'Viúvo(a)' ? 'selected' : '' ?>>Viúvo(a)</option>
                    <option value="Separado(a)" <?= $cliente['estado_civil'] == 'Separado(a)' ? 'selected' : '' ?>>Separado(a)</option>
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
            <div class="client-info-pair" id="estado-wrapper">
                <label for="estado">Estado:</label>
                <span id="estado-text"><?= htmlspecialchars($cliente['estado']) ?></span>
                <input type="text" id="estado" name="estado" value="<?= htmlspecialchars($cliente['estado']) ?>" required style="display: none;">
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
                <label for="profissao">Profissão:</label>
                <input type="text" id="profissao" name="profissao" value="<?= htmlspecialchars($cliente['profissao']) ?>" required>
            </div>
            <div class="client-info-pair">
                <label for="tempo_empresa_anos">Tempo de Empresa (Anos):</label>
                <input type="number" id="tempo_empresa_anos" name="tempo_empresa_anos" value="<?= htmlspecialchars($cliente['tempo_empresa_anos']) ?>" required>
            </div>
            <div class="client-info-pair">
                <label for="tempo_empresa_meses">Tempo de Empresa (Meses):</label>
                <input type="number" id="tempo_empresa_meses" name="tempo_empresa_meses" value="<?= htmlspecialchars($cliente['tempo_empresa_meses']) ?>" required>
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
                </select>
            </div>
            <div class="client-info-pair">
                <label for="carencia">Carência:</label>
                <input type="text" id="carencia" name="carencia" value="<?= htmlspecialchars($cliente['carencia']) ?>" required>
            </div>
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
            

            <div class="client-info-pair" id="valor-projeto-wrapper">
                <label for="valor_projeto">Valor do Projeto:</label>
                <span id="valor-projeto-text"><?= htmlspecialchars($cliente['valor_projeto']) ?></span>
                <input type="text" id="valor_projeto" name="valor_projeto" value="<?= htmlspecialchars($cliente['valor_projeto']) ?>" required style="display: none;">
            </div>

            <div class="full">
                <button type="submit" class="button-salvar">Atualizar Dados</button>
                <button type="button" id="editBtn" onclick="toggleEditMode()" class="button-editar">Editar</button>
                <button type="reset"  class="button-limprar">Limpar</button>
                <a href="dashboard.php" class="button-link">Voltar</a>
            </div>
        </div>
    </form>

    </div>
    <footer>
        <div class="container-footer">

            <p class="credits-left">
                © <span id="currentYear"></span> <a href="/home.html" class="link">Intranet | JNG</a>
            </p>
            
            <p class="credits-right">
                <span>Desenvolvido por Tecnologia <a href="http://jng.com.br" class="link">JNG</a></span>
            </p>
        </div> 
    </footer>

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
            if (value.length > 9) value = value.substring(0, 9);
            value = value.replace(/(\d{2})(\d)/, '$1.$2')
                         .replace(/(\d{3})(\d)/, '$1.$2')
                         .replace(/(\d{3})(\d)/, '$1-$2');
        }
        if (tipo === 'moeda') {
            value = (parseInt(value, 10) / 100).toFixed(2) + '';
            value = value.replace(".", ",");
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
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

<!-- Inclua estas duas bibliotecas no <head> ou antes do script abaixo -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

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
    return isNaN(numero) ? '' : numero.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}


    function toggleEditMode() {
        const isEditing = document.getElementById("cpf").style.display === "block";

        document.getElementById("cpf-cnpj-text").style.display = isEditing ? "block" : "none";
        document.getElementById("cpf").style.display = isEditing ? "none" : "block";

        document.getElementById("data-nascimento-text").style.display = isEditing ? "block" : "none";
        document.getElementById("data_nascimento").style.display = isEditing ? "none" : "block";

        document.getElementById("estado-text").style.display = isEditing ? "block" : "none";
        document.getElementById("estado").style.display = isEditing ? "none" : "block";

        document.getElementById("valor-projeto-text").style.display = isEditing ? "block" : "none";
        document.getElementById("valor_projeto").style.display = isEditing ? "none" : "inline-block";

        if (!isEditing) {
            // Máscaras nos inputs
            $('#cpf').unmask().mask('000.000.000-00', {
                onKeyPress: function (cpf, e, field, options) {
                    const isCNPJ = cpf.replace(/\D/g, '').length > 11;
                    $(field).unmask();
                    if (isCNPJ) {
                        $(field).mask('00.000.000/0000-00');
                    } else {
                        $(field).mask('000.000.000-00');
                    }
                }
            });

            $('#data_nascimento').mask('00/00/0000');
            $('#valor_projeto').mask('#.##0,00', { reverse: true });

            // Aplica formatação visual nos <span>
            const cpfValor = document.getElementById("cpf").value;
            document.getElementById("cpf-cnpj-text").innerText = formatCPFouCNPJ(cpfValor);

            const valorProjeto = document.getElementById("valor_projeto").value;
            document.getElementById("valor-projeto-text").innerText = formatValorBrasileiro(valorProjeto);
        }
    }

    // Aplica formatação inicial nas <span> ao carregar a página
    window.onload = function () {
        const cpfValor = document.getElementById("cpf").value;
        document.getElementById("cpf-cnpj-text").innerText = formatCPFouCNPJ(cpfValor);

        const valorProjeto = document.getElementById("valor_projeto").value;
        document.getElementById("valor-projeto-text").innerText = formatValorBrasileiro(valorProjeto);
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

    document.addEventListener('DOMContentLoaded', function () {
        const campos = ['valor_projeto', 'valor_patrimonio'];

        campos.forEach(function (id) {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function () {
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#celular').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    $('#rg').mask('00.000.000-0');
    $('#valor_patrimonio, #valor_projeto').mask('#.##0,00', {reverse: true});
});
</script>

</body>
</html>

<?php
// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
