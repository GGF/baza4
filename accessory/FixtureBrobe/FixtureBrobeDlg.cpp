// FixtureBrobeDlg.cpp : implementation file
//

#include "stdafx.h"
#include "FixtureBrobe.h"
#include "FixtureBrobeDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CAboutDlg dialog used for App About

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// Dialog Data
	enum { IDD = IDD_ABOUTBOX };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

// Implementation
protected:
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()


// CFixtureBrobeDlg dialog



CFixtureBrobeDlg::CFixtureBrobeDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CFixtureBrobeDlg::IDD, pParent)
	, m_InputFiles(_T("g:\\666\\layer_14.lgr"))
	, m_OutputFiles(_T("g:\\666\\1.scr"))
	, m_List(0)
	, m_Number(0)
	, m_Top(true)
	, ThreeBarrel(true)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CFixtureBrobeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDITINPUTFILES, m_InputFiles);
	DDX_Text(pDX, IDC_EDITOUTPUTFILES, m_OutputFiles);
	DDX_Control(pDX, IDC_PROGRESS1, m_progress);
	DDX_Check(pDX, IDC_CHECK1, m_Top);
	DDX_Check(pDX, IDC_CHECK2, ThreeBarrel);
	DDX_Control(pDX, IDC_PROBED, m_Probed);
	DDX_Control(pDX, IDC_TOTAL, m_Total);
}

BEGIN_MESSAGE_MAP(CFixtureBrobeDlg, CDialog)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	//}}AFX_MSG_MAP
	ON_BN_CLICKED(IDC_OPENINPUTFILES, OnBnClickedOpeninputfiles)
	ON_BN_CLICKED(IDC_OPENOUTPUTFILE, OnBnClickedOpenoutputfile)
	ON_BN_CLICKED(IDC_CONVERT, OnBnClickedConvert)
END_MESSAGE_MAP()


// CFixtureBrobeDlg message handlers

BOOL CFixtureBrobeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// Add "About..." menu item to system menu.

	// IDM_ABOUTBOX must be in the system command range.
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// Set the icon for this dialog.  The framework does this automatically
	//  when the application's main window is not a dialog
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon

	// TODO: Add extra initialization here
	
	return TRUE;  // return TRUE  unless you set the focus to a control
}

void CFixtureBrobeDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

// If you add a minimize button to your dialog, you will need the code below
//  to draw the icon.  For MFC applications using the document/view model,
//  this is automatically done for you by the framework.

void CFixtureBrobeDlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // device context for painting

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// Center icon in client rectangle
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// Draw the icon
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// The system calls this function to obtain the cursor to display while the user drags
//  the minimized window.
HCURSOR CFixtureBrobeDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}

void CFixtureBrobeDlg::OnBnClickedOpeninputfiles()
{
	UpdateData ();
	CFileDialog dlg( true );
	if ( dlg.DoModal() != IDOK ) return;
	m_InputFiles = dlg.GetPathName ();
	UpdateData (false);
}

void CFixtureBrobeDlg::OnBnClickedOpenoutputfile()
{
	UpdateData ();
	CFileDialog dlg( false );
	if ( dlg.DoModal() != IDOK ) return;
	m_OutputFiles = dlg.GetPathName ();
	UpdateData (false);
}

void CFixtureBrobeDlg::OnBnClickedConvert()
{
	UpdateData();

	if (m_InputFiles == "" ) {
		AfxMessageBox(IDS_NOINFIL);
		return;
	}
	if (m_OutputFiles == "") {
		AfxMessageBox(IDS_NOOUTFIL);
		return;
	}

	if (!ReadFiles()) return;
	m_List.sort();
	Compute ();
	if (!WriteFiles()) return;
	
	AfxMessageBox(IDS_SUCCESS);//нормальное завершение

}

TestPoint CTP; // current test point
bool bydistance(FixProbe o1, FixProbe o2){
	if ( sqrt((o1.x-CTP.x)*(o1.x-CTP.x)+(o1.y-CTP.y)*(o1.y-CTP.y)) > sqrt(sqrt((o2.x-CTP.x)*(o2.x-CTP.x)+(o2.y-CTP.y)*(o2.y-CTP.y))) ) {
		return true;
	} else 
		return false;
}

