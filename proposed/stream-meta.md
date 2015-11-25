# HTTP Message Meta Document

## 1. Summary

The purpose of this proposal is to provide a common interface for input and
output streams.

In PHP, streams are used for general input and output, including:

- Reading and writing files
- Reading and writing data across a network

The majority of the `StreamInterface` API is based on
[Python's io module](http://docs.python.org/3.1/library/io.html), which provides
a practical and consumable API. Instead of implementing stream
capabilities using something like a `WritableStreamInterface` and
`ReadableStreamInterface`, the capabilities of a stream are provided by methods
like `isReadable()`, `isWritable()`, etc. This approach is used by Python,
[C#, C++](http://msdn.microsoft.com/en-us/library/system.io.stream.aspx),
[Ruby](http://www.ruby-doc.org/core-2.0.0/IO.html),
[Node](http://nodejs.org/api/stream.html), and likely others.

## 2. People

### 2.1 Editor(s)

* Andrew Carter

### 2.2 Sponsors

* TODO: Find
* TODO: Find

### 2.3 Contributors

* Matthew Weier O'Phinney
* TODO: All of the PSR-7 contributors who worked on streams
