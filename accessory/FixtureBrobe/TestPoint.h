#pragma once
#include <math.h>
#include "fixprobe.h"

class TestPoint
{
public:
	TestPoint(void);
	TestPoint(TestPoint*);
	TestPoint(double,double);
	TestPoint(double,double,double);
	~TestPoint(void);

public: 
	double x,y;

// �������� ��� ���������� �� ���������� �� ����
	bool operator <(TestPoint o) {
		if ( sqrt(o.x*o.x+o.y*o.y) > sqrt(x*x+y*y) ) 
			return true;
		else
			return false;
	}

	TestPoint& operator =(TestPoint o) {
		return *new TestPoint(o.x, o.y);
	}

	FixProbe m_Probe;
	// �������� �� ��������������
	bool IsCross(TestPoint tp);
	// ������� ����������� ��� �����
	long m_Weight;
	// ���������� ����� �������
	double Dist(TestPoint tp);
	double thickness;
};
