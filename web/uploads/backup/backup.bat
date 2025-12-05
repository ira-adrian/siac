@echo off
set mySqlPath=C:\xampp\mysql\bin\mysqldump.exe
set dbUser=root
set dbPassword=""
set dbName=siarme
set file=Respaldo_%dbName%_%date:~-4,4%-%date:~-7,2%-%date:~-10,2%_%time:~0,2%_%time:~3,2%.sql
set path=C:\xampp\htdocs\autoseguro\web\uploads\backup

echo Running dump for database %dbName% ^> ^%path%\%file%
%mySqlPath% -u%dbUser% -p%dbPassword%  %dbName% >"%path%\%file%"
echo Done!