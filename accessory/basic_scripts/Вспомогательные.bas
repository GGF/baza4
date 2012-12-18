Option Explicit

Private Declare Function OpenProcess Lib "kernel32" _
(ByVal dwDesiredAccess As Long, ByVal bInheritHandle As Long, _
ByVal dwProcessId As Long) As Long

Private Declare Function GetExitCodeProcess Lib "kernel32" _
(ByVal hProcess As Long, lpExitCode As Long) As Long

Private Const STATUS_PENDING = &H103&
Private Const PROCESS_QUERY_INFORMATION = &H400
Private Declare Function GetUserName Lib "advapi32.dll" _
Alias "GetUserNameA" (ByVal lpBuffer As String, nSize As Long) As Long

Function UserNameWindows() As String
    
    Dim lngLen As Long
    Dim strBuffer As String
    
    Const dhcMaxUserName = 255
    
    strBuffer = Space(dhcMaxUserName)
    lngLen = dhcMaxUserName
    If CBool(GetUserName(strBuffer, lngLen)) Then
        UserNameWindows = Left$(strBuffer, lngLen - 1)
    Else
        UserNameWindows = ""
    End If
End Function

Public Function ShellandWait(ExeFullPath As String, _
Optional TimeOutValue As Long = 0) As Boolean
    
    Dim lInst As Long
    Dim lStart As Long
    Dim lTimeToQuit As Long
    Dim sExeName As String
    Dim lProcessId As Long
    Dim lExitCode As Long
    Dim bPastMidnight As Boolean
    
    On Error GoTo ErrorHandler

    lStart = CLng(Timer)
    sExeName = ExeFullPath

    'Deal with timeout being reset at Midnight
    If TimeOutValue > 0 Then
        If lStart + TimeOutValue < 86400 Then
            lTimeToQuit = lStart + TimeOutValue
        Else
            lTimeToQuit = (lStart - 86400) + TimeOutValue
            bPastMidnight = True
        End If
    End If

    lInst = Shell(sExeName, vbMinimizedNoFocus)
    
lProcessId = OpenProcess(PROCESS_QUERY_INFORMATION, False, lInst)

    Do
        Call GetExitCodeProcess(lProcessId, lExitCode)
        DoEvents
        If TimeOutValue And Timer > lTimeToQuit Then
            If bPastMidnight Then
                 If Timer < lStart Then Exit Do
            Else
                 Exit Do
            End If
    End If
    Loop While lExitCode = STATUS_PENDING
    
    ShellandWait = True
   
ErrorHandler:
ShellandWait = False
Exit Function
End Function

Function URLencode(encodestr) As String
Dim encodestr1, s As String
Dim n As Integer

