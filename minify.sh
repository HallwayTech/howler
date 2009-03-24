##### minify local js files #####
rm -rf js/*.min.js
for f in `ls js/ | grep \.js$`; do
	f_new="${f:0:${#f}-2}min.js"
	echo "Minifying ${f} to ${f_new}"
	java -jar ../yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar "js/${f}" > "js/$f_new"
done

##### minify local css files #####
rm -rf css/*.min.css
for f in `ls css/ | grep \.css$`; do
	f_new="${f:0:${#f}-2}min.css"
	echo "Minifying ${f} to ${f_new}"
	java -jar ../yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar "css/${f}" > "css/$f_new"
done

##### minify external libraries #####
#for f in `ls lib/ | grep \.js$`; do
#	echo "Minifying ${f} to ${f}"
#	java -jar ../yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar "lib/${f}" > "lib/${f}.tmp"
#	mv "lib/${f}.tmp" "lib/$f"
#done