bool bynumber(FixProbe o1, FixProbe o2){
	if ( o1.m_number < o2.m_number ) {
		return true;
	} else 
		return false;
}

bool zeronumber(FixProbe o) {
	if (o.m_number == 0) return true;
	else return false;
}

bool CFixtureBrobeDlg::ReadFiles(void)
{
	CStdioFile *pInFile;

	m_List.clear ();
   TRY
   {
		pInFile = new CStdioFile(m_InputFiles , CFile::modeRead | CFile::shareDenyNone);
	    ULONGLONG nFileSize = pInFile->GetLength();
		m_Number = 0;

		nFileSize /= 1024;
		m_progress.SetRange32(0,(long)nFileSize );
		m_progress.SetStep (1);
		m_progress.SetPos(0);
		m_progress.SetBkColor (RGB(0,255,0));

		long counter = 0;
		double lX=-1203245403.12315;

		CString fstr;
		double xd=3;
		double yd=3;
		double xpred,ypred;

		while ( pInFile->ReadString (fstr) ) {
			counter += fstr.GetLength ();
			//if (m_progress.GetPos () == 200) m_progress.SetPos (0);
			m_progress.SetPos (counter/1024);

			if ( fstr.Left (1) == "X" || fstr.Left (1) == "Y") {
				CString str[4];
				int curPos =0;
				int i = 0;

				while (curPos > -1) {
					str[i]=fstr.Tokenize ("XYD",curPos);
					i++;
				}

				m_List.push_back (TestPoint(atoi(str[0])/pow(10,xd),atoi(str[1])/pow(10,yd)));

				m_Number++;//считаем количество строк
			} else if (fstr.Left (4)=="G54D" ) {

			}
		}

   }
   CATCH(CFileException, pEx)
   {
      // Simply show an error message to the user.
      pEx->ReportError();
   }
   AND_CATCH(CMemoryException, pEx)
   {
      // We can't recover from this memory exception, so we'll
      // just terminate the app without any cleanup. Normally, an
      // an application should do everything it possibly can to
      // clean up properly and _not_ call AfxAbort().
      AfxAbort();
   }
   END_CATCH


   // If an exception occurs in the CFile constructor,
   // the language will free the memory allocated by new
   // and will not complete the assignment to pFile.
   // Thus, our clean-up code needs to test for NULL.
   if (pInFile != NULL)   {
      pInFile->Close();
      delete pInFile;
   }
	char m[200];
   m_Total.SetWindowText(itoa(m_Number,m,10));

return true;
}

bool CFixtureBrobeDlg::WriteFiles(void)
{
	FILE *pOutFile;

	pOutFile = fopen(m_OutputFiles,"w");
	if (pOutFile == NULL) {
		AfxMessageBox(IDS_OPENOUTFILE);
		return false;
	}

	UpdateData();
	list <TestPoint> ::iterator curr;

	m_progress.SetRange32(0,m_Number );
	m_progress.SetStep (1);
	m_progress.SetPos(0);
	m_progress.SetBkColor (RGB(255,255,255));

	fprintf(pOutFile ,"util_bned@\nview_close@\n");

	CString str;

	if ( m_Top ) {
		str = "bn_addtoppin@";
	} else {
		str = "bn_addbotpin@";
	}


	for ( curr=m_List.begin (); curr != m_List.end (); curr++) {
		if ( curr->m_Probe.x < -5 ) continue;
		fprintf(pOutFile ,"%s\nsetsnap@ 1\n",str);
		fprintf(pOutFile ,"axy@ %.4f,%.4f\n",curr->x,curr->y);// тестовая точка
		fprintf(pOutFile ,"axy@ %.4f,%.4f\n",curr->m_Probe.x,curr->m_Probe.y);// точка на кондукторе
		fprintf(pOutFile ,"back@\n");
		m_progress.StepIt ();
	}

	fprintf(pOutFile ,"view_all@\nview_redraw@\n");


	fclose(pOutFile);

// дополнительно
/*	pOutFile = fopen("g:\\666\\fix.dat","w");
	if (pOutFile == NULL) {
		AfxMessageBox(IDS_OPENOUTFILE);
		return false;
	}

	list <FixProbe> ::iterator fcurr;

	for ( fcurr=m_Grid.begin (); fcurr != m_Grid.end (); fcurr++) {
		fprintf(pOutFile ,"%.4f %.4f %d\n",fcurr->x,fcurr->y,fcurr->m_number);// тестовая точка
	}

	fclose(pOutFile);

	pOutFile = fopen("g:\\666\\point.dat","w");
	if (pOutFile == NULL) {
		AfxMessageBox(IDS_OPENOUTFILE);
		return false;
	}

	for ( curr=m_List.begin (); curr != m_List.end (); curr++) {
		fprintf(pOutFile ,"%.4f %.4f %d\n",curr->x,curr->y,curr->m_Weight);// тестовая точка
	}

	fclose(pOutFile);
*/
	return true;
}

