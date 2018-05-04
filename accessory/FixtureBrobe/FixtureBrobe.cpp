// FixtureBrobe.cpp : Defines the class behaviors for the application.
//

#include "stdafx.h"
#include "FixtureBrobe.h"
#include "FixtureBrobeDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CFixtureBrobeApp

BEGIN_MESSAGE_MAP(CFixtureBrobeApp, CWinApp)
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()


// CFixtureBrobeApp construction

CFixtureBrobeApp::CFixtureBrobeApp()
{
	// TODO: add construction code here,
	// Place all significant initialization in InitInstance
}


// The one and only CFixtureBrobeApp object

CFixtureBrobeApp theApp;


// CFixtureBrobeApp initialization

BOOL CFixtureBrobeApp::InitInstance()
{
	CWinApp::InitInstance();


	CFixtureBrobeDlg dlg;
	m_pMainWnd = &dlg;
	INT_PTR nResponse = dlg.DoModal();
	if (nResponse == IDOK)
	{
		// TODO: Place code here to handle when the dialog is
		//  dismissed with OK
	}
	else if (nResponse == IDCANCEL)
	{
		// TODO: Place code here to handle when the dialog is
		//  dismissed with Cancel
	}

	// Since the dialog has been closed, return FALSE so that we exit the
	//  application, rather than start the application's message pump.
	return FALSE;
}
