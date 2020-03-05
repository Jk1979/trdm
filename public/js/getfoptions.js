function getfilteroptions(category)
{
 $.ajax({
  url: '/ajax/getfilteroptions/' + category,
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert('get filter options error');},
  success:
  function (data)
  {   //// Доработать код ниже
	  $("#filterOptions").empty();
      if (data != 0)
      {
          $('#filterOptions').append( data );
      }
       
   }
 });
}