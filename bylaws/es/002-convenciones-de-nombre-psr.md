Convención de nombres para código liberado por PHP-FIG
------------------------------------------------------

1. Las interfaces DEBEN incluir el sufijo `Interface`: p.ej. `Psr\Foo\BarInterface`.
2. Las clases abstractas `Abstract` DEBEN incluir el prefijo `Abstract`: p.ej. `Psr\Foo\AbstractBar`.
3. Los Traits DEBEN incluir el sufijo `Trait`: p.ej. `Psr\Foo\BarTrait`.
4. PSR-0, 1 y 2 DEBE ser seguido.
5. El nombre del paquete del proveedor DEBE ser `Psr`.
6. DEBE existir un paquete de segundo nivel `paquete/segundo-nivel` en relación con el PSR
   que cubre el código.
7. El paquete de `Composer` DEBE llamarse `psr/<paquete>` p.ej. `psr/log`. Si requiere una
   implementación como un paquete virtual DEBE nombrarse `psr/<package>-implementation` y si
   fuera necesario con una versión específica como `1.0.0`. Los Implementors de este PSR
   pueden proporcionar `"psr/<package>-implementation": "1.0.0"` en su paquete, para cumplir
   con este requisito. Los cambios en la especificación de los PSRs sólo tendrán que generar
   una etiqueta nueva del paquete `psr/<package>`, y la versión de la implementación.
