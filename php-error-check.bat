@echo off
echo. > stats.txt
for /R %%f in (*.php) do php -l "%%f" >> stats.txt