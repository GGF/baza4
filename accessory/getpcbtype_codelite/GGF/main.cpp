/*
 * #include <QApplication>
#include <QFont>
#include <QPushButton>

int main(int argc, char* argv[])
{
    QApplication app(argc, argv);

    QPushButton quit("Quit");

    quit.resize(75, 30);
    quit.setFont(QFont("Times", 18, QFont::Bold));

    QObject::connect(&quit, SIGNAL(clicked()), &app, SLOT(quit()));

    quit.show();

    return app.exec();
}
*/

// head.cpp : Defines the entry point for the console application.
//

//#include "stdafx.h"
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
//#include <atlstr.h>

#define PCAD2000 2000
#define PCAD4 4
#define PCAD85 85
#define CAM7 7
#define CAM8 8
#define CAM9 9
#define CAM10 10

void usage(){
	printf ("Usage: getpcbtype filename\n");
}
int main(int argc, char* argv[])
{
	char str[1024];
	str[0] = 0;
	int limit = 200;
	int counter = 0;
	FILE *pf;

	if (argc<2) {
		usage();
		return 1;
	} else {

		//for(int i=1;i<argc;i++) { str += argv[i]; str+=" "; }

		//if( atoi(argv[argc-1])!=0 ) 
			//limit = atoi(argv[argc-1]);
			strcpy(str,argv[1]);
	}

	printf("%s\n",str);

		pf = fopen(str,"r");
		if (!pf) {
			printf("Can't open file");
			return 1;
		}

	str = "";
	while (counter < limit) {
		char ch = fgetc (pf);
//		putc(ch,stdout);
		str[counter] = ch;
		counter++;
	}
	str[counter]=0;
	fclose (pf);

	if (str.Find ( "ACCEL_ASCII" )  != -1 )					return PCAD2000;
	if (str.Find ( "II" )		    != -1 )					return PCAD2000;
	if (str.Find ( "VERSION 5." )  != -1 )					return CAM7;
	if (str.Find ( "VERSION 6." )  != -1 )					return CAM7;
	if (str.Find ( "VERSION 7.0" )  != -1 )					return CAM7;
	if (str.Find ( "VERSION 7." )  != -1 )					return CAM8;
	if (str.Find ( "VERSION 8.0" )  != -1 )					return CAM8;
	if (str.Find ( "VERSION 8." )  != -1 )					return CAM9;
	if (str.Find ( "VERSION 9.0" )  != -1 )					return CAM9;
	if (str.Find ( "VERSION 9" )  != -1 )					return CAM10;
	if (str.Find ( "VERSION 10" )  != -1 )					return CAM10;
	if (str.Find ("version : 2.09") != -1 )					return PCAD85;
	if (str.Find ("version : 1.04") != -1 )					return PCAD4;

	return 0;
}

