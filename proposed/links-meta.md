# Link Definition Meta Document

## 1. Summary



### Open questions

The following questions are still outstanding, in the opinion of the Editor, and should be resolved.

* LinkableInterface is a terrible name. Please suggest another one.
* How do we support "empty" attributes, as HTML5 permits but few other systems do?
* Should Href be a string, or can/should we use PSR-7 URI objects? I'm very very tempted to go with the latter.
* Is there wording we should clean up around rel definitions?
* Should the rel definition information move from the interfaces to the spec, or stay in the interface docblocks where
  people can easily find it when using it?
* Currently, technically, URL templates would be disallowed. That's a problem for, say, HAL. How do we want to square
  that, especially if Href becomes an object?

## 2. Scope

### 2.1 Goals

* 


### 2.2 Non-Goals

* 

## 3. Design Decisions

### 



## 4. People

### 4.1 Editor(s)

* Larry Garfield

### 4.2 Sponsors

* Evert Pot
* Matthew Weier O'Phinney (coordinator)

### 4.3 Contributors

## 5. Votes

## 6. Relevant links

* [What's in a link?](http://evertpot.com/whats-in-a-link/) by Evert Pot
