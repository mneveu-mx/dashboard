#!/bin/sh
nbreProcess=`ps -aux 2>/dev/null | grep "/usr/lib/chromium-browser/chromium-browser" 2>/dev/null | wc -l`
if [ "$nbreProcess" -lt 2 ]
then
	DISPLAY=:0 chromium-browser -kiosk
fi
