##
## Auto Generated makefile, please do not edit
##
WXWIN:=C:\wxWidgets-2.8.10
WXCFG:=gcc_dll\mswu
ProjectName:=testqt

## Debug
ConfigurationName      :=Debug
IntermediateDirectory  :=./Debug
OutDir                 := $(IntermediateDirectory)
WorkspacePath          := "C:\Workspaces\GGF"
ProjectPath            := "C:\Workspaces\GGF"
CurrentFileName        :=
CurrentFulePath        :=
CurrentFileFullPath    :=
User                   :=User
Date                   :=01/19/10
CodeLitePath           :="C:\Program Files\CodeLite"
LinkerName             :=g++
ArchiveTool            :=ar rcus
SharedObjectLinkerName :=g++ -shared -fPIC
ObjectSuffix           :=.o
DependSuffix           :=.o.d
PreprocessSuffix       :=
DebugSwitch            :=-gstab
IncludeSwitch          :=-I
LibrarySwitch          :=-l
OutputSwitch           :=-o 
LibraryPathSwitch      :=-L
PreprocessorSwitch     :=-D
SourceSwitch           :=-c 
CompilerName           :=g++
OutputFile             :=$(IntermediateDirectory)/$(ProjectName)
Preprocessors          :=
ObjectSwitch           :=-o 
ArchiveOutputSwitch    := 
PreprocessOnlySwitch   :=
CmpOptions             := -g -W $(Preprocessors)
LinkOptions            :=  
IncludePath            :=  "$(IncludeSwitch)." "$(IncludeSwitch)." "$(IncludeSwitch)/usr/include/qt4" "$(IncludeSwitch)c:/QT/4.5.3/include/QtGui" "$(IncludeSwitch)c:/QT/4.5.3/include/" 
RcIncludePath          :=
Libs                   :=$(LibrarySwitch)QtCore4 $(LibrarySwitch)QtGui4 
LibPath                := "$(LibraryPathSwitch)." "$(LibraryPathSwitch)c:/QT/4.5.3/lib" "$(LibraryPathSwitch)c:/QT/4.5.3/bin" 


Objects=$(IntermediateDirectory)/main$(ObjectSuffix) 

##
## Main Build Tragets 
##
all: $(OutputFile)

$(OutputFile): makeDirStep $(Objects)
	@makedir $(@D)
	$(LinkerName) $(OutputSwitch)$(OutputFile) $(Objects) $(LibPath) $(Libs) $(LinkOptions)

makeDirStep:
	@makedir "./Debug"

PreBuild:


##
## Objects
##
$(IntermediateDirectory)/main$(ObjectSuffix): main.cpp $(IntermediateDirectory)/main$(DependSuffix)
	@makedir "./Debug"
	$(CompilerName) $(SourceSwitch) "C:/Workspaces/GGF/main.cpp" $(CmpOptions) $(ObjectSwitch)$(IntermediateDirectory)/main$(ObjectSuffix) $(IncludePath)
$(IntermediateDirectory)/main$(DependSuffix): main.cpp
	@makedir "./Debug"
	@$(CompilerName) $(CmpOptions) $(IncludePath) -MT$(IntermediateDirectory)/main$(ObjectSuffix) -MF$(IntermediateDirectory)/main$(DependSuffix) -MM "C:/Workspaces/GGF/main.cpp"

##
## Clean
##
clean:
	$(RM) $(IntermediateDirectory)/main$(ObjectSuffix)
	$(RM) $(IntermediateDirectory)/main$(DependSuffix)
	$(RM) $(IntermediateDirectory)/main$(PreprocessSuffix)
	$(RM) $(OutputFile)
	$(RM) $(OutputFile).exe

-include $(IntermediateDirectory)/*$(DependSuffix)


