# Internationalization Meta Document

## 1. Summary

Developers of components that are not coupled to any specific framework often find themselves in need of displaying a message to the user of the web application. However, being as they are not a component of a specific framework - their options for making these messages localizable is limited. In many cases, developers will make no attempt to pass these messages through a localization layer.

To resolve this issue and encourage the localization of libraries, a standard method for translating and transforming messages is necessary.

## 3. Scope

### 3.1. Goals

* Provide a method for an independent component to display a message in a language other than the one in which the component was written.

### 3.2. Non-Goals

* This PSR does not provide a standard for the storage and management of translatable items and their translations. That is, this PSR is about denoting that a message is translateable - not providing the specific translations for it.
* This PSR does not provide a mechanism for collecting translatable items from a component's source code.
* This PSR only addresses text present in PHP code.  It is not concerned with the translation of user content stored in a database or similar data store.

## 4. Approaches

To solve this, we currently aim to create an interface that a framework-independent component can rely on for transforming a message key and context into a translated and formatted string.

## 5. People

### 5.1. Editor
* Navarr Barnier

### 5.2. Sponsor
* Larry Garfield

### 5.3. Working group members
* Alexander Makarov
* Susanne Moog
* Ken Guest
* Ben Ramsey

## 6. Votes

* Entrance Vote (TBD)
