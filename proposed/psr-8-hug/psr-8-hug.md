Mutually Assured Hug
====================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## 1. Overview

This standard establishes a common way for objects to express mutual
appreciation and support by hugging.  This allows objects to support each other
in a constructive fashion, furthering cooperation between different PHP projects.

## 2. Specification

This specification defines two interfaces, \Psr\Hug\Huggable and
\Psr\Hug\GroupHuggable.

### Huggable objects

1. A Huggable object expresses affection and support for another object by invoking
its hug() method, passing $this as the first parameter.

2. An object whose hug() method is invoked MUST hug() the calling object back
at least once.

3. Two objects that are engaged in a hug MAY continue to hug each other back for
any number of iterations. However, every huggable object MUST have a termination
condition that will prevent an infinite loop.  For example, an object MAY be
configured to only allow up to 3 mutual hugs, after which it will break the hug
chain and return.

4. An object MAY take additional actions, including modifying state, when hugged.
A common example is to increment an internal happiness or satisfaction counter.

### GroupHuggable objects

1. An object may optionally implement GroupHuggable to indicate that it is able
to support and affirm multiple objects at once.

## 3. Interfaces

### HuggableInterface

~~~php
namespace Psr\Hug;

/**
 * Defines a huggable object.
 *
 * A huggable object expresses mutual affection with another huggable object.
 */
interface Huggable
{

    /**
     * Hugs this object.
     *
     * All hugs are mutual. An object that is hugged MUST in turn hug the other
     * object back by calling hug() on the first parameter. All objects MUST
     * implement a mechanism to prevent an infinite loop of hugging.
     *
     * @param Huggable $h
     *   The object that is hugging this object.
     */
    public function hug(Huggable $h);
}
~~~

~~~php
namespace Psr\Hug;

/**
 * Defines a huggable object.
 *
 * A huggable object expresses mutual affection with another huggable object.
 */
interface GroupHuggable extends Huggable
{

  /**
   * Hugs a series of huggable objects.
   *
   * When called, this object MUST invoke the hug() method of every object
   * provided. The order of the collection is not significant, and this object
   * MAY hug each of the objects in any order provided that all are hugged.
   *
   * @param Huggable[] $huggables
   *   An array or iterator of objects implementing the Huggable interface.
   */
  public function groupHug($huggables);
}
~~~
