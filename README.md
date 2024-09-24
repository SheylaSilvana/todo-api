# To-Do List API

## Descrição

Esta API foi desenvolvida para gerenciar tarefas (To-Do List) e autenticação de usuários, utilizando o Lumen e PostgreSQL. A API oferece autenticação via JWT, gerenciamento de tarefas, integração com o Google Calendar para criação e atualização automática de eventos, além de envio de e-mails automáticos configurados com o SMTP do Gmail. O projeto é containerizado com Docker para facilitar o ambiente de desenvolvimento e execução.

## Funcionalidades

### 1. Autenticação de Usuários:

- **Registro**: Criação de novos usuários com envio de e-mail contendo uma senha gerada automaticamente.
- **Login**: Geração de token JWT para acessar rotas protegidas.
- **Logout**: Invalidação do token JWT do usuário autenticado.
- **Exibição de Informações**: Recuperação dos dados do usuário autenticado.
- **Redefinição de Senha**: Envio automático de uma nova senha por e-mail, com invalidade do token JWT anterior.

### 2. Gestão de Tarefas:

- **CRUD de Tarefas**: Criar, listar, atualizar e excluir tarefas.
- **Filtros**: 
  - Filtragem de tarefas por status, data de criação, intervalo de datas, título e descrição.
  - Busca geral no título ou descrição da tarefa.
- **Integração com Google Calendar**:
  - **Criação de eventos**: As tarefas criadas são sincronizadas como eventos no Google Calendar.
  - **Atualização de eventos**: Atualizações nas tarefas refletem nos eventos do Google Calendar associados.
  - **Exclusão de eventos**: A exclusão de tarefas remove o evento correspondente do Google Calendar.
- **Contagem de Tarefas por Status**: Exibe a quantidade de tarefas agrupadas por status (A Fazer, Feitas).
- **Tarefas por Intervalo de Datas**: Permite listar tarefas criadas em um intervalo específico de datas.
- **Tarefas Recentes**: Exibe as tarefas criadas nos últimos 7 dias.
- **Tarefas com Informações de Usuários**: Permite listar tarefas com informações detalhadas dos usuários associados.

### 3. Gestão de Usuários:

- **Listagem de Usuários**: Visualização de usuários cadastrados com suporte a paginação.
- **Atualização de Usuários**: Atualização das informações dos usuários, como nome.
- **Exclusão de Usuários**: Remoção de usuários do sistema.
- **Redefinição de Senha**: Permite redefinir a senha de um usuário e enviar a nova senha por e-mail automaticamente.

### 4. Integração com Google Calendar:

- A API permite a sincronização automática das tarefas com o Google Calendar. Isso inclui a criação, atualização e exclusão de eventos no calendário correspondente às tarefas gerenciadas na aplicação.

### 5. Notificações:

- **Envio de E-mails**: 
  - Um e-mail é enviado quando uma nova tarefa é criada.
  - Notificações também são enviadas quando o status da tarefa é atualizado (de "A Fazer" para "Feitas").

## Configuração do Projeto

### Pré-requisitos
Para rodar este projeto, certifique-se de que os seguintes pré-requisitos estão atendidos:
- **Lumen** => `10.0`
- **Docker** versão => `27.2.0`
- **Docker Compose** versão => `2.29.2`
- **PHP** versão => `8.3.9`
- **Composer** versão => `2.7.9`
- **PostgreSQL** versão => `16.4`
- **Google Calendar API**
- **JWT (JSON Web Token)**
- **SMTP (Gmail)**
- **PHPUnit**

### Variáveis de Ambiente

#

O arquivo `.env` deve ser configurado da seguinte maneira:
1. Cópia do .env.example: Copie o arquivo .env.example para .env.
```
cp .env.example .env
```
2. Configuração do Banco de Dados Local (para desenvolvimento fora do Docker)
Se você estiver rodando o banco de dados localmente (fora do Docker), a configuração do `.env` para o banco de dados defina os dados assim:
```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=nome_do_banco
DB_USERNAME=nome_do_usuario
DB_PASSWORD=senha_do_banco 
```
- Quando estiver rodando com Docker, o `DB_HOST` deve ser o nome do serviço definido no `docker-compose.yml`.Exemplo: `DB_HOST=db`.

3. Geração da Chave Secreta do JWT: Para gerar a chave secreta do JWT, utilize o comando Artisan dentro do container ou localmente (se o ambiente estiver configurado):

Dentro do container Docker (recomendado):
```
docker-compose exec app php artisan jwt:secret
```
Isso vai gerar e configurar automaticamente a chave secreta JWT_SECRET no arquivo .env.

Ou, manualmente, você pode usar:
```
php artisan jwt:secret
```
4. Configuração do SMTP (Gmail): Para enviar e-mails utilizando o Gmail em localhost, siga os passos para gerar uma senha de aplicativo no Gmail:
    - Primeiro, certifique-se de que a verificação em duas etapas está habilitada na sua conta do Google. Vá até as Configurações de Segurança da Conta Google e ative a verificação em duas etapas, se ainda não estiver ativada.
    - Encontre a seção "Senhas de app".
    - Na tela de Senhas de App, escolha "Selecionar aplicativo" no primeiro dropdown.
    - Escolha "Outro (nome personalizado)" e digite o nome do aplicativo (exemplo: "To-Do List API").
    - Clique em Gerar para obter uma senha de app.
    - A senha gerada será exibida em uma janela pop-up. Copie essa senha, pois você precisará dela na configuração do seu .env.
