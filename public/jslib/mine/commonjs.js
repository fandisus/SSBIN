//c:comma, d:decimal separator, t:thousand separator
//Courtesy of: http://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-money-in-javascript
Number.prototype.formatMoney = function(comma, dec, thousand){ 
var n = this, 
    comma = isNaN(comma = Math.abs(comma)) ? 2 : comma, 
    dec = dec == undefined ? "," : dec, 
    thousand = thousand == undefined ? "." : thousand, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(comma)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (comma ? dec + Math.abs(n - i).toFixed(comma).slice(2) : "");
};

Array.prototype.remove = function(element) {
  var a = this;
  a.splice(a.indexOf(element),1);
};

//Courtesy of: http://stackoverflow.com/questions/600763/check-if-a-variable-contains-a-numerical-value-in-javascript/601877#601877
function isNumber(n){
    return isFinite(String(n).trim() || NaN);
}

//filetype: js or css.
function loadjscssfile(filename, filetype){
 if (filetype=="js") { //if filename is a external JavaScript file
  var fileref=document.createElement('script')
  fileref.setAttribute("type","text/javascript")
  fileref.setAttribute("src", filename)
 } else if (filetype=="css") { //if filename is an external CSS file
  var fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
 }
 if (typeof fileref!="undefined") document.getElementsByTagName("head")[0].appendChild(fileref)
}
