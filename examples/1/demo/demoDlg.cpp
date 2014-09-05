
// demoDlg.cpp : implementation file
//

#include "stdafx.h"
#include "demo.h"
#include "demoDlg.h"
#include "afxdialogex.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CdemoDlg dialog




CdemoDlg::CdemoDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CdemoDlg::IDD, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CdemoDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CdemoDlg, CDialogEx)
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDOK, &CdemoDlg::OnBnClickedOk)
	ON_BN_CLICKED(IDCANCEL, &CdemoDlg::OnBnClickedCancel)
END_MESSAGE_MAP()


// CdemoDlg message handlers

BOOL CdemoDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// Set the icon for this dialog.  The framework does this automatically
	//  when the application's main window is not a dialog
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon

	// TODO: Add extra initialization here

	return TRUE;  // return TRUE  unless you set the focus to a control
}

// If you add a minimize button to your dialog, you will need the code below
//  to draw the icon.  For MFC applications using the document/view model,
//  this is automatically done for you by the framework.

void CdemoDlg::OnPaint()
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
		CDialogEx::OnPaint();
	}
}

// The system calls this function to obtain the cursor to display while the user drags
//  the minimized window.
HCURSOR CdemoDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}



void CdemoDlg::OnBnClickedOk()
{
	// TODO: 在此添加控件通知处理程序代码
	char buff[256];
    memset(buff,0,256);
	
	CString str;
	GetDlgItem(IDC_EDIT1)->GetWindowText(str);

	char * lpBuff = (LPSTR)(LPCTSTR)str;

	memcpy(buff,lpBuff,256);

	_asm{int 3h}
	_asm{pushad}
	MyEncrypt(buff);	
	_asm{popad}
	_asm{int 3h}

	GetDlgItem(IDC_STATIC)->SetWindowText(buff);
	
	//CDialogEx::OnOK();
}


void CdemoDlg::OnBnClickedCancel()
{
	// TODO: 在此添加控件通知处理程序代码
	CDialogEx::OnCancel();
}


#pragma code_seg (".text$unest_here") 
bool CdemoDlg::MyEncrypt(char * buff){	
	int flag = *(int*)buff;
	if (0x00303030 == flag){
	    * (int *)buff = 0x00003131;
	}else{
		for (int i=0;;i++){
			if (0 == buff[i]){
				break;		
			}else{
				int j = buff[i];
				j -= 0x30;
				j += i+1;
				while (j > 9)
					j -= 10;
				buff[i] = j + 0x30;
			}
		}
	}
	return true;
}
#pragma code_seg ()