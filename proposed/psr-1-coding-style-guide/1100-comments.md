Comments and DocBlocks
======================

This guide recognizes that different projects have different documentation tools and requirements.  In the absence of project-specific requirements, documentation should follow the standard set out by PHPDocumentor, DocBlox, etc.

Class files should have at least two sets of documentation blocks:  a file-level block above the namespace declaration, and a class-specific block above the class declaration.

    <?php
    /**
     * 
     * This is a file-level documentation block.
     * 
     * @package Vendor\Package
     * 
     * @license ...
     * 
     */
    namespace Vendor\Package;
    
    /**
     * 
     * This is a class-level documentation block.
     * 
     */
    class ClassName
    {
        // class body
    }

This guide strongly encourages the documentation of each constant, property, method, method argument, and thrown exception in each class file.

This guide strongly encourages non-documentation comments as well, if only as a tool to aid in comprehension of the code being commented on.  (Self-commenting code is frequently so only for the original author.)

Comments should use only the `/* */` and `//` style. Do not use the `#` commenting style.

Use `example.com`, `example.org`, and `example.net` for all example domain names in documentation.

