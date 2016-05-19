$.notify.defaults({autoHideDelay:5000,className:"success", position:"top center"});
var tr = function() {};
//Calon tambahan: notifbar: http://red-team-design.com/cool-notification-messages-with-css3-jquery/
$(document).ready(function() {
  var gaya = document.createElement('style');
  gaya.innerHTML =  "#loadScreen {position:fixed;top:0;left:0; background-color:rgba(255,255,255,.7); display:none;width:100%;height:100%; text-align:center;vertical-align:middle;z-index:1100;}";
  gaya.innerHTML += "#loadScreen div {margin:auto;margin-top:150px;}";
  gaya.innerHTML += "#loadScreen progress {width:80%; max-width:500px;}";
  document.head.appendChild(gaya);
  //http://stackoverflow.com/questions/3097644/can-code-in-a-javascript-file-know-its-own-domain-url
  var path = $('script[src$="myloading.js"]').attr('src');
  var mydir= path.split('/').slice(0, -1).join('/')+'/';  // remove last filename part of path
  $("body").append("<div id='loadScreen'><div><img src='"+mydir+"loading.gif'/></div><progress value='100' max='100'></progress></div>");
});
tr.loading = function(Show) {
  if (Show) $("#loadScreen").fadeIn();
  else $("#loadScreen").fadeOut();
};
tr.handleResponse = function(reply,successcb,errorcb) {
  try {
    reply = JSON.parse(reply);
    if (reply.result === "error") { $.notify(reply.message, "error"); typeof errorcb === 'function' && errorcb(reply); }
    else if (reply.result === "success") { successcb(reply); }
    else if (reply.result === "debug") { console.log(reply.data); }
  } catch (e) {
    console.log(reply);
    console.log(e);
    $.notify("Unknown Error. Check console log (F12) for details", "error");
  }
};
tr.post = function(uri,oPost, successcb,errorcb) {
  tr.loading(true);
  $.post(uri, oPost, function(reply) {
    tr.handleResponse(reply,successcb,errorcb);
  }).always(function() { tr.loading(false); });
};

//modifikasi dari http://stackoverflow.com/questions/166221/how-can-i-upload-files-asynchronously-with-jquery
tr.postForm = function(uri,form,successcb,errorcb) { //Form is a DOM object e.g: $("#formLoadFCA")[0]
  var formData = new FormData(form);
  tr.loading(true);
  $.ajax({
    url:uri, type:'POST',data:formData,
    error: function(j, stat, error) { console.log(j); console.log(stat); console.log(error); },
    success: function(reply) {
      tr.handleResponse(reply,successcb,errorcb);
    },
    xhr: function() {  // Custom XMLHttpRequest
      var myXhr = $.ajaxSettings.xhr();
      if(myXhr.upload){ // Check if upload property exists
          myXhr.upload.addEventListener('progress',showProgress, false);
          // For handling the progress of the upload. loadingcb is a function(e). See example below
      }
      return myXhr;
    },
    cache:false, contentType:false, processData:false
  }).always(function() { tr.loading(false); });
};
var showProgress = function(e) {
  if (e.lengthComputable){
    $("#loadScreen progress").attr("value",parseInt(e.loaded/e.total * 100));
  }
};
//var loadingcbExample = function(e) {
//  if(e.lengthComputable){
//    $scope.uploadPercentage = parseInt(e.loaded/e.total * 100);
//    $scope.$apply();
//  };
//};

var Example = (function() {
  var that = {};
  that.show = function(text,alertClass, duration) {
    if (alertClass === "danger") alertClass = "error";
    $.notify(text,alertClass);
  };
  return that;
}());
