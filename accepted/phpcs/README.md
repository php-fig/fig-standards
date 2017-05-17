===========================
PSR-0 PHP_CodeSniffer Sniff
===========================

Purpose
=======
This Sniff is to be integrated in your [*PHP_CodeSniffer*][1] ruleset to observe the compatability of your project
to the [PSR-0 standard][2].

Installation
============
Getting the sources
-------------------
The installation is pretty simple just clone the sources to your favorite directory. 
This might be the FIG-Standards directory in your home directory.

    $> git clone git://github.com/lapistano/fig-standards.git ~/fig-standards

Setting up
----------
If you do not want to move or link the sniffer anywhere, skip this section and move over to __Usage__.
Otherwise you have multiple choices at this point, either leave the the sniff where they are right now and link 
them to the *Standards* directory of your PHP_CodeSniffer installation (e.g. /usr/share/php/PHP/CodeSniffer/Standards)
or you copy them to this directory. I propose you do just the linking to make it easy to update, in case the 
sniffer changes.

Usage
=====
Mainly the usage is described on the [*PHP_CodeSniffer manual page*][1] on the PEAR website. 
But here is a short how to, if you linked or copied the sniffer to your PHP_CodeSniffer installation directory.
Open the command line and change to your project directory or the directory to be verified. Then enter

    $>phpcs --standard=Psr0 --extensions=php ./

and PHP_CodeSniffer will do its magic.

Links
=====
[1]: http://pear.php.net/manual/en/package.php.php-codesniffer.php
[2]: http://groups.google.com/group/php-standards