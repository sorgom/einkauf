# run todo PHP server locally on nginx
## required installation
you need a folder or two folders with PATH environment set to it

containing
- from nginx download:
    - nginx.exe
- from PHP download:
    - php-cgi.exe
    - php.ini
    - php8ts.dll
    - php_openssl.dll

### php.ini
- enable openssl:
``extension=./php_openssl.dll``

## start, state, stop
than in this ngnix folder
- call start.cmd to start the server
- open localhost:8080 in your browser

- call state.cmd to see if running, there should at least be an instance of
    - nginx.exe
    - php-cgi.exe

- call stop.cmd to stop
