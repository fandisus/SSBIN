/**
 * deg, dms: optional param, default: null
 * public: {type:'lat/long', mode:'deg/dms'}  '/' means: choose one
 */
app.directive("trDegdms", function () {
  var path = $('script[src$="DirDegdms.js"]').attr('src');
  var mydir = path.split('/').slice(0, -1).join('/') + '/';  // remove last filename part of path
  return {
    templateUrl: mydir + "DirDegdms.html",
    restrict: 'E',
    scope: {deg:'=', dms:'=', public:'='},
    
    link: function (s, e) {//scope,element
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
      var dmsDeg = function(o) {
        var maxDeg = (s.public.type === 'lat') ? 90 : 180;
        if (o.deg >= maxDeg) return null;
        if (o.min >= 60) return null;
        if (o.sec >= 60) return null;
        if (o.deg<0 || o.min<0 || o.sec<0) return null;
        var res = o.deg + o.min/60 + o.sec/3600;
        if (o.pole === "S" || o.pole === "W") res *= -1;
        return Math.round(res * 1000000) / 1000000;
      };
      s.changeMode = function() {
        s.public.mode = (s.public.mode === 'dms') ? 'deg' : 'dms';
      };
      s.public.degChanged = function() {//di onchange deg
        //alphanumeric error handler
        var o = {deg:0, min:0, sec:0, pole:"S"};
        if (!isNumber(s.deg)) {
          o.pole = (s.public.type === 'lat') ? 'S' : 'E';
          s.dms = dmsString(o);
          return;
        }
        //calculate
        if (s.public.type === 'lat') {
          o = degDms(s.deg);
          if (o.deg > 90) { s.dms = null; return; }
          o.pole = (s.deg < 0) ? 'S' : 'N';
          s.dms = dmsString(o);
        } else if (s.public.type === 'long') {
          o = degDms(s.deg);
          if (o.deg > 180) { s.dms = null; return; }
          o.pole = (s.deg < 0) ? 'W' : 'E';
          s.dms = dmsString(o);
        }
        else s.dms = null;
      };
      s.public.dmsChanged = function() { //di onchange dms
        if (s.dms === '') { s.deg = 0; return; }
        var patt = (s.public.type === 'lat') ? /^([NS])\s(\d{1,3})°(\d{1,2})'(\d{1,2}\.*\d*)"$/ : /^([WE])\s(\d{1,3})°(\d{1,2})'(\d{1,2}\.*\d*)"$/;
        var match = s.dms.match(patt);
        if (match === null) { s.deg = 0; return;}
        var o = {pole:match[1],deg:parseInt(match[2]),min:parseInt(match[3]),sec:parseFloat(match[4])};
        s.deg = dmsDeg(o);
      };
      if (s.deg !== undefined && s.deg !== null) s.public.degChanged();
      if (s.dms !== undefined && s.dms !== null) s.public.dmsChanged();
      if (s.deg === undefined) {s.deg = null; s.public.degChanged();}
    }
  };
});