encodestr1 = ""
For n = 1 To Len(encodestr)
    s = Mid(encodestr, n, 1)
    Select Case s
    Case "?"
        s = "%C0"
    Case "?"
        s = "%C1"
    Case "?"
        s = "%C2"
    Case "?"
        s = "%C3"
    Case "?"
        s = "%C4"
    Case "?"
        s = "%C5"
    Case "?"
        s = "%A8"
    Case "?"
        s = "%C6"
    Case "?"
        s = "%C7"
    Case "?"
        s = "%C8"
    Case "?"
        s = "%C9"
    Case "?"
        s = "%CA"
    Case "?"
        s = "%CB"
    Case "?"
        s = "%CC"
    Case "?"
        s = "%CD"
    Case "?"
        s = "%CE"
    Case "?"
        s = "%CF"
    Case "?"
        s = "%D0"
    Case "?"
        s = "%D1"
    Case "?"
        s = "%D2"
    Case "?"
        s = "%D3"
    Case "?"
        s = "%D4"
    Case "?"
        s = "%D5"
    Case "?"
        s = "%D6"
    Case "?"
        s = "%D7"
    Case "?"
        s = "%D8"
    Case "?"
        s = "%D9"
    Case "?"
        s = "%DA"
    Case "?"
        s = "%DB"
    Case "?"
        s = "%DC"
    Case "?"
        s = "%DD"
    Case "?"
        s = "%DE"
    Case "?"
        s = "%DF"
    Case "?"
        s = "%E0"
    Case "?"
        s = "%E1"
    Case "?"
        s = "%E2"
    Case "?"
        s = "%E3"
    Case "?"
        s = "%E4"
    Case "?"
        s = "%E5"
    Case "?"
        s = "%B8"
    Case "?"
        s = "%E6"
    Case "?"
        s = "%E7"
    Case "?"
        s = "%E8"
    Case "?"
        s = "%E9"
    Case "?"
        s = "%EA"
    Case "?"
        s = "%EB"
    Case "?"
        s = "%EC"
    Case "?"
        s = "%ED"
    Case "?"
        s = "%EE"
    Case "?"
        s = "%EF"
    Case "?"
        s = "%F0"
    Case "?"
        s = "%F1"
    Case "?"
        s = "%F2"
    Case "?"
        s = "%F3"
    Case "?"
        s = "%F4"
    Case "?"
        s = "%F5"
    Case "?"
        s = "%F6"
    Case "?"
        s = "%F7"
    Case "?"
        s = "%F8"
    Case "?"
        s = "%F9"
    Case "?"
        s = "%FA"
    Case "?"
        s = "%FB"
    Case "?"
        s = "%FC"
    Case "?"
        s = "%FD"
    Case "?"
        s = "%FE"
    Case "?"
        s = "%FF"
    Case " "
        s = "%20"
    Case ""
        s = "%24"
    Case "&"
        s = "%26"
    Case "+"
        s = "%2B"
    Case ","
        s = "%2C"
    Case "/"
        s = "%2F"
    Case ":"
        s = "%3A"
    Case ";"
        s = "%3B"
    Case "="
        s = "%3D"
    Case "?"
        s = "%3F"
    Case "@"
        s = "%40"
    Case ">"
        s = "%3C"
    Case "<"
        s = "%3E"
    Case "#"
        s = "%23"
    Case "%"
        s = "%25"
    Case "{"
        s = "%7B"
    Case "}"
        s = "%7D"
    Case "|"
        s = "%7C"
    Case "\"
        s = "%5C"
    Case "^"
        s = "%5E"
    Case "~"
        s = "%7E"
    Case "["
        s = "%5B"
    Case "]"
        s = "%5E"
    Case "`"
        s = "%60"
    Case Else
    End Select
    encodestr1 = encodestr1 + s
Next
URLencode = encodestr1
End Function

Function tostr(v As Variant) As String
    If IsNumeric(v) Then
        tostr = Trim(Str(v))
        Exit Function
    Else
        tostr = URLencode(Trim(v))
        Exit Function
    End If
End Function
Function getrowbyname(name As String) As String
    getrowbyname = Range(name).Row
End Function
Function getvalbyrowcol(name As String, col As Integer) As Variant
    getvalbyrowcol = Sheets("??? ????????").Range(Chr(66 + col) + getrowbyname(name)).Value
End Function
Function querybaze(q As String) As Integer

    Dim URL As String
    Dim res As String
    URL = "http://baza4/?level=update&update[act]=" + q
    res = html_get(URL)
    querybaze = Val(res)
    If querybaze = 0 Then
        MsgBox "Error URL-" + URL + " Result: " + res
        querybaze = -1
    End If
End Function

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

Function html_get(ByVal strURL As String) As String
    Dim doc1 As HTMLDocument
    Dim doc2 As HTMLDocument
    Set doc1 = New HTMLDocument
    Set doc2 = doc1.createDocumentFromUrl(strURL, "")
    Do Until doc2.readyState = "complete"
        DoEvents
    Loop
    html_get = doc2.body.innerHTML
    Set doc2 = Nothing
    Set doc1 = Nothing
End Function

Function getsize(size As String, pos As Integer) As Single
    Dim ar() As String
    Dim findpos
    Dim findchar
    findchar = "x"
    findpos = InStr(1, size, findchar, 1)
    If (findpos > 0) Then
        ar = Split(size, "x")
    Else
        ar = Split(size, "?")
    End If
    getsize = Val(ar(pos - 1))
End Function

