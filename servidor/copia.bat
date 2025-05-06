@echo off
REM Script de copia con verificación básica y hash por archivo usando certutil
REM Obtener fecha en formato AAAAMMDD
set FECHA=%DATE:~-4%%DATE:~3,2%%DATE:~0,2%
REM Definir rutas
set ORIGEN=C:\wamp64\www\jjstyle
set DESTINO=C:\Users\Administrador\Downloads\copia\jjstyle_%FECHA%
set LOG=%DESTINO%\log_copia.txt
set HASH_ORIGEN=%DESTINO%\hash_origen.txt
set HASH_COPIA=%DESTINO%\hash_copia.txt
REM Crear carpeta de destino
mkdir "%DESTINO%"
REM Copiar archivos del proyecto
xcopy "%ORIGEN%" "%DESTINO%" /E /H /C /I /Y > "%LOG%"
REM Verificación: contar archivos copiados
echo. >> "%LOG%"
echo Verificación de archivos copiados: >> "%LOG%"
dir /s /b "%DESTINO%" | find /v /c "" >> "%LOG%"
REM Calcular hash de los archivos originales
echo Generando hashes del ORIGEN... >> "%LOG%"
for /R "%ORIGEN%" %%F in (*) do (
    certutil -hashfile "%%F" SHA256 >> "%HASH_ORIGEN%"
    echo. >> "%HASH_ORIGEN%"
)
REM Calcular hash de los archivos copiados
echo Generando hashes de la COPIA... >> "%LOG%"
for /R "%DESTINO%" %%F in (*) do (
    certutil -hashfile "%%F" SHA256 >> "%HASH_COPIA%"
    echo. >> "%HASH_COPIA%"
)
echo Verificación de integridad finalizada. >> "%LOG%"
echo Puedes comparar hash_origen.txt y hash_copia.txt manualmente. >> "%LOG%"
pause