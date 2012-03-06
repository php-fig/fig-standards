Errors and Exceptions
=====================

PHP code must not emit notices, warnings, or errors under E_ALL error reporting.

Use of raise_error() is discouraged; throw Exceptions instead.

Returning error codes or error statuses from functions and checking is acceptable and encouraged for normal program flow over the use of Exceptions with try-catch blocks.

Make as much use of SPL exceptions as possible. <http://php.net/manual/en/spl.exceptions.php>

Vendor packages should define a top-level exception of their own, to make it easy to catch package-specific exceptions.
