# Application Tracing

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMEND", "MAY", and "OPTIONAL" in this document are to
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more functionality than
covered in the recommendation however they MUST implement the indicated
interfaces and functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

TBD

## Definitions

* **Framework** - An application framework (or micro-framework) that runs a developers code.  
  Eg. Laravel, Symfony, CakePHP, Slim
* **Library** - Any library that developers may include that adds additional functionality  
  Eg. Image Manipulation, HTTP Clients, 3rd Party SDK's 
* **Provider** - An organization that offers APM as a service. Typically via a composer package
