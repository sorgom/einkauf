@echo off
SETLOCAL

cd /d %~dp0
set myDir=%cd%
set logDir=%myDir%\logs
set errlog=%logDir%\error.log
cd ..
set topDir=%cd%

call %myDir%\stop.cmd

start /B php-cgi.exe -b 127.0.0.1:9000
start /B nginx -e %errlog% -c %myDir%\nginx.conf -p %topDir%
ENDLOCAL