Atualize os campos relacionados ao e-mail no .env com as credenciais geradas:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD="senha_gerada_do_app"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@gmail.com
MAIL_FROM_NAME="To-Do List"
```
### Configuração do Google Calendar localmente
#
1. Criar Projeto no Google Cloud:

    -  Vá até Google Cloud Console.
    - Crie um novo projeto ou utilize um existente.
2. Ativar a API do Google Calendar:

    - Acesse a API Library no console e ative a **Google Calendar API**.
3. Criar Credenciais de OAuth 2.0:

    - Vá em **APIs e Serviços > Credenciais**.
    - Selecione **Criar credenciais > ID do cliente OAuth 2.0**.
    - Defina o tipo de aplicativo (por exemplo, Aplicativo Web) e adicione o URI de redirecionamento (ex: `http://localhost:8080`).
    - Salve o arquivo JSON gerado, que contém o `client_id`, `client_secret`, entre outros.
4. Criar uma Conta de Serviço:

    - No mesmo menu de Credenciais, selecione **Criar credenciais > Conta de Serviço**.
    - Salve o arquivo JSON da conta de serviço.
    - No arquivo JSON da conta de serviço, você encontrará um campo chamado `client_email`, que será algo como `your-service-account@project-id.iam.gserviceaccount.com`.
    - Acesse o Google Calendar em calendar.google.com.
    - Na barra lateral esquerda, encontre o calendário no qual você deseja que a API crie eventos.
    - Clique nos três pontos ao lado do nome do calendário e selecione Configurações e compartilhamento.
    - Role para baixo até a seção Compartilhar com pessoas específicas.
    - Clique em Adicionar pessoas e insira o `client_email` da conta de serviço, como `todolist@todo-list-436417.iam.gserviceaccount.com`.
    - Defina a permissão como Fazer alterações nos eventos para permitir que a conta de serviço possa criar, editar e excluir eventos.
    - Salve as alterações.
### Subindo o Ambiente com Docker

1.  **Build e Start dos Containers**: No diretório raiz do projeto, execute:
    ```
    docker-compose up --build -d
    ```
    
2.  **Migrações do Banco de Dados**: Após os containers estarem ativos, execute:
    ```
    docker-compose exec app php artisan migrate
    ```
    

## Diagrama Entidade-Relacionamento (DER)
O Diagrama Entidade-Relacionamento (DER) abaixo foi gerado utilizando o **pgAdmin** e descreve as tabelas e seus relacionamentos no banco de dados **PostgreSQL**:

![DER ToDoList](https://github.com/user-attachments/assets/2d5de2b9-325b-458b-8eb9-17d16c69b77e)

## Testes

O projeto contém testes automatizados utilizando **PHPUnit** para garantir que todas as funcionalidades principais, como autenticação, gestão de tarefas e gestão de usuários, estejam funcionando corretamente.

Como Rodar os Testes:
1. Gerar Token JWT e Criar um Usuário Caso Não Exista:
    - O primeiro teste irá verificar se existe um usuário registrado. Caso contrário, será criado um novo usuário, uma senha aleatória será gerada e enviada para o e-mail configurado no projeto.
    - Se o usuário já existir, o teste utilizará as credenciais disponíveis para gerar o token JWT, necessário para autenticar as requisições subsequentes.
2. Se você está rodando os testes localmente fora do Docker, pode usar o seguinte comando:
```
vendor/bin/phpunit tests/TestLogoutTest.php
```
3. Quando você está rodando o ambiente com Docker, o comando deve ser executado dentro do container, usando `docker-compose exec`:
```
docker-compose exec app vendor/bin/phpunit tests/TestLogoutTest.php
```
## Postman Collection

Para facilitar o teste da API, você pode importar a **Postman Collection** incluída no projeto:

1.  Abra o **Postman**.
2.  Clique em **Import** e selecione o arquivo da [coleção do postman](postman/api-to-do-collection.json) no diretório do projeto.
3.  Configure o ambiente no Postman, criando variáveis para o **token JWT**.

## Executando Jobs de Fila

O projeto está configurado para utilizar filas de trabalho para processar tarefas em segundo plano, como o envio de e-mails e a notificação de mudanças no estado das tarefas. Quando executado dentro do Docker, o worker da fila será iniciado automaticamente.

### Execução Automática no Docker

No ambiente Docker, o worker de filas é configurado para rodar automaticamente. Ele irá monitorar as filas e processar tarefas, como envio de e-mails e notificações de mudanças de estado nas tarefas.

### Execução Manual Fora do Docker

Se estiver executando o projeto fora do Docker, ou se desejar reiniciar manualmente o worker, utilize o seguinte comando:

```
php artisan queue:work
```
## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para enviar **Pull Requests** com melhorias ou abrir um **Issue** para reportar problemas ou solicitar novas funcionalidades.
## Licença

Este projeto está licenciado sob a [MIT License](./LICENSE). Isso significa que você pode usar, modificar e distribuir o código conforme as condições da licença.

Consulte o arquivo [LICENSE](./LICENSE) para obter mais detalhes.
