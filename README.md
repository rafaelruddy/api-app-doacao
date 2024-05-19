# Nome do Projeto

## Descrição
Este projeto tem o objetivo de criar uma API para servir o App de doação e um dashboard administrativo de instituições de doações. 

## Pré-requisitos
- PHP 8.2 ou superior
- Composer instalado globalmente
- Banco de dados MySQL

## Instalação
1. Clone o repositório do projeto:
    ```bash
    git clone https://github.com/rafaelruddy/api-app-doacao.git
    ```

2. Navegue até o diretório do projeto:
    ```bash
    cd nome-do-projeto
    ```

3. Instale as dependências do Composer:
    ```bash
    composer install
    ```

4. Copie o arquivo de ambiente de exemplo e configure suas variáveis de ambiente:
    ```bash
    cp .env.example .env
    ```

5. Gere a chave de aplicação:
    ```bash
    php artisan key:generate
    ```

6. Execute as migrações do banco de dados:
    ```bash
    php artisan migrate
    ```

7. Gere o secret do JWT:
    ```bash
    php artisan jwt:secret
    ```

8. Inicie o servidor de desenvolvimento:
     ```bash
    php artisan serve
    ```

## Uso
Após seguir as etapas de instalação, você pode acessar o projeto em seu navegador usando o URL `http://localhost:8000`.

## Contribuição
Contribuições são bem-vindas! Para contribuir com o projeto, siga estes passos:
1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/sua-feature`)
3. Faça commit de suas alterações (`git commit -am 'Adicionar nova feature'`)
4. Faça push para a branch (`git push origin feature/sua-feature`)
5. Abra uma solicitação de pull request

## Licença
Este projeto está licenciado sob a [Licença MIT](https://opensource.org/licenses/MIT).
