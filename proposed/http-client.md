## Introduction

Many libraries and applications require an HTTP client to talk to other servers. In general all these libraries either ship their own HTTP client library, or use low-level PHP functionality such as `file_get_contents`, ext/curl or the ext/socket. Depending on the use-cases there are very tricky implementation details to be handled such as proxies, SSL, authentication protocols and many more. Not every small library can afford to implement all the different details. However there are only a few http client libraries that are widely used between different projects, because of NIH or fears of vendor lock-in.

## Goal

This proposal aims at providing a very simple HTTP client interface to allow interopability between HTTP clients of different libraries and allow PHP libraries to ship HTTP client functionality without the necessity to implement a client.

## Interfaces

A single interface is proposed

    <?php
    namespace PSR\Http\Client;

    /**
     * A HTTP Client
     */
    interface HttpClient
    {
        /**
         * Send a http-request and return a http-response.
         * 
         * @param string $method HTTP method, uppercase
         * @param string $url Url to send HTTP request to
         * @param mixed $content Content of the request, can be empty.
         * @param array $headers Array of Headers, header Name is the key.
         * @param array $options Vendor specific options to activate specific features.
         * @return Response
         */
        public function request($method, $url, $content = null, array $headers = array(), array $options = array());
    }
    
A value objects come with this interface, the Response which is returned from the client. Their definition is as follows:

    <?php
    namespace PSR\Http\Client;

    /**
     * Http Response returned from {@see HttpClient::request}.
     */
    class Response
    {
        /**
         * Status code for the response
         *
         * @return int
         */
        public function getStatusCode();
        
        /**
         * Get content type for the response
         *
         * @return string
         */
        public function getContentType();
        
        /**
         * Get the content of this response
         *
         * @return string
         */
        public function getContent();
        
        /**
         * Return all headers of this response.
         *
         * @return array
         */
        public function getHeaders();
        
        /**
         * Return a specified header of this response or null if not found,
         *
         * Returns an array if multiple occurances of a header are returned.
         *
         * @return string|array|null
         */
        public function getHeader($name);
    }

## Sample code

Here is an example usage:

    <?php
    $client = create_http_client(); // implementation specific
    $response = $client->request('GET', 'http://www.php.net');
    
    if ($response->getStatusCode() == 200) {
        $content = $response->getContent();
    }
    
    $response = $client->request('GET', 'http://api/returning.json');
    
    if ($response->getContentType() == 'application/json') {
        $json = json_decode($response->getContent());
    }

