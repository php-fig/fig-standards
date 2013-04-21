Convención de nombres para el código liberado bajo PHP-FIG
===============================================

1. Los interfaces TIENEN QUE tener el sufijo `Interface`: p.ej. `Psr\Foo\BarInterface`.
2. las clases abstractas TIENEN QUE llevar el prefijo `Abstract`: p.ej. `Psr\Foo\AbstractBar`.
3. Los traits TIENEN QUE tener el sufijo `Trait`: p.ej. `Psr\Foo\BarTrait`.
4. PSR-0, 1 y 2 TIENEN QUE cumplirse.
5. El nombre de proveedor TIENE QUE ser `Psr`.
6. TIENE QUE haber una namespace del paquete de segundo nivel en relación al PSR que encapsula el código.
7. El paquete Composer TIENE QUE llamarse `psr/<paquete>` p.ej. `psr/log`. Si necesita una implementación como un paquete virtual, éste TIENE QUE nombrarse `psr/<paquete>-implementation` y se requiere especificar una versión como `1.0.0`. Los implementadores de PSR pueden proveer `"psr/<paquete>-implementatio":"1.0.0"` en su paquete para satisfacer el requerimiento. Los cambios en la especificación a través de los PSRs deben llevar a una nueva etiqueta del paquete `psr/<paquete>`, y se requiere una versión ajustada a la aplicación.