// fork.cpp : Defines the entry point for the console application.
//

#include "stdafx.h"
#include <process.h>


int _tmain(int argc, _TCHAR* argv[])
{

	if ( argc <2 ) return 1;
	for (int i=1;i<argc;i++){
		argv[i-1] = argv[i];
		printf("%s\n",argv[i-1]);
	}
	argv[argc-1] = NULL;
	_execv(argv[0],argv);

	return 0;
}

