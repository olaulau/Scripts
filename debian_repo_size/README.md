# debian repo size
a PHP tool to calculate the size of a debian-based mirror

## current features & operating :
- reads a source.list / mirror.list file 
- downloads corresponding Packages.bz2 files
- integrate corresponding data's into an in-memory sqlite database
- display statistics about size required to store corresponding mirrors
 [see an execution](./trace.txt)

## futures features :
- store database locally as a cache
- maybe a nicer web version