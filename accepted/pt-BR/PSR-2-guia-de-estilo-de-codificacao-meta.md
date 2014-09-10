PSR-2 Meta Documento
====================

1. Resumo
---------

A intenção deste guia é reduzir a fricção cognitiva quando lendo códigos de diferentes autores. Isto é feito enumerando um conjunto de regras compartilhadas e expectativas de como formatar códigos em PHP.

As regras de estilo incluídas aqui são derivadas de semelhanças entre vários projetos membros. Quando vários autores colaboram entre múltiplos projetos, ajuda ter um conjunto de princípios básicos à ser utilizado em todos os projetos. Assim, o benefício deste guia não está nas regras em sí, mas no compartilhamento destas regras.


2. Votos
--------

- **Votos de Aceitação:** [ML](https://groups.google.com/d/msg/php-fig/c-QVvnZdMQ0/TdDMdzKFpdIJ)


3. Errata
---------

1. _[09/08/2013]_ Utilizando um ou mais argumentos multi-linhas (ex: arrays ou funções anônimas) não constitui em dividir a lista de argumentos em sí, por isso, a seção 4.6 não é automaticamente aplicada. Arrays e funções anônimas conseguem atravessar múltiplas linhas.

Os exemplos a seguir são perfeitamente válidos na PSR-2:

```php
<?php
somefunction($foo, $bar, [
  // ...
], $baz);

$app->get('/hello/{name}', function ($name) use ($app) { 
    return 'Hello '.$app->escape($name); 
});
```

2. _[10/17/2013]_ Quando extendendo múltiplas interfaces, a lista de `extends` deveria ser tratada do mesmo jeito que uma lista de`implements`, como declarado na seção 4.1.

