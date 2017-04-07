# Carga automática de clases de clases

En el documento original se usa el RFC 2119 para el uso de las palabras 
MUST, MUST NOT, SHOULD, SOULD NOT y MAY. Para que la traducción sea lo 
más fiel posible, se traducira siempre MUST como el verbo deber en presente 
(DEBE, DEBEN), SHOULD como el verbo deber en condicional (DEBERÍA, DEBERÍAN) 
y el verbo MAY como el verbo PODER.


## 1. Visión General

Este PSR describe una especificación para la [carga automática de clases][] desde 
rutas de archivo. Es totalmente interoperable y se puede utilizar conjuntamente 
con cualquier otra especificación de auto carga, incluyendo [PSR-0][]. Este documento
también describe donde colocar los archivos de las clases para que se carguen
 automáticamente de acuerdo a la especificación


## 2. Especificación

1. El término "clase" se refiere a clases, interfaces, traits y otras estructuras similares.

2. Un espacio de nombres y clase completamente cualificada debe tener la siguiente estructura:

        \<Paquete>\(<SubPaquete>)*\<Nombre de clase>
        
    1. El nombre de la clase completamente cualificada DEBE tener un espacio de nombres de nivel 
       superior, también conocido como "Espacio de nombres del proveedor".
    
    2. El nombre de la clase completamente cualificada PUEDE tener uno o más sub-espacios de nombres. 
    
    3. El nombre de la clase completamente cualificada DEBE acabar con un nombre de clase.
    
    4. Los guiones bajos no tienen ningún significado especial en ninguna parte
       del nombre de la clase completamente cualificada.
       
    5. Los caracteres alfabéticos en los nombres de proveedor, espacios de nombres y nombres de clase 
       pueden contener cualquier combinación de mayúsculas y minúsculas.
    
    6. Todos los nombres de las clases DEBEN referenciarse teniendo en cuenta las mayúsculas y minúsculas.

3. Cuando se carga un archivo correspondiente a un nombre de clase completamente cualificada ...

    1. Una serie contigua de uno o más espacios raíz y sub-espacios de nombres, sin incluir
       el separador del espacio de nombres raíz, en el nombre de la clase completamente cualificada
       ("prefijo del espacio de nombres") se corresponden al menos a un "directorio base".
    
    2. El sub-espacio de nombres contiguo al "prefijo del espacio de nombres" corresponde
       a un sub-directorio dentro de "directorio base", en el que los separadores de espacios de nombres 
       representan separadores de directorio. El nombre del sub-directorio DEBE coincidir con el nombre
       del sub-espacio teniendo en cuenta mayúsculas y minúsculas.
       
    3. El nombre de la clase corresponde al nombre del archivo añadiéndole el sufijo `.php`.
       El nombre del fichero DEBE coincidir con el nombre de la clase teniendo en cuenta mayúsculas
       y minúsculas.
       
4. Las implementaciones de auto carga NO DEBEN lanzar excepciones ni errores de ningún nivel,
   y NO DEBERÍAN retornar ningún valor.


## 3. Ejemplos

La siguiente tabla muestra la correspondencia entre rutas de archivo, nombres de clase completamente cualificadas,
prefijo del espacio de nombres y directorio base.

|  Clase Completamente Cualificada | Prefijo            | Directorio Base          | Ruta de archivo final
| -------------------------------- |--------------------|--------------------------|--------------------------------------------
| \Acme\Log\Writer\File_Writer     | Acme\Log\Writer    | ./acme-log-writer/lib/   | ./acme-log-writer/lib/File_Writer.php
| \Aura\Web\Response\Status        | Aura\Web           | /path/to/aura-web/src/   | /path/to/aura-web/src/Response/Status.php
| \Symfony\Core\Request            | Symfony\Core       | ./vendor/Symfony/Core/   | ./vendor/Symfony/Core/Request.php
| \Zend\Acl                        | Zend               | /usr/includes/Zend/      | /usr/includes/Zend/Acl.php


Como ejemplo de implementación de auto carga de clases según esta especificación,
se pueden ver los [archivos de ejemplo][]. Estos ejemplos NO DEBEN considerarse parte
de la especificación y PUEDEN cambiar en todo momento.

[carga automática de clases]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[archivos de ejemplo]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt