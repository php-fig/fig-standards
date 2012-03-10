Introduction
============

This guide is derived from commonalities between Aura, Doctrine, Horde, PEAR,
PPI, Solar, Symfony, Zend Framework, and other projects. The intent is not to
dictate an abritrary set of requirements, but to reduce cognitive friction
when scanning code from different projects by providing a common set of rules
and expectations.

Overview
--------

The following is an overview of the rules; please review the remainder of this
guide for details.

- Use only `<?php` and `<?=` opening tags for PHP code; leave out the closing
  `?>` tag when the file contains only PHP code.

- Limit line length to 80 characters; use 4 spaces for indenting, not tabs;
  do not add trailing spaces at the end of lines.

- Namespace all classes; place one blank line after the `namespace`
  declaration, and one blank line after the block of `use` declarations.

- Declare class names in `StudlyCaps`; opening braces for classes go on the
  next line, and closing braces go on their own line.

- Declare method names in `camelCase`; opening braces for methods go on the
  next line, and closing braces go on their own line.

- Declare visibility on all properties and methods; static declarations come
  before the visbility declaration.
  
- Control structure keywords have one space after them; function calls do not.

- Opening braces for control structures go on the same line, and closing
  braces go on their own line.
