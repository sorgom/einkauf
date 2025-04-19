# run todo PHP server locally on nginx
## required installation
You need
- a folder with PATH environment set to it
- containing four files
```
nginx/
| from nginx download:
├── nginx.exe
| from PHP download:
├── php8ts.dll
├── php-cgi.exe
└── php.ini
```
## start, state, stop
- call start.cmd to start the server
- open localhost:8080 in your browser

- call state.cmd to see if running, there should at least be an instance of
    - nginx.exe
    - php-cgi.exe

- call stop.cmd to stop
