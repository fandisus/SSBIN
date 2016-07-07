/**
 * Prequisite:degdms.js
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
        s.deg = dmsDegStr(s.dms,s.public.type);
      };
      if (s.deg !== undefined && s.deg !== null) s.public.degChanged();
      if (s.dms !== undefined && s.dms !== null) s.public.dmsChanged();
      if (s.deg === undefined) {s.deg = null; s.public.degChanged();}
    }
  };
});