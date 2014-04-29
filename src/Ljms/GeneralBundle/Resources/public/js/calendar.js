$(document).ready(function() {
//request to the server about games for the selected month and year
    function requestData (month, year){
            $.ajax({
                    async: false,
                    type: "GET",
                    dataType: "json",
                    url: base_url+"calendar/get_dates",
                    data: "year="+year+"&month="+month, 
                    success: function (msg){ undisabledDays=msg; }
            });
    }
//off days without games
    function disableAllTheseDays(date) {
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [$.inArray(string, undisabledDays) !=-1];
      }
//determines the year and month currently
    function loadCurrentPosts() {
        var curDate = new Date();
        var month = curDate.getMonth();
            month++;
        var year = curDate.getFullYear();
            requestData(month, year);
    }
//year and month when scrolling calendar
    function loadIdPost(year, month){ requestData(month, year); }     
           
    $('#datepickerevent').datepicker({
        beforeShow: loadCurrentPosts(),
        dateFormat: 'yy-mm-dd',
        beforeShowDay:  disableAllTheseDays, 
        onChangeMonthYear:  function(year, month, inst) {
                                    loadIdPost(year, month)},
    //reference when choosing a date
        onSelect: function(date)
                        {
                        var links= base_url+"calendar?dates=";
                        window.location.href = links+date;
                        }
          
    }); 
}); 