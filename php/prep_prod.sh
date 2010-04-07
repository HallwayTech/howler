#! /bin/bash
./minify.sh

echo 'Prepping index.html'
sed 's_js/\(.*\)\.js_js/\1\.min\.js_g' index_dev.html > index.html
sed 's_css/\(.*\)\.css_css/\1\.min\.css_g' index_dev.html > index.html
