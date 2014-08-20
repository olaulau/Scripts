#! /bin/bash


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
	javac $SUB_DIR/Test.java
	java ${BASE_PACKAGE}Test
else
	echo " [ using GCJ ] "
	rm -f $SUB_DIR/*.class $SUB_DIR/Test.bin
	gcj -C $SUB_DIR/Test.java
 	gcj --main=${BASE_PACKAGE}Test $SUB_DIR/*.class -o $SUB_DIR/Test.bin
	$SUB_DIR/Test.bin
fi


echo " ----- "
echo $?

