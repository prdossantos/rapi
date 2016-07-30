# RApi
Cliente PHP para API REST

## Instalação
Clone ou baixe o repositório.

## Exemplos
##### Instanciando a classe
```php
use App\RApi;
```
###### Configurações
```php
RApi::setConfig('host','http://api.domain.com');

//Podemos setar varias config de uma vez.
RApi::setConfig(array(
	'host' => 'http://api.domain.com',
	'ssl' => 'cert.pem'
));
```
###### GET
```php
//Todas as requisições devem conter uma chamada a função run()

RApi::get('/')->run();

//Passando parâmetros, o retorno será um objeto
$res = RApi::get('/')
       ->fields(['user'=>'test','pass'=>'***'])
       ->run();

//Passando parâmetros e informado headers, o retorno será um objeto
$res = RApi::get('/')
       ->fields(['user'=>'test','pass'=>'***'])
       ->header(['Content-Type: application/json', 'Accept: application/json'])
       ->run();       
#output
$res->response; // Resultado da consulta
$res->info;     // array com informações da requisição
$res->header;   // array com header do resultado da consulta
```
## Métodos disponíveis
* get 
* post 
* put 
* delete

## Funções disponíveis
* fields(array $fields) 
* header(array $headers) 
* cookie(string $cookie) 
* run()

##Testes
Instale o [composer](https://getcomposer.org)
```sh
//Instalando as dependências necessárias
composer install

//Rodando os testes
phpunit
```