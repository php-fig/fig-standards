HTTP Client Meta Document
=========================

## Summary

HTTP request and responses are the two fundamental objects in web programming. 
All clients communicating to an external API use some form of HTTP client. Many
libraries are coupled to one specific client or implement a client and/or adapter
layer themselves. This leads to bad library design, version conflicts or too much
code not related to the library domain. 

## Why bother?

Thanks to PSR-7 we know how HTTP requests and responses ideally look like, but nothing 
defines how a request should be sent and a response received. A common interface for HTTP
client will allow libraries to be decoupled from an implementation such as Guzzle.

## Scope

### Goals

* A common interface for sending PSR-7 messages.   

### Non-Goals

* The purpose of this PSR is not to support asynchronous HTTP clients.  
* This PSR will not include how to configure a HTTP client. It does only
specify the default behaviours. 
* The purpose is not to be opinionated about the use of middlewares (PSR-15).

## People

### 5.1 Editor

* Tobias Nyholm and the PHP-HTTP team

### 5.2 Sponsors
