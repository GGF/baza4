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
// оператор для сортировки по расстоянию от нуля
	bool operator <(FixProbe o) {
		if ( sqrt(o.x*o.x+o.y*o.y) > sqrt(x*x+y*y) ) 
			return true;
		else
			return false;
	}


	// Ближайшая точка
	bool Fist(TestPoint);
	// Вторая точка
	bool Second(TestPoint tp);
	// Третья точка
	bool Thrird(TestPoint tp);
	bool Test(TestPoint o, double r);
	// Количество проб в этой точке...
	int m_number;
};
