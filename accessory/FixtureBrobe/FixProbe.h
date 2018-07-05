#pragma once
//#include "testpoint.h"
#include <math.h>

class TestPoint;

class FixProbe
{
public:
	FixProbe(void);
	FixProbe(double,double);
	~FixProbe(void);
public:
	double x,y;

	bool operator ==(TestPoint o); 
// �������� ��� ���������� �� ���������� �� ����
	bool operator <(FixProbe o) {
		if ( sqrt(o.x*o.x+o.y*o.y) > sqrt(x*x+y*y) ) 
			return true;
		else
			return false;
	}


	// ��������� �����
	bool Fist(TestPoint);
	// ������ �����
	bool Second(TestPoint tp);
	// ������ �����
	bool Thrird(TestPoint tp);
	bool Test(TestPoint o, double r);
	// ���������� ���� � ���� �����...
	int m_number;
};
