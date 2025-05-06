# 🚗 Sistema de Reserva de Veículos

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Sistema simples para gerenciamento de reservas de veículos, desenvolvido em PHP com MySQL. Permite cadastrar, listar e reservar veículos de forma prática e eficiente, ideal para pequenas empresas ou uso interno.

## 💰 Financiamentos

Gerencie e acompanhe financiamentos de forma simples e eficiente. Este projeto oferece uma interface intuitiva para o cadastro de dados pessoais, preenchimento de formulários financeiros e visualização de informações relevantes.

---

## ⚡ Links Rápidos

- 📂 [Repositório no GitHub](https://github.com/Tecnologia-JNG/Financiamentos)
- 🧾 Documentação em breve
- 🧪 Demonstração em breve

---

## 🧩 Funcionalidades

- 📋 Cadastro e edição de dados pessoais  
- 📝 Formulários dinâmicos para dados financeiros  
- 📊 Dashboard com informações detalhadas (últimos 20 clientes, status de simulações)  
- 🔔 Alertas de status de financiamento  
- 🔒 Segurança e privacidade dos dados (validações front‑end e back‑end)  

---

## 🗂️ Estrutura do Projeto

```
Financiamentos/
├── index.php              # Aplicação única com dashboard, modal, formulário e lógica
├── schema.sql             # Script de criação da tabela `clientes`
├── assets/
│   ├── css/               # Estilização visual do sistema
│   │   └── style.css
│   ├── js/                # Scripts de interação (jQuery)
│   │   └── main.js
│   └── fonts/             # Fontes utilizadas (ex: AptosDisplay)
└── README.md              # Este arquivo
```

---

## 🛠️ Tecnologias Utilizadas

- **PHP puro**  
- **MySQL** (via mysqli)  
- **HTML5** e **CSS3**  
- **JavaScript** (jQuery)  
- **Fonte personalizada:** AptosDisplay  

---

## 🔧 Configuração & Execução

1. Clone este repositório:
   ```bash
   git clone https://github.com/Tecnologia-JNG/Financiamentos.git
   ```
2. Importe o script `schema.sql` no seu MySQL para criar a tabela `clientes`.
3. Ajuste credenciais de banco em `index.php`:
   ```php
   $db_host = 'localhost';
   $db_user = 'seu_usuario';
   $db_pass = 'sua_senha';
   $db_name = 'financiamentos';
   ```
4. Copie a pasta para o diretório do servidor (ex: `htdocs` no XAMPP).
5. Acesse no navegador:
   ```
   http://localhost/Financiamentos/index.php
   ```

---

## 🤝 Contribuindo

1. Faça um **fork** deste repositório.  
2. Crie uma **branch** para sua feature:
   ```bash
   git checkout -b feature/nome-da-feature
   ```
3. Commit suas mudanças:
   ```bash
   git commit -m "Descrição da feature"
   ```
4. Envie para o repositório remoto:
   ```bash
   git push origin feature/nome-da-feature
   ```
5. Abra um **Pull Request**.

---

## 📄 Licença

Este projeto está sob a licença **MIT**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 📣 Contato

- **Autor:** Tecnologia JNG  
- **E-mail:** ti@jng.com  

---

> “O sucesso nasce do querer, da determinação e da vontade de se chegar a um ideal.” – Napoleon Hill

