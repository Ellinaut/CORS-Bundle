# CORS-Bundle

```yaml
cors:
  patterns:
    '*': ~
```

```yaml
cors:
  patterns:
    '*':
      handle_options: true
      credentials: true
      origin: [ '*' ]
      methods:
        - 'OPTIONS'
        - 'HEAD'
        - 'GET'
        - 'POST'
        - 'PATCH'
        - 'PUT'
        - 'DELETE'
      additional_methods: [ ]
      headers:
        - 'Access-Control-Allow-Headers'
        - 'Access-Control-Request-Method'
        - 'Access-Control-Request-Headers'
        - 'Access-Control-Allow-Credentials'
        - 'Origin'
        - 'Authorization'
        - 'Content-Type'
        - 'Accept'
        - 'Accept-Language'
        - 'Vary'
        - 'X-Requested-With'
      additional_headers: [ ]
```