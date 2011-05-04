copy x:\tool\cam350m.pcb cam.pcb
attrib -R cam.pcb
copy z:\igor\dpp.s32 dpp.s32
ren *.zip origin.zip
ren *.cam *.pcb
ren W*2.gbr comp.gbr
ren 1C*_2.gbr comp.gbr
ren 1M*_2.gbr comp.gbr
ren W*1.gbr solder.gbr
ren 2C*_1.gbr solder.gbr
ren 2M*_1.gbr solder.gbr
ren M*2.gbr mcomp.gbr
ren M*1.gbr msolder.gbr
ren 1C*_1.gbr i3.gbr
if %errorlevel%==0 copy z:\igor\mpp4.s32 mpp4.s32
ren 1M*_1.gbr i3.gbr
if %errorlevel%==0 copy z:\igor\mpp4.s32 mpp4.s32
ren 2C*_2.gbr i2.gbr
if %errorlevel%==0 del /q dpp.s32
ren 2M*_2.gbr i2.gbr
if %errorlevel%==0 del /q dpp.s32
ren s*.lnz 2*.frz
ren w*.lnz 2*.mkr
ren l*.lnz 2*.mkr
