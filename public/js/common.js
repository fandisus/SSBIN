printDataInfo = function(o) {
  if (o.data_info == undefined) return '';
  return "Created By: " + o.data_info.created_by +
          "<br />Created At: " + moment(o.data_info.created_at).calendar() + 
          "<br />Updated By: " + o.data_info.updated_by +
          "<br />Updated At: " + moment(o.data_info.updated_at).calendar();
};
