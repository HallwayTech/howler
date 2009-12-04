echo "chall/$1" | sha1sum 
sed 's/\,"type":"sound"//g' $1 | sed 's/\,"start":"0"//g' | sed 's/\,"_MODIFIERS":{}//g' | sed 's/%2F/\//g' | sed 's/%20/ /g' | sed 's/%26/\&/g' | sed 's/"id"/"file"/g' > temp
mv temp $1 
