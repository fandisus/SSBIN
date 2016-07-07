var dmsString = function(o) {//S 01°56'06.30"
  return o.pole + ' ' + o.deg + '°' + o.min + "'" + o.sec + '"';
};
var degDms = function(val) {
  var n = Math.abs(val);
  var deg = parseInt(n); n=(n-deg)*60;
  var min = parseInt(n); n=(n-min)*60;
  var sec = Math.round(n * 100)/100;
  return {deg:deg, min:min, sec:sec};
};
var dmsDeg = function(o,type) {
  var maxDeg = (type === 'lat') ? 90 : 180;
  if (o.deg >= maxDeg) return null;
  if (o.min >= 60) return null;
  if (o.sec >= 60) return null;
  if (o.deg<0 || o.min<0 || o.sec<0) return null;
  var res = o.deg + o.min/60 + o.sec/3600;
  if (o.pole === "S" || o.pole === "W") res *= -1;
  return Math.round(res * 1000000) / 1000000;
};
var dmsDegStr = function(str, type) {
  if (str === '') return null;
  var patt = (type === 'lat') ? /^([NS])\s(\d{1,3})°(\d{1,2})'(\d{1,2}\.*\d*)"$/ : /^([WE])\s(\d{1,3})°(\d{1,2})'(\d{1,2}\.*\d*)"$/;
  var match = str.match(patt);
  if (match === null) { return null; }
  var o = {pole:match[1],deg:parseInt(match[2]),min:parseInt(match[3]),sec:parseFloat(match[4])};
  return dmsDeg(o,type);
};
