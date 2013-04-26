Convención de nombres para el código liberado bajo PHP-FIG
===============================================

1. Los interfaces TIENEN QUE tener el sufijo `Interface`: p.ej. `Psr\Foo\BarInterface`.
2. Las clases abstractas TIENEN QUE llevar el prefijo `Abstract`: p.ej. `Psr\Foo\AbstractBar`.
3. Los traits TIENEN QUE tener el sufijo `Trait`: p.ej. `Psr\Foo\BarTrait`.
4. PSR-0, 1 y 2 TIENEN QUE cumplirse.
5. El nombre de proveedor TIENE QUE ser `Psr`.
6. TIENE QUE haber un espacio de nombres del paquete de segundo nivel en relación al PSR que engloba el código.
7. El paquete Composer TIENE QUE llamarse `psr/<paquete>` p.ej. `psr/log`. Si necesita una implementación como paquete virtual, éste TIENE QUE nombrarse `psr/<paquete>-implementation` y requiere especificar una versión como `1.0.0`. Los implementadores de PSR pueden proveer `"psr/<paquete>-implementation":"1.0.0"` en su paquete para satisfacer este requerimiento. Los cambios en la especificación a través de los PSRs deben llevar a una nueva etiqueta del paquete `psr/<paquete>`, y requiere una versión ajustada a la aplicación.