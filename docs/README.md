# HTTP Message Interfaces

```mermaid

classDiagram

    direction TB

    class MessageInterface {
        <<interface>>
        +getProtocolVersion() string
        +withProtocolVersion(version) MessageInterface
        +getHeaders() array
        +hasHeader(name) bool
        +getHeader(name) string[]
        +getHeaderLine(name) string
        +withHeader(name, value) MessageInterface
        +withAddedHeader(name, value) MessageInterface
        +withoutHeader(name) MessageInterface
        +getBody() StreamInterface
        +withBody(body) MessageInterface
    }

    class RequestInterface {
        <<interface>>
        +getRequestTarget() string
        +withRequestTarget(target) RequestInterface
        +getMethod() string
        +withMethod(method) RequestInterface
        +getUri() UriInterface
        +withUri(uri, preserveHost) RequestInterface
    }

    class ServerRequestInterface {
        <<interface>>
        +getServerParams() array
        +getCookieParams() array
        +withCookieParams(cookies) ServerRequestInterface
        +getQueryParams() array
        +withQueryParams(query) ServerRequestInterface
        +getUploadedFiles() array
        +withUploadedFiles(files) ServerRequestInterface
        +getParsedBody() null|array|object
        +withParsedBody(data) ServerRequestInterface
        +getAttributes() array
        +getAttribute(name, default) mixed
        +withAttribute(name, value) ServerRequestInterface
        +withoutAttribute(name) ServerRequestInterface
    }

    class ResponseInterface {
        <<interface>>
        +getStatusCode() int
        +withStatus(code, reasonPhrase) ResponseInterface
        +getReasonPhrase() string
    }

    class StreamInterface {
        <<interface>>
        +close() void
        +detach() resource
        +getSize() int|null
        +tell() int
        +eof() bool
        +isSeekable() bool
        +seek(offset, whence) void
        +rewind() void
        +isWritable() bool
        +write(string) int
        +isReadable() bool
        +read(length) string
        +getContents() string
        +getMetadata(key) mixed
    }

    class UriInterface {
        <<interface>>
        +getScheme() string
        +getAuthority() string
        +getUserInfo() string
        +getHost() string
        +getPort() int|null
        +getPath() string
        +getQuery() string
        +getFragment() string
        +withScheme(scheme) UriInterface
        +withUserInfo(user, pass) UriInterface
        +withHost(host) UriInterface
        +withPort(port) UriInterface
        +withPath(path) UriInterface
        +withQuery(query) UriInterface
        +withFragment(fragment) UriInterface
    }

    class UploadedFileInterface {
        <<interface>>
        +getStream() StreamInterface
        +moveTo(targetPath) void
        +getSize() int|null
        +getError() int
        +getClientFilename() string|null
        +getClientMediaType() string|null
    }

    %% Relations et Multiplicités
    MessageInterface <|-- RequestInterface : hérite
    MessageInterface <|-- ResponseInterface : hérite
    RequestInterface <|-- ServerRequestInterface : hérite

    MessageInterface "1" o-- "1" StreamInterface : contient le corps
    RequestInterface "1" o-- "1" UriInterface : possède une
    ServerRequestInterface "1" --> "*" UploadedFileInterface : gère les fichiers
    UploadedFileInterface "1" o-- "1" StreamInterface : expose un flux
```

Message / Stream (1:1) : Tout message HTTP (requête ou réponse) possède obligatoirement un corps, même si celui-ci est vide.

Request / Uri (1:1) : Une requête doit pointer vers une cible unique définie par une URI.

ServerRequest / UploadedFile (1:N) : Une requête serveur peut contenir aucun ou plusieurs fichiers envoyés via un formulaire (multipart/form-data).