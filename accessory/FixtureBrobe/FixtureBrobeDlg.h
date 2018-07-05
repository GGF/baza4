// FixtureBrobeDlg.h : header file
//

#pragma once
#include "testpoint.h"
#include "fixprobe.h"
#include "afxcmn.h"
#include <list>
#include "afxwin.h"

using namespace std ;


// CFixtureBrobeDlg dialog
class CFixtureBrobeDlg : public CDialog
{
// Construction
public:
	CFixtureBrobeDlg(CWnd* pParent = NULL);	// standard constructor

// Dialog Data
	enum { IDD = IDD_FIXTUREBROBE_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV support


// Implementation
protected:
	HICON m_hIcon;

	// Generated message map functions
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()
public:
	afx_msg void OnBnClickedOpeninputfiles();
	afx_msg void OnBnClickedOpenoutputfile();
	afx_msg void OnBnClickedConvert();
	afx_msg void OnEnChangeEditinputfiles();
	CString m_InputFiles;
	CString m_OutputFiles;
	bool ReadFiles(void);
	bool WriteFiles(void);
	void Compute(void);
	list <TestPoint> m_List;
	list <TestPoint> m_ResList;
	CProgressCtrl m_progress;
	long m_Number;
	// список положений для проб
	list <FixProbe> m_Grid;
	BOOL m_Top;
	BOOL ThreeBarrel;
	CStatic m_Probed;
	CStatic m_Total;
};
