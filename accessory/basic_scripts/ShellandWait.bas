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