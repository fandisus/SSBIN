printDataInfo = function(o) {
  if (o.data_info == undefined) return '';
  return "Created By: " + o.data_info.created_by +
          "<br />Created At: " + moment(o.data_info.created_at).calendar() + 
          "<br />Updated By: " + o.data_info.updated_by +
          "<br />Updated At: " + moment(o.data_info.updated_at).calendar();
};
printValidationInfo = function(o) {
  if (o.validation == undefined) return '';
  if (!o.validation.validated) return 'Not validated yet';
  return "Validated By: " + o.validation.validated_by +
          "<br />Validated At: " + moment(o.validation.validated_at).calendar();
};
