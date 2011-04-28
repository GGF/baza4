chcp 1251
ren *.gb? *.gbx
ren *.lg? *.gbx
set n=0
echo %CD% >dbq.bat
for /f "delims=\ tokens=1,2,3,4" %%I in (dbq.bat) do set gdir=%%I/%%J/%%K/%%L
for %%i in (pos*KODAK.gbx) do set /a n+=1 & ren %%i %1_%%~ni.gbx & copy /Y %1_%%~ni.gbx "z:\на рисование\8000pos"
for %%i in (neg*KODAK.gbx) do set /a n+=1 & ren %%i %1_%%~ni.gbx & copy /Y %1_%%~ni.gbx "z:\на рисование\8000neg"
for %%i in (pos*AGFA.gbx) do set /a n+=1 & ren %%i %1_%%~ni.gbx & copy /Y %1_%%~ni.gbx "z:\на рисование\AGFA8000pos"
for %%i in (neg*AGFA.gbx) do set /a n+=1 & ren %%i %1_%%~ni.gbx & copy /Y %1_%%~ni.gbx "z:\на рисование\AGFA8000neg"
if %n% == 0 goto end
rem copy /Y neg*.gbx "z:\на рисование\8000neg"
rem copy /Y pos*.gbx "z:\на рисование\8000pos"
set urlq="http://baza.mpp/phototemplates/?action=add&user=%username%&filenames=%n% %gdir%"
echo tear.exe %urlq% >dbq.bat
rem set urlq="http://bazawork1/addpt.php?user=%username%&filenames=%n% %gdir%"
rem echo tear.exe %urlq% >>dbq.bat
call .\dbq.bat
del /q .\dbq.bat
:end