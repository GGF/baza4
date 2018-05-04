#include "StdAfx.h"
#include "testpoint.h"


TestPoint::TestPoint(void)
: m_Weight(0)
, thickness(0)
{
	x = y = 0;
	m_Probe.x = -10;
	m_Probe.y = -10;
}

TestPoint::TestPoint(double _x, double _y): m_Weight(0), thickness(0)
{
	x = _x;
	y = _y;
	m_Probe.x = -10;
	m_Probe.y = -10;

}

TestPoint::TestPoint(double _x, double _y, double thick): m_Weight(0)
{
	thickness = thick;
	x = _x;
	y = _y;
	m_Probe.x = -10;
	m_Probe.y = -10;

}

TestPoint::TestPoint(TestPoint* o)
{
	m_Weight = o->m_Weight ;
	x = o->x;
	y = o->y;
	m_Probe.x = o->m_Probe.x;
	m_Probe.y = o->m_Probe.y;
	thickness = o->thickness;

}

TestPoint::~TestPoint(void)
{
}

// ѕроверка на пересекаемость
bool TestPoint::IsCross(TestPoint tp)
{
	if (m_Probe.x == 0 || tp.m_Probe.x == 0) return false;
	double distance;
	double t1,t2;// параметры пр€мых
	double a11,a12,a13;// вектор первой пр€мой
	double a21,a22,a23;
	double r11,r12,r13;
	double r21,r22,r23;
	double aa1,aa2,aa3;

	a11 = m_Probe.x - x;
	a12 = m_Probe.y - y;
	a13 = 91;

	r11 = x;
	r12 = y;
	r13 = 0;

	a21 = tp.m_Probe.x - tp.x;
	a22 = tp.m_Probe.y - tp.y;
	a23 = 91;

	r21 = tp.x;
	r22 = tp.y;
	r23 = 0;

	aa1 = a12*a23 - a13*a22;
	aa2 = a13*a21 - a11*a23;
	aa3 = a11*a22 - a12*a21;

	double devisor = a21*a12*aa3 - a21*a13*aa2 + a22*a13*aa1 - a22*a11*aa3 + a23*a11*aa2 - a23*a12*aa1;
	if (devisor == 0) return false;

	double devidend1 = r11*a22*aa3-r11*a23*aa2-r21*a22*aa3+r21*a23*aa2+r12*a23*aa1-r12*a21*aa3-r22*a23*aa1+r22*a21*aa3+r13*a21*aa2-r13*a22*aa1-r23*a21*aa2+r23*a22*aa1;
	double devidend2 = -r21*a12*aa3+r21*a13*aa2+r11*a12*aa3-r11*a13*aa2-r22*a13*aa1+r22*a11*aa3+r12*a13*aa1-r12*a11*aa3-r23*a11*aa2+r23*a12*aa1+r13*a11*aa2-r13*a12*aa1;

	t1 = devidend1 / devisor;
	t2 = devidend2 / devisor;

	if ((r13+a13*t1)> 91 || (r13+a13*t1)<0 ) return false;
	if ((r23+a23*t1)> 91 || (r23+a23*t2)<0 ) return false;

	distance = sqrt(((r11+a11*t1)-(r21+a21*t2))*((r11+a11*t1)-(r21+a21*t2)) + ((r12+a12*t1)-(r22+a22*t2))*((r12+a12*t1)-(r22+a22*t2)) + ((r13+a13*t1)-(r23+a23*t2))*((r13+a13*t1)-(r23+a23*t2)));

	if (distance < (thickness+tp.thickness+0.7)) 
		return true;
	return false;
}

// –ассто€ние между точками
double TestPoint::Dist(TestPoint o)
{
	return sqrt((o.x-x)*(o.x-x)+(o.y-y)*(o.y-y));
}
