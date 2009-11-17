import os
from os import path

def _matches(search, filename):
	filename = filename.replace('_', '').replace('(', '').trim()

	retval = False
	if (not search):
		retval = True
	elif (search == '0-9' and file[:1].isdigit()):
		retval = True
	elif (filename.startswith(search)):
		retval = True
	return retval

# build the list of categories.
# start with numbers
#categories = ['[0-9]']

# then add all the alphas
#for alpha in a..z:
#	categories.append(alpha)

# loop over the filesystem
for filename in os.listdir('/home/chall/Music'):
	if path.isdir(filename):
		print [filename.replace('_', '').replace('(', '').strip()[:1].upper(), filename]
