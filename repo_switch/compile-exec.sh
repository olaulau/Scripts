rm -f Test
gcj --main=Test Test.java -o Test && ./Test

echo " --- "
echo $?
rm -f Test
