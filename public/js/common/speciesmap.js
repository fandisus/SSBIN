var init = JSON.parse($('#init').html());
var res = init.res;
map = new GMaps({
  div: '#map',
  lat: init.lat,
  lng: init.long,
  zoom:10
});
var showDetails =function(i){
  $('#modalDetails').modal('show');
  $('#details-panel').html(res[i].info);
};
for (var i in res) {
  (function(i) {
    map.addMarker({
      lat: res[i].latitude,
      lng: res[i].longitude,
      title: res[i].count + ' data',
      click: function(e) { showDetails(i); }
    });
  })(i);
}
