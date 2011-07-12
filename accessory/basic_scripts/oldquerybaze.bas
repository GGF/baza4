Function oldquerybaze(q As String) As Integer
    Dim mydir, batfile, resfile, MyCode As String
    Dim f, fs, a As Object
    Dim RetVal
    
    batfile = Trim(Str(Int(Rnd * 1000))) + ".bat"
    resfile = Trim(Str(Int(Rnd * 1000))) + ".res"
    mydir = ActiveWorkbook.Path
    
    Set fs = CreateObject("Scripting.FileSystemObject")
    Set a = fs.CreateTextFile(mydir + "\" + batfile, True)
    a.WriteLine ("chcp 1251")
    
    a.WriteLine ("x:\tool\tear.exe """ + "http://baza4/?level=update&update[act]=" + q + """ >> """ + mydir + "\" + resfile + """ ")
    a.Close
    
    RetVal = ShellandWait(mydir + "\" + batfile)
            
    If (fs.FileExists(mydir + "\" + resfile)) Then
        Set a = fs.OpenTextFile(mydir + "\" + resfile)
        MyCode = a.readline
        a.Close
    End If
    
    If (fs.FileExists(mydir + "\" + batfile)) Then
        Set f = fs.GetFile(mydir + "\" + batfile)
        f.Delete True
    End If
    
    If (fs.FileExists(mydir + "\" + resfile)) Then
        Set f = fs.GetFile(mydir + "\" + resfile)
        f.Delete True
    End If
    
    querybaze = Val(MyCode)
    If querybaze = 0 Then querybaze = -1
        
End Function