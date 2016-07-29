# RApi
Cliente PHP para API REST

##Instalação
Clone ou baixe o repositório.

##Exemplos
#####Instanciando a classe
```php
use App\RApi;
```
##Métodos

######Configurações
```php
RApi::setConfig('host','http://api.domain.com');

//Podemos setar varias config de uma vez.
RApi::setConfig(array(
	'host' => 'http://api.domain.com',
	'ssl' => 'cert.pem'
));
```
######Métodos disponíveis
*get*,*post*,*put*,*delete*

######Funções disponíveis
*fields()*,*header()*,*cookie*,*run()*

######GET
```php
//Todas as requisições devem conter uma chamada a função *run()*

RApi::get('/')->run();

//Passando parâmetros
$res = RApi::get('/')->fields(['user'=>'test','pass'=>'***'])->run();

print $res->response;

```

##Testes
Instale o [composer](https://getcomposer.org)
```sh
//Instalando as dependências necessárias
composer install

//Rodando os testes
phpunit
```