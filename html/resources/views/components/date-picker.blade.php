<script type="text/javascript" src="{{ asset('assets/daterangepicker/daterangepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/daterangepicker/daterangepicker.css') }}" />
<script type="text/javascript">
    //DATES - START
    //On click "N" days on reporting page
    $(".dash-btn-group .btn").click(function() {
        var classes = $(this)
            .parent()
            .attr("class");

        if (classes.indexOf("ndays") != -1) {
            $(".dash-btn-group.ndays .btn").removeClass("active");
            $(this).addClass("active");
        }

        var checkID = $(this).attr("id");
        if (checkID != "custmDateGraph") {
            // $(".overlay-effect").toggleClass("show");
            $("#grid-selection").DataTable().ajax.reload();
        }


        var dateGrphChkId = $(".dash-btn-group.ndays .btn.active").attr("id");
    });

    function dateRange(start, end) {
        $(".dash-btn-group.one-for-all .btn").removeClass("active");
        $("#custmDateGraph").addClass("active");
        $("#grid-selection").DataTable().ajax.reload();
    }

    // for Custom Date Selection
    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();
    $('#custmDateGraph').daterangepicker({
        buttonClasses: ['btn', 'btn-md'],
        applyClass: 'btn-success',
        // "dateLimit": {
        //     "days": 120
        // },
        opens: 'left',
        // showDropdowns: true,
        startDate: moment(),
        endDate: moment(),
        maxDate: new Date(currentYear, currentMonth, currentDate),
        cancelClass: 'btn-danger'
    }, function(start, end, label) {
        start = start.format('MM/DD/Y');
        end = end.format('MM/DD/Y');
        //var oid_ccr = $("#oid_ccr").val();
        $("#startDate").val(start);
        $("#endDate").val(end);
        dateRange(start, end);

    });
    </script>