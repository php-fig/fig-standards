Error Reporting
===============

PHP code must not emit notices, warnings, or errors under `E_ALL` error
reporting. This is because certain styles are parsed and executed (e.g.
failing to declare variables, failing to quote strings, etc.) but still show
up as notices. Coding with `E_ALL` turned on forces a consistent technical
style.
