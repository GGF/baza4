// fork.cpp : Defines the entry point for the console application.
//

#include "stdafx.h"
#include <process.h>
#include <atlstr.h>
#include <stdlib.h>
#include <stdio.h>



int _tmain(int argc, _TCHAR* argv[])
{

	if ( argc <2 ) return 1;
	CString str;
	str = "";
	str += "\"";
	str += argv[2];
	str += "\"";

	_execl(argv[1],argv[1],str,NULL);

	return 0;
}

