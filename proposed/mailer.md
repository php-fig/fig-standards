# Mailer interface 

This document describes a common interface for prepare and send a mail 

This goal set By MailerInterface is to standardize how to frameworks and libraries. 

## 1. Specification 

### 1.1 Basics MailerInterface
   
   - The Psr\Mail\MailerInterface exposes one methods `send`
   
   - `send` takes one mandatory parameter of type Psr\Mail\MessageInterface. A call to `send` SHOULD be a boolean or null 
   
   
### 1.2 Basics MessageInterface
    
  - The Psr\Mail\MessageInterface exposes the getters and setters for the fields  ```attachments```, ```subject```, ```form```, ```to``` and ```body```
   
   - ```attachments``` setter takes one mandatory parameter of type array. The getter MUST return an array of attachments list.
   - ```subject``` setter takes one mandatory parameter MUST be a string or null . The getter MUST return a string or null.
   - ```form``` setter takes one mandatory parameter MUST be a string and MUST be not empty. The getter MUST return a string. 
   - ```to``` setter takes one mandatory parameter MUST be a string and MUST be not empty. The getter MUST return a string.
   - ```body``` setter takes one mandatory parameter MUST be a string or null. The getter MUST return a string or null.
   
    
## 2. Package 

The interfaces and classes described as well are provided as part of the psr/mailer package.

### 2.1 Psr\Mail\MailerInterface

```php 
    <?php

        namespace Psr\Mail;
        
        use Psr\Mail\MessageInterface;

        interface MailerInterface
        {
            /**
            * @param MessageInterface $message
            * @return bool|null
            */
            public function send(MessageInterface $message);
        }
```

### 2.2 Psr\Mail\MessageInterface

```php

    <?php
    
        namespace Psr\Mail;
        
        interface MessageInterface
        {
            private $subject;
            private $from;
            private $to;
            private $body;
            private $html;
            private $attachments;
        
            /**
            * @return array
            */
            public function getAttachments();
        
            /**
            * @param array $attachement
            */
            public function setAttachments(array $attachments = []);
        
            /**
            * @return string|null
            */
            public function getSubject()
        
            /**
            * @param string|null $subject
            */
            public function setSubject($subject = null);
        
            /**
            * @return string
            */
            public function getFrom();
        
            /**
            * @param string $from
            */
            public function setFrom($from);
        
            /**
            * @return string
            */
            public function getTo();
        
            /**
            * @param string $to
            */
            public function setTo($to);
        
            /**
            * @return string|null
            */
            public function getBody();
        
            /**
            * @param string|null $body
            */
            public function setBody($body = null);
        }
```