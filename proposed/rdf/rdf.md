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

The goal of this PSR is to provide a base of RDF interfaces which can help to integrate RDF libraries and make data interchange over library borders possible. Furthermore, RDF implementations in general may also benefit.


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
     * Returns true, if this pattern matches the given node. This method is the 
     * same as equals for concrete nodes and is overwritten for pattern/variable nodes.
     *
     * @param Node $toMatch Node instance to apply the pattern on.
     * @return boolean true, if this pattern matches the node, false otherwise.
     */
    public function matches(Node $toMatch);
}
```

### 3.2 `Psr\RDF\NamedNode`

```php
<?php
namespace Psr\RDF;

/**
 * This interface is common for named nodes according to RDF 1.1.
 * {@url http://www.w3.org/TR/rdf11-concepts/#section-IRIs}
 */
interface NamedNode extends Node
{
    /**
     * Returns the URI of the node.
     *
     * @return string URI of the node.
     */
    public function getUri();
}
```

### 3.3 `Psr\RDF\Literal`

```php
<?php
namespace Psr\Rdf;

/**
 * This interface is common for literals according to RDF 1.1
 * {@url http://www.w3.org/TR/rdf11-concepts/#section-Graph-Literal}
 */
interface Literal extends Node
{
    /**
     * Get the value of the Literal in its string representations
     *
     * @return string the value of the Literal
     */
    public function getValue();

    /**
     * Get the datatype URI of the Literal (this is always set according to the standard).
     *
     * @return Node the datatype of the Literal
     */
    public function getDatatype();

    /**
     * Get the language tag of this Literal or null if the Literal has no language tag.
     *
     * @return string|null Language tag or null, if none is given.
     */
    public function getLanguage();
}
```

### 3.4 `Psr\RDF\BlankNode`

```php
<?php
namespace Psr\Rdf;

/**
 * This interface is common for blank nodes according to RDF 1.1.
 * {@url http://www.w3.org/TR/rdf11-concepts/#section-blank-nodes}
 */
interface BlankNode extends Node
{
    /**
     * Returns the blank ID of this blank node.
     *
     * @return string Blank ID.
     */
    public function getBlankId();
}
```

### 3.5 `Psr\RDF\Statement`

```php
<?php
namespace Psr\RDF;

/**
 * This interface is common for RDF statement. It represents a 3-tuple and 4-tuple. A 3-tuple consists
 * of subject, predicate and object, whereas a 4-tuple is a 3-tuple but also contains a graph.
 */
interface Statement
{
    /**
     * Returns Statements subject.
     *
     * @return Node Subject node.
     */
    public function getSubject();

    /**
     * Returns Statements predicate.
     *
     * @return Node Predicate node.
     */
    public function getPredicate();

    /**
     * Returns Statements object.
     *
     * @return Node Object node.
     */
    public function getObject();

    /**
     * Returns Statements graph, if available.
     *
     * @return Node|null Graph node, if available.
     */
    public function getGraph();

    /**
     * If this statement consists of subject, predicate, object and graph, this 
     * function returns true, false otherwise.
     *
     * @return boolean True, if this statement consists of subject, predicate, object 
     *                 and graph, false otherwise.
     */
    public function isQuad();

    /**
     * If this statement consists of subject, predicate and object, but no graph, 
     * this function returns true, false otherwise.
     *
     * @return boolean True, if this statement consists of subject, predicate and 
     *                 object, but no graph, false otherwise.
     */
    public function isTriple();

    /**
     * Returns true if neither subject, predicate, object nor, if available, graph, 
     * are patterns.
     *
     * @return boolean True, if neither subject, predicate, object nor, if available, 
     *                 graph, are patterns, false otherwise.
     */
    public function isConcrete();

    /**
     * Returns true if at least subject, predicate, object or, if available, graph, 
     * are patterns.
     *
     * @return boolean True, if at least subject, predicate, object or, if available, 
     *                 graph, are patterns, false otherwise.
      */
    public function isPattern();

    /**
     * Get a valid NQuads serialization of the statement. If the statement is not 
     * concrete i.e. it contains variable parts this method will throw an exception.
     *
     * @throws \Exception if the statment is not concrete
     * @return string a string representation of the statement in valid NQuads syntax.
     */
    public function toNQuads();

    /**
     * Get a string representation of the current statement. It should contain
     * a human readable description of the parts of the statement.
     *
     * @return string A string representation of the statement.
     */
    public function __toString();

    /**
     * Returns true, if the given argument matches the is statement-pattern.
     *
     * @param Statement $toCompare the statement to where this pattern shoul be applied to.
     */
    public function matches(Statement $toCompare);

    /**
     * Checks if a given Statement instance is equal to this instance.
     *
     * @param Statement $toCompare the statement to compare with
     * @return boolean True, if the given Statement instance is equal to this one.
     */
    public function equals(Statement $toCompare);
}
```
