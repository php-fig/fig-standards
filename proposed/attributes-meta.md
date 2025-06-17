# Attributes Meta Document

## 1. Summary

This Working Group aims to create and manage a shared set of PHP attributes that may be used by many different actors within the PHP ecosystem, avoiding duplication and redundancy.

## 2. Why Bother?

Right now libraries that want to use attributes either have to define them themselves or rely upon 
another library that has them defined. Most of the time that also means to import all the code of 
the other library. 

Userland libraries have no interest in provising an interoperable set of attributes as they solve 
a specific problem with *their* set of attributes. 

This registry shall solve that

## 3. Scope

### 3.1 Goals

* Define and maintain a set of attribute definitions that are relevant for a reasonably broad portion of the PHP ecosystem so that different tools and users can use and rely upon a defined set of attributes with less duplication.
* Provide one or multiple composer-installable packages containing 
  the code for the attributes in the registry
* provide methods to maintain the registry and add, modify and remove attributes from it

### 3.2 Non-Goals

* The goal is **not** to provide a registry of **all** attributes available in PHP. As an example: The ORM-specific
  attributes from Doctrine are likely not to be part of this registry as they are specific to the library itself.

## 4. Approaches

TBD


## 5. People

### 5.1 Editor

 * Andreas Heigl

### 5.2 Sponsor

 * Vincent de Lau

### 5.3 Working group members

 * Juliette Reinders-Folmer
 * Jaap van Otterdijk
 * Larry Garfield

## 6. Votes

* [Entrance Vote](https://groups.google.com/g/php-fig/)
* [Acceptance Vote](https://groups.google.com/g/php-fig/)

## 7. Relevant Links


## 8. Past contributors

This document stems from the work of many people in previous years, we recognize their effort:

 *
_**Note:** Order descending chronologically._

## 9. FAQ

TBD