void CFixtureBrobeDlg::Compute(void)
{

	m_List.sort();
	// создание сетки
	double xl = m_List.back().x;
	double yl = m_List.back().y;

	for ( int i = -5; i<(xl/2.54+5); i++) {
		for (int j= -5; j<(yl/2.54+5); j++){
			m_Grid.push_back (FixProbe (i*2.54,j*2.54));
		}
	}

	m_Grid.sort ();

	list <TestPoint> ::iterator curr,curr1;
	list <FixProbe> ::iterator fcurr;

	char col = 0;

	m_progress.SetRange32(0,m_List.size () );
	m_progress.SetStep (1);
	m_progress.SetPos(0);
	m_progress.SetBkColor (RGB(255,150,col));

// определение веса для сетки
	for ( curr=m_List.begin (); curr != m_List.end (); curr++) {
		m_progress.StepIt ();
		for ( fcurr=m_Grid.begin (); fcurr != m_Grid.end (); fcurr++) {
			if ( fcurr->Test(*curr,9) ) {
				fcurr->m_number++;
			}
		}
	}

	//m_Grid.remove_if (zeronumber);

// определение веса для точки
/*	for ( curr=m_List.begin (); curr != m_List.end (); curr++) {
		m_progress.StepIt ();
		for ( fcurr=m_Grid.begin (); fcurr != m_Grid.end (); fcurr++) {
			if ( fcurr->Test(*curr,8.5) ) {
				curr->m_Weight += fcurr->m_number;
			}
		}
	}
*/

//	m_Grid.sort(bynumber);

	m_Number = 0;

	for (double r=0.2; r<9; r+=.2){
	
		m_progress.SetRange32(0,m_List.size () );
		m_progress.SetStep (1);
		m_progress.SetPos(0);
		m_progress.SetBkColor (RGB(255,0,col));

		for ( curr=m_List.begin (); curr != m_List.end (); curr++) {
			m_progress.StepIt ();
			CTP = *curr;
			if (curr->m_Probe.x > -5 ) continue;
//			m_Grid.sort (bydistance);
			for ( fcurr=m_Grid.begin (); fcurr != m_Grid.end (); fcurr++) {
				if ( fcurr->Test(*curr,r) ) {
					col++;
					m_progress.SetBkColor (RGB(255,0,col));
					m_Number++;
					char m[200];
					m_Probed.SetWindowText (itoa(m_Number,m,10));
					curr->m_Probe.x = fcurr->x;
					curr->m_Probe.y = fcurr->y;
					m_Grid.erase (fcurr);// занята
					break;
				}
			}
		}
	}

	m_progress.SetRange32(0,m_List.size () );
	m_progress.SetStep (1);
	m_progress.SetPos(0);
	col = 0;
	m_progress.SetBkColor (RGB(col,col,col));

/*
	for ( curr=m_List.begin (); curr != m_List.end (); curr++) {
		col++;
		for ( curr1=m_List.begin (); curr1 != m_List.end (); curr1++) {
			if ( curr == curr1 ) continue;
		//	if ( curr->Dist (*curr1) > 9 ) continue;
			if (curr->IsCross(*curr1)) {
				swap(curr->m_Probe.x,curr1->m_Probe.x);
				swap(curr->m_Probe.y,curr1->m_Probe.y);
			}
		}
		m_progress.StepIt ();
		m_progress.SetPos(0);
		m_progress.SetBkColor (RGB(col,col,col));
	}
*/
}
