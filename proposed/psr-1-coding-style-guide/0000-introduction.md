Introduction
============

The intent of this guide is not to dictate an abritrary set of requirements,
but to reduce cognitive friction when scanning code from different projects by
providing a common set of rules and expectations. It is derived from
commonalities among the various member projects.


Overview
--------

The following is an overview of the rules; please review the remainder of this
guide for details.

- Use only `<?php` and `<?=` opening tags for PHP code; leave out the closing
  `?>` tag when the file contains only PHP code.

- Use 4 spaces for indenting, not tabs.

- There is no hard limit on line length; the soft limit is 120 characters;
  lines of 80 characters or less are strongly encouraged. Do not add trailing
  whitespace at the end of lines. Use Unix line endings (LF).

- Namespace all classes; place one blank line after the `namespace`
  declaration, and one blank line after the block of `use` declarations.

- Declare class names in `StudlyCaps`; opening braces for classes go on the
  next line, and closing braces go on their own line.

- Declare method names in `camelCase`; opening braces for methods go on the
  next line, and closing braces go on their own line.

- Declare visibility on all properties and methods; `static` declarations come
  before the visbility declaration; `final` declarations come before `static`
  and visibility.
  
- Control structure keywords have one space after them; function calls do not.

- Opening braces for control structures go on the same line, and closing
  braces go on their own line.

- Opening parentheses for control structures have no space after them, and
  closing parentheses for control structures have no space before.
