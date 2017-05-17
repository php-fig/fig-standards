PSR-1 Meta Document
===================

1. Summary
----------

The intent of this guide is to establish common basic standards for coding style,
emphasizing those that would have an impact on the PHP runtime environment.


2. Errata
---------

1. _[12/04/2013]_ When this PSR was approved, the Framework Interoperability Group
had published a single autoloading specification, PSR-0. Thus, this PSR includes
the text "Namespaces and classes MUST follow PSR-0".  Since this PSR was approved
the FIG has also published an updated autoloading specification, PSR-4. As written,
PSR-1 is thus incompatible with PSR-4. The intent, however, was not to codify
PSR-0 as such but to codify using a portable and well-understood autoloading
convention.

Instead, implementers SHOULD interpret that line in PSR-1 to require compliance
with PSR-0, PSR-4, or any future autoloading specification published by the FIG.
Compliance with any such FIG-published autoloading specification is sufficient
for PSR-1 compliance.
