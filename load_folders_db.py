import os
from os import path
import re

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
#		print [re.search('[a-zA-Z0-9]', filename)filename.replace('_', '').replace('(', '').strip()[:1].upper(), filename]
