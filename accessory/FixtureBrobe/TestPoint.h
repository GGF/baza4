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

// оператор для сортировки по расстоянию от нуля
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
	// Проверка на пересекаемость
	bool IsCross(TestPoint tp);
	// весовой коэффициэнт для точки
	long m_Weight;
	// Расстояние между точками
	double Dist(TestPoint tp);
	double thickness;
};
