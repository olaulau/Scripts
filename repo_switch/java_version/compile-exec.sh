#! /bin/bash

MICRO_DATE_CMD="date +%s%6N"

SUB_DIR="pkg"
BASE_PACKAGE="pkg."


## decide GCJ or JAVA
if [ "$1" = "--java" ]
then
	GCJ=0
else
	JAVA_COMPIL="gcj"
	$JAVA_COMPIL 2>/dev/null
	let GCJ_TEST=$?
	if [ $GCJ_TEST -eq 127 ]
	then
		GCJ=0
	else
		GCJ=1
	fi
fi


## run with GCJ or JAVA
if [ $GCJ -eq 0 ]
then
	echo " [ using openJDK ] "
	rm -f $SUB_DIR/*.class
	let compil_begin_date=`$MICRO_DATE_CMD`
	javac $SUB_DIR/Test.java
	let compil_end_date=`$MICRO_DATE_CMD`
	let exec_begin_date=`$MICRO_DATE_CMD`
	java ${BASE_PACKAGE}Test
	exec_exit_status=$?
	let exec_end_date=`$MICRO_DATE_CMD`
else
	echo " [ using GCJ ] "
	rm -f $SUB_DIR/*.class Test.bin
	let compil_begin_date=`$MICRO_DATE_CMD`
	gcj -C $SUB_DIR/Test.java
 	gcj --main=${BASE_PACKAGE}Test $SUB_DIR/*.class -o Test.bin
	let compil_end_date=`$MICRO_DATE_CMD`
	let exec_begin_date=`$MICRO_DATE_CMD`
	./Test.bin
	exec_exit_status=$?
	let exec_end_date=`$MICRO_DATE_CMD`
fi

let compile_time=($compil_end_date-$compil_begin_date)/1000
let exec_time=($exec_end_date-$exec_begin_date)/1000

echo " ----- "
echo "compil time = $compile_time ms"
echo "exec time = $exec_time ms"
echo " => $exec_exit_status"


