# Gabinete Digital

## Clonar o Repositório Git

Para começar, clone este repositório Git executando o seguinte comando:

```
git clone https://github.com/JairoTSantos/gab
```
Coloque todos os arquivo na pasta da sua hospedagem.


## Configurar as Variáveis de Ambiente

Antes de executar a aplicação, é necessário configurar as variáveis de configuração. Modifique o arquivo `/app/config/config.php` na raiz do projeto com as seguintes variáveis:

```
return [
    'db' => [
        'host' => 'HOST DO BANCO DE DADOS',
        'username' => 'USUARIO DO BANCO DE DADOS',
        'password' => 'SENHA',
        'database' => 'NOME DO BANCO DE DADOS'
    ],
    'master_user' => [
        'name' => 'NOME DO USUÁRIO ADMINISTRATIVO',
        'email' => 'EMAIL DO USUÁRIO ADMINISTRATIVO',
        'pass' => 'SENHA'
    ],
    'app' => [
        'maximum_file_size' => 5, //tamanho máximo de upload de arquivos. Padrão 5mb
        'permitted_files' => ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'doc']  //tipos de arquivos permitidos para upload
    ],
    'deputado' => [
        'id_deputado' => 00000000, //ID DO DEPUTADO DO GABINETE 
        'nome_deputado' => 'Nome parlamentar', //NOME DO DEPUTADO DO GABINETE
        'estado_deputado' => 'XX' //ESTADO DO DEPUTADO
    ]
];
```
## Sincronizar as tabelas do banco
Importe o sript sql no seu banco de dados. /app/mysql/db.sql


## Primero acesso

Acesse `/pasta_da_hospedagem` e faça login com o usuário administrativo e crie sua nova conta.

## Novos usuários

Para permitir que outros usuário criem suas contas, acesse `/pasta_da_hospedagem/public/cadastro.php` e peça para que eles preencham os campos. Cada novo usuário estará desativado necessitando que um usuário administrativo ative sua conta.