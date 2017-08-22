# RDF interfaces

The [Resource Description Framework](https://www.w3.org/RDF/) (RDF) is a model for data interchange on the Web. Data is modeled using triples or quads. 

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119][].

[ARC2]: https://github.com/semsol/arc2
[EasyRdf]: https://github.com/njh/easyrdf
[Erfurt]: https://github.com/AKSW/Erfurt
[hardf]: https://github.com/pietercolpaert/hardf
[RFC 2119]: http://tools.ietf.org/html/rfc2119
[Saft]: https://github.com/SaftIng/Saft

## 1. Goal

There are currently 5 RDF-libraries for PHP available ([EasyRdf][], [Erfurt][], [hardf][], [ARC2][] and [Saft][]). Each implements different areas with various quality and feature-coverage. Combined, they provide a rich feature-set from RDF data handling, serialization and parsing to database access. Therefore it is important, that each library uses the same data model for RDF data to allow data interchange.

The goal of this PSR is to provide a base of RDF interfaces which can help to integrate RDF libraries and make data interchange over library borders possible.


## 2. Definitions

* **RDF** - The Resource Description Framework (RDF) is a family of World Wide Web Consortium (W3C) specifications originally designed as a metadata data model. It has come to be used as a general method for conceptual description or modeling of information that is implemented in web resources, using a variety of syntax notations and data serialization formats ([Source](https://en.wikipedia.org/wiki/Resource_Description_Framework)).

* **Resource** - Anything can be a resource, including physical things, documents, abstract concepts, numbers and strings; the term is synonymous with "entity" as it is used in the RDF Semantics specification. ([Source](https://www.w3.org/TR/rdf11-concepts/))

* **IRI** and **URI** - IRIs are a generalization of URIs [RFC3986](http://www.ietf.org/rfc/rfc3986.txt) that permits a wider range of Unicode characters. Every absolute URI and URL is an IRI, but not every IRI is an URI. When IRIs are used in operations that are only defined for URIs, they must first be converted according to the mapping defined in section 3.1 of [RFC3987](http://www.ietf.org/rfc/rfc3987.txt). A notable example is retrieval over the HTTP protocol. The mapping involves UTF-8 encoding of non-ASCII characters, %-encoding of octets not allowed in URIs, and Punycode-encoding of domain names. ([Source](https://www.w3.org/TR/rdf11-concepts/#section-IRIs))

* **Triple** - An RDF triple consists of three components: (1) the subject, which is an IRI or a blank node, (2) the predicate, which is an IRI and (3) the object, which is an IRI, a literal or a blank node. An RDF triple is conventionally written in the order subject, predicate, object ([Source](https://www.w3.org/TR/rdf11-concepts/#section-triples)).

* **Quad** - A quad is a triple with a graph URI as fourth element.

* **Statement** - A statement is either a triple or quad.

* **Literal** - Literals are used for values such as strings, numbers, and dates. ([Source](https://www.w3.org/TR/rdf11-concepts/#section-Graph-Literal))

* **Blank Node** - Blank nodes are disjoint from IRIs and literals. Otherwise, the set of possible blank nodes is arbitrary. RDF makes no reference to any internal structure of blank nodes. ([Source](https://www.w3.org/TR/rdf11-concepts/#section-blank-nodes))

* **Named Node** - A synonym for Resource.

* **Node** - A node is either a literal, a blank node or resource.

## 3. Interfaces

The following interfaces are represent all relevant RDF concepts.

### 3.1 `Psr\RDF\Node`

```php
<?php
namespace Psr\RDF;

/**
 * TODO
 */
interface Node
{
    /**
     * Checks if this instance is a literal.
     *
     * @return boolean True, if it is a literal, false otherwise.
     */
    public function isLiteral();

    /**
     * Checks if this instance is a named node.
     *
     * @return boolean True, if it is a named node, false otherwise.
     */
    public function isNamed();

    /**
     * Checks if this instance is a blank node.
     *
     * @return boolean True, if this instance is a blank node, false otherwise.
     */
    public function isBlank();

    /**
     * Checks if this instance is concrete, which means it does not contain pattern.
     *
     * @return boolean True, if this instance is concrete, false otherwise.
     */
    public function isConcrete();

    /**
     * Checks if this instance is a pattern. It can either be a pattern or concrete.
     *
     * @return boolean True, if this instance is a pattern, false otherwise.
     */
    public function isPattern();

    /**
     * Transform this Node instance to a n-quads string, if possible.
     *
     * @return string N-quads string representation of this instance.
     * @throws \Exception if no n-quads representation is available.
     */
    public function toNQuads();

    /**
     * This method is ment for getting some kind of human readable string
     * representation of the current node. There is no definite syntax, but it
     * should contain the the URI for NamedNodes and the value for Literals.
     *
     * @return string A human readable string representation of the node.
     * @api
     * @since 0.1
     */
    public function __toString();

    /**
     * Check if a given instance of Node is equal to this instance.
     *
     * @param Node $toCompare Node instance to check against.
     * @return boolean True, if both instances are semantically equal, false otherwise.
     */
    public function equals(Node $toCompare);

    /**
     * Returns true, if this pattern matches the given node. This method is the same as equals for concrete nodes
     * and is overwritten for pattern/variable nodes.
     *
     * @param Node $toMatch Node instance to apply the pattern on.
     * @return boolean true, if this pattern matches the node, false otherwise.
     */
    public function matches(Node $toMatch);
}
```
