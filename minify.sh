YUI_CMD="java -jar ../yuicompressor-2.4.2/build/yuicompressor-2.4.2.jar -v"

##### minify local js files #####
rm -rf js/*.min.js
for f in `ls js/ | grep \.js$`; do
	f_new="${f:0:${#f}-2}min.js"
	echo "Minifying ${f} to ${f_new}"
	$YUI_CMD "js/${f}" -o "js/$f_new"
done

##### minify local css files #####
rm -rf css/*.min.css
for f in `ls css/ | grep \.css$`; do
	f_new="${f:0:${#f}-3}min.css"
	echo "Minifying ${f} to ${f_new}"
	$YUI_CMD "css/${f}" -o "css/$f_new"
done
