# Attributes Meta Document

## 1. Summary

The idea is to create a registry that holds and provides user-space attributes for PHP.

This Proposal is about defining the registry and the workflows to add, modify and 
remove attributes from that registry

## 2. Why Bother?

Right now libraries that want to use attributes either have to define them themselves or rely upon 
another library that has them defined. Most of the time that also means to import all the code of 
the other library. 

Userland libraries have no interest in provising an interoperable set of attributes as they solve 
a specific problem with *their* set of attributes. 

This registry shall solve that

## 3. Scope

### 3.1 Goals

* Define and maintain a set of attributes that are relevant for more than one
  single tool so that different tools can use and rely upon a defined set of attributes and
  users do not need to use several similar attributes with the same meaning for different tools.
* Provide one or multiple composer-installable packages containing 
  the code for the attributes in the registry
* provide methods to maintain the registry and add, modify and remove attributes from it

### 3.2 Non-Goals

* The goal is **not** to provide a registry of **all** attributes available in PHP. As an example: The ORM-specific
  attributes from Doctrine are likely not to be part of this registry as they are specific to the library itself

## 4. Approaches

TBD


## 5. People

### 5.1 Editor

 * TBD

### 5.2 Sponsor

 * TBD

### 5.3 Working group members

 * Juliette Reinders-Folmer
 * Jaap vsn Otterdijk
 * Vincent de Lau
 * Larry Garfield
 * Andreas Heigl

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