# RDF interfaces

The [Resource Description Framework](https://www.w3.org/RDF/) (RDF) is a model for data interchange on the Web. Data is modeled using triples or quads. 

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## 1. Goal

The goal of this PSR is to provide a base of RDF interfaces which can help to integrate RDF libraries and make data interchange over library borders possible.

## 2. Motivation

There are currently 5 RDF-libraries for PHP available ([EasyRdf](https://github.com/njh/easyrdf), [Erfurt](https://github.com/AKSW/Erfurt), [hardf](https://github.com/pietercolpaert/hardf), [ARC2](https://github.com/semsol/arc2) and [Saft](https://github.com/SaftIng/Saft)). Each implements different areas with various quality and feature-coverage. Combined, they provide a rich feature-set from RDF data handling, serialization and parsing to database access. Therefore it is important, that each library uses the same data model for RDF data to allow data interchange.

## 3. Background information

In this section further background information are shared.

### Representation Task Force (RDFJS)

The Representation Task Force (RDFJS, [Link 1](https://www.w3.org/community/rdfjs/), [Link 2](https://github.com/rdfjs/representation-task-force)) published an interface documentation ([Link](https://github.com/rdfjs/representation-task-force/blob/master/interface-spec.md)) describing the representation of RDF in JavaScript.

The approach is partly object orientied and relies on the direct usage of instance properties. Direct Access to properties violates the object oriented approach. Implementation details of an interface should not be visible to the outside world.

Nevertheless, this document shares some parts with the interface documentation or is directly influenced:

* Some of the terms of the RDFJS document are to same as in this document. These are: **NamedNode**, **BlankNode**, **Literals**. 
* The terms **triple** and **quad** can be represented by the **Statement** interface of this document.
* Interface **Term** is equal to **Node** in this document.
* Interface **DataFactory** is equal to **NodeFactory** in this document.


The following list contains all interfaces which exist in both documents:
* BlankNode
* NamedNode
* Literal

In the following an overview about the interfaces, which differ.

| Interface         | Interface from Representation Task Force specification |
|:------------------|:-------------------------------------------------------|
| Node              | Term                                                   |
| Statement         | Triple + Quad                                          |
| AnyPattern        | Variable                                               |
| NodeFactory       | DataFactory                                            |
| StatementIterator | -                                                      |

## 4. Definitions and concepts

* **RDF** - The Resource Description Framework (RDF) is a family of World Wide Web Consortium (W3C) specifications originally designed as a metadata data model. It has come to be used as a general method for conceptual description or modeling of information that is implemented in web resources, using a variety of syntax notations and data serialization formats ([Source](https://en.wikipedia.org/wiki/Resource_Description_Framework)).

* **Resource** - Anything can be a resource, including physical things, documents, abstract concepts, numbers and strings; the term is synonymous with "entity" as it is used in the RDF Semantics specification. ([Source](https://www.w3.org/TR/rdf11-concepts/#resources-and-statements))

* **IRI** and **URI** - IRIs are a generalization of URIs [RFC3986](http://www.ietf.org/rfc/rfc3986.txt) that permits a wider range of Unicode characters. Every absolute URI and URL is an IRI, but not every IRI is an URI. When IRIs are used in operations that are only defined for URIs, they must first be converted according to the mapping defined in section 3.1 of [RFC3987](http://www.ietf.org/rfc/rfc3987.txt). A notable example is retrieval over the HTTP protocol. The mapping involves UTF-8 encoding of non-ASCII characters, %-encoding of octets not allowed in URIs, and Punycode-encoding of domain names. ([Source](https://www.w3.org/TR/rdf11-concepts/#section-IRIs))

* **Triple** - An RDF triple consists of three components: (1) the subject, which is an IRI or a blank node, (2) the predicate, which is an IRI and (3) the object, which is an IRI, a literal or a blank node. An RDF triple is conventionally written in the order subject, predicate, object ([Source](https://www.w3.org/TR/rdf11-concepts/#section-triples)).

* **Quad** - A quad is a triple with a graph URI as fourth element.

* **Statement** - A statement is either a triple or quad.

* **Literal** - Literals are used for values such as strings, numbers, and dates. ([Source](https://www.w3.org/TR/rdf11-concepts/#section-Graph-Literal))

* **Blank Node** - Blank nodes are disjoint from IRIs and literals. Otherwise, the set of possible blank nodes is arbitrary. RDF makes no reference to any internal structure of blank nodes. ([Source](https://www.w3.org/TR/rdf11-concepts/#section-blank-nodes))

* **Named Node** - A synonym for Resource.

* **Node** - A node is an abstract concept and is either of type literal, a blank node, any pattern or resource.

* **NodeFactory** - A node factory creates instances of Node. This has to be one of the following: BlankNode, NamedNode, Literal or AnyPatternImpl. It also has to provide methods to create a Node instance by given n-triples/n-quads string and via a parameter list ($value, $type, $datatype, $language).

* **AnyPattern** - A pattern is variable and acts as a placeholder. See section "Data interfaces and design principles" for detailed information about its usage.

* **StatementIterator** - A StatementIterator is a `Iterator` ([PHP documentation](http://php.net/manual/de/class.iterator.php)) but forces entries to be of type **Statement**.

## 5. Data interfaces

In the following an overview of all interfaces and according example implementations.

|     Interface     |      According Class       |
|:-----------------:|:--------------------------:|
|       Node        |             -              |
|     BlankNode     |       BlankNodeImpl        |
|      Literal      |        LiteralImpl         |
|     NamedNode     |       NamedNodeImpl        |
|     Statement     |       StatementImpl        |
|    NodeFactory    |      NodeFactoryImpl       |
|         -         |       AnyPatternImpl       |
| StatementIterator | ArrayStatementIteratorImpl |


### 5.2 Additional information

* **AnyPattern** is implicit to Node and has no seperated interface. See the example implementation below, how it manifests in the code. Its purpose is to act as placeholder in a SPARQL query string. Its other purpose is to act as placeholder in a **Statement** to outline that either subject, predicate, object or graph is not specified.

## 6. PHP-Interfaces

Repository [Saft/PsrRdf](https://github.com/SaftIng/PsrRdf) contains the following interfaces and example implementations. The following interfaces are represent all relevant RDF concepts.

### 6.1 `Psr\RDF\Node`

| Methods                        |
|:-------------------------------|
| isBlank                        |
| isLiteral                      |
| isNamed                        |
| isConcrete                     |
| isPattern (*means AnyPattern*) |
| toNQuads                       |
| equals                         |
| matches                        |

```php
<?php

namespace Psr\Rdf;

/**
 * A node is an abstract concept and is either of type literal, a blank node, any pattern or resource
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

### 6.2 `Psr\RDF\BlankNode`

Bold methods are unique to this interface, the rest *comes* from the Node.

| Methods                        |
|:-------------------------------|
| **getBlankId**                 |
| isBlank                        |
| isLiteral                      |
| isNamed                        |
| isConcrete                     |
| isPattern (*means AnyPattern*) |
| toNQuads                       |
| equals                         |
| matches                        |

```php
<?php

namespace Psr\Rdf;

/**
 * This interface is common for blank nodes according to RDF 1.1.
 * For more information see http://www.w3.org/TR/rdf11-concepts/#section-blank-nodes
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

### 6.3 `Psr\RDF\Literal`

Bold methods are unique to this interface, the rest *comes* from the Node.

| Methods                        |
|:-------------------------------|
| **getValue**                   |
| isBlank                        |
| isLiteral                      |
| isNamed                        |
| isConcrete                     |
| isPattern (*means AnyPattern*) |
| toNQuads                       |
| equals                         |
| matches                        |

```php
<?php

namespace Psr\Rdf;

/**
 * Literals are used for values such as strings, numbers, and dates.
 * For more information see https://www.w3.org/TR/rdf11-concepts/#section-Graph-Literal
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
     * Get the datatype of the Literal. It can be one of the XML Schema datatypes (XSD) or anything else. If the URI is
     * needed it can be retrieved by calling ->getDatatype()->getUri().
     *
     * An overview about all XML Schema datatypes: {@url http://www.w3.org/TR/xmlschema-2/#built-in-datatypes}
     *
     * @return Node the datatype of the Literal as named node
     */
    public function getDatatype();

    /**
     * Get the language tag of this Literal or null of the Literal has no language tag.
     *
     * @return string|null Language tag or null, if none is given.
     */
    public function getLanguage();
}
```

### 6.4 `Psr\Rdf\NamedNode`

Bold methods are unique to this interface, the rest *comes* from the Node.

| Methods                        |
|:-------------------------------|
| **getUri**                     |
| isBlank                        |
| isLiteral                      |
| isNamed                        |
| isConcrete                     |
| isPattern (*means AnyPattern*) |
| toNQuads                       |
| equals                         |
| matches                        |

```php
<?php

namespace Psr\Rdf;

/**
 * This interface is common for named nodes according to RDF 1.1 specification.
 * For more information see http://www.w3.org/TR/rdf11-concepts/#section-IRIs.
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

### 6.5 `Psr\Rdf\NodeFactory`

| Methods                             |
|:------------------------------------|
| createLiteral                       |
| createNamedNode                     |
| createBlankNode                     |
| createAnyPattern                    |
| createNodeFromNQuads                |
| createNodeInstanceFromNodeParameter |

```php
<?php

namespace Psr\Rdf;

/**
 * The NodeFactory interface abstracts the creation of new instances of RDF nodes by hiding different implementation details.
 */
interface NodeFactory
{
    /**
     * Create a new RDF literal node instance. Details how to create such an instance may differ between different
     * implementations of the NodeFactory.
     *
     * @param string $value The value of the literal
     * @param string|Node $datatype The datatype of the literal (NamedNode, optional)
     * @param string $lang The language tag of the literal (optional)
     * @return Literal Instance of Literal.
     */
    public function createLiteral($value, $datatype = null, $lang = null);

    /**
     * Create a new RDF named node. Details how to create such an instance may differ between different
     * implementations of the NodeFactory.
     *
     * @param string $uri The URI of the named node
     * @return NamedNode Instance of NamedNode.
     */
    public function createNamedNode($uri);

    /**
     * Create a new RDF blank node. Details how to create such an instance may differ between different
     * implementations of the NodeFactory.
     *
     * @param string $blankId The identifier for the blank node
     * @return BlankNode Instance of BlankNode.
     */
    public function createBlankNode($blankId);

    /**
     * Create a new pattern node, which matches any RDF Node instance.
     *
     * @return Node Instance of Node, which acts like an AnyPattern.
     */
    public function createAnyPattern();

    /**
     * Creates an RDF Node based on a n-triples/n-quads node string.
     *
     * @param string $string N-triples/n-quads node string to use.
     * @return Node Node instance, which type must be one of the following: NamedNode, BlankNode, Literal
     * @throws \Exception if no node could be created e.g. because of a syntax error in the node string
     */
    public function createNodeFromNQuads($string);

    /**
     * Helper function, which is useful, if you have all the meta information about a Node and want to create
     * the according Node instance.
     *
     * @param string      $value       Value of the node.
     * @param string      $type        Can be uri, bnode, var or literal
     * @param string      $datatype    URI of the datatype (optional)
     * @param string      $language    Language tag (optional)
     * @return Node Node instance, which type is one of: NamedNode, BlankNode, Literal, AnyPattern
     * @throws \Exception if an unknown type was given.
     */
    public function createNodeInstanceFromNodeParameter($value, $type, $datatype = null, $language = null);
}
```

### 6.6 `Psr\Rdf\Statement`

| Methods      |
|:-------------|
| getSubject   |
| getPredicate |
| getObject    |
| getGraph     |
| isTriple     |
| isQuad       |
| isConcrete   |
| isPattern    |
| equals       |
| matches      |
| toNQuads     |
| __toString   |

```php
<?php

namespace Psr\Rdf;

/**
 * This interface is common for RDF statement. It can represent a 3- or 4-tuple. A 3-tuple (triple) consists
 * of subject, predicate and object, whereas a 4-tuple (quad) is a 3-tuple with a graph.
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
     * Determines if this statement contains graph information.
     *
     * @return boolean True, if this statement consists of subject, predicate, object and graph, false otherwise.
     */
    public function isQuad();

    /**
     * Determines if this statement contains no graph information.
     *
     * @return boolean True, if this statement consists of subject, predicate and object,
     *                 but no graph, false otherwise.
     */
    public function isTriple();

    /**
     * Checks if this statement contains no pattern.
     *
     * @return boolean True, if neither subject, predicate, object nor, if available, graph, are patterns,
     *                 false otherwise.
     */
    public function isConcrete();

    /**
     * Checks if this statement contains a pattern.
     *
     * @return boolean True, if at least subject, predicate, object or, if available, graph, are patterns,
     *                 false otherwise.
     */
    public function isPattern();

    /**
     * Get a valid NQuads serialization of the statement. If the statement is not concrete because
     * it contains pattern, this method has to throw an exception.
     *
     * @throws \Exception if the statment is not concrete
     * @return string a string representation of the statement in valid NQuads syntax.
     */
    public function toNQuads();

    /**
     * Get a string representation of the current statement. It should contain a human readable description of the parts
     * of the statement.
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

### 6.7 `Psr\Rdf\StatementIterator`

| Methods |
|:--------|
| current |
| key     |
| next    |
| rewind  |
| valid   |

```php
<?php

namespace Psr\Rdf;

/**
 * The StatementIterator interface extends the \Iterator interface by restricting it to Statements.
 *
 * Note: It extends \Iterator, but contains its methods too, to be compatible to all implementations
 *       requiring an \Iterator instance.
 */
interface StatementIterator extends \Iterator
{
    /**
     * Get current Statement instance.
     *
     * @return Statement
     */
    public function current();

    /**
     * Get key of current Statement.
     *
     * @return scalar May not be meaningful, but must be unique.
     */
    public function key();

    /**
     * Go to the next Statement instance. Any returned value is ignored.
     */
    public function next();

    /**
     * Reset this iterator.
     *
     * Be aware, it may not be implemented! This can be the case if the implementation is based
     * on a stream.
     */
    public function rewind();

    /**
     * Checks if the current Statement is valid.
     *
     * @return boolean
     */
    public function valid();
}
```
