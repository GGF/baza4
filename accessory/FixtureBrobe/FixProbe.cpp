#include "StdAfx.h"
#include "fixprobe.h"
#include "testpoint.h"
#include <math.h>

FixProbe::FixProbe(void)
: m_number(0)
{
	x = y = -10;
	m_number = 0;
}

FixProbe::FixProbe(double ax, double ay)
{
	x = ax;
	y = ay;
	m_number = 0;
}

FixProbe::~FixProbe(void)
{
}

bool FixProbe::operator ==(TestPoint o) {
	if ( sqrt((o.x-x)*(o.x-x)+(o.y-y)*(o.y-y)) < 5.5 ) 
		return true;
	else
		return false;
}

// Ближайшая точка
bool FixProbe::Fist(TestPoint o)
{
	if ( sqrt((o.x-x)*(o.x-x)+(o.y-y)*(o.y-y)) <= 0.8 ) 
		return true;
	else
		return false;
}

// Вторая точка
bool FixProbe::Second(TestPoint o)
{
	if ( sqrt((o.x-x)*(o.x-x)+(o.y-y)*(o.y-y)) <= 2 ) 
		return true;
	else
		return false;
}

// Третья точка
bool FixProbe::Thrird(TestPoint o)
{
	if ( sqrt((o.x-x)*(o.x-x)+(o.y-y)*(o.y-y)) <= 4 ) 
		return true;
	else
		return false;
}

bool FixProbe::Test(TestPoint o, double r)
{
	if ( sqrt((o.x-x)*(o.x-x)+(o.y-y)*(o.y-y)) <= r ) 
		return true;
	else
		return false;
}
