\ls | gawk '
function trySeparator(currentSeparator) {
	tmp = match($0, currentSeparator, a)
	if(tmp) {
		split($0, a, currentSeparator)
		nb = length(a)
		path=""
		for (i=1; i<=nb; i++) {
			printf a[i] " | "
			path=path a[i] "/"
		}
		print ""
		system("mkdir -p \"" path "\"")
		system("mv \"" $0 "\"/* \"" path "\"")
		system("rmdir \"" $0 "\"")
		
		return 1
	}
	else
		return 0
}

{
	separators[0] = " - "
	separators[1] = " – "
	separators[2] = "-"
	separators[3] = "–"
	separators[4] = "  "
	nbSeparators = length(separators)
	
	found = 0
	separatorIndice = 0
	while(!found && separatorIndice < nbSeparators) {
		currentSeparator = separators[separatorIndice]
		#print "-- CURRENT TEST : " $0 "(" currentSeparator ") --"
		found = trySeparator(currentSeparator)

		separatorIndice ++
	}
	
	if(!found) {
		print "___ not matched : " $0
	}
	
}
'

