$(document).ready(function() {

    $('input[name="daterange"]').daterangepicker({
        autoApply: true,
        autoUpdateInput: true
            //maxDate: moment().format('DD/MM/YYYY'),
    });

    var offset = 15;
    var offset2 = 15;
    var dateStart = [],
        dateEnd = [];
    var resetChecker = 0;
    var activityInputChanged = 0;
    var activitySelectChanged = 0;
    dateStart[0] = '';
    dateEnd[0] = '';

    $('#box').scroll(function() {
        if ($('#box').scrollTop() == $("#box")[0].scrollHeight - 400) {
            loadTable();
        }
    });

    $('#activityBox').scroll(function() {
        if ($('#activityBox').scrollTop() == $("#activityBox")[0].scrollHeight - 400) {
            showMoreLog();
        }
    });

    $('#printOrderSelect').on('change', function() {
        loadTable();
    });

    $("input[name='batch']").on('change keydown paste input', function() {
        loadTable();
    });

    $('#activityLogSelect').on('change', function() {
        activitySelectChanged = 1;
        searchActivity();
    });

    $("input[name='activityUserName']").on('change paste input', function() {
        activityInputChanged = 1;
        searchActivity();
    });

    $("input[name='daterange']").on('apply.daterangepicker', function(ev, picker) {
        var startDate = jps_makeTimestamp(picker.startDate.format('MM/DD/YYYY'));
        var endDate = jps_makeTimestamp(picker.endDate.format('MM/DD/YYYY'));
        dateStart = startDate.split(" ");
        dateEnd = endDate.split(" ");
        loadTable();
    });

    $("input[name='phone_number']").keypress(function(e) {
        if (e.which == 13) {
            $('#login_div').css("display", "none");
            $('#verify_div').css("display", "block");
            login_process();
        }
    });

    $("input[name='verification_code']").keypress(function(e) {
        if (e.which == 13) {
            verify_process();
        }
    });

    $('#login_button').on('click', function() {
        $('#login_div').css("display", "none");
        $('#verify_div').css("display", "block");
        login_process();
    });

    $('#verify_button').on('click', function() {
        verify_process();
    });

    function login_process() {
        var phone_number = $("input[name='phone_number']").val();
        // var token = $("meta[name='csrf-token2']").attr('content');
        // console.log(phone_number, token);
        $.post("/verifyLogin", { phone_number: phone_number, _token: $('meta[name="csrf-token2"]').attr('content') })
            .done(function(data) {
                var a = data;
                if (a != 0) {
                    $("#userid").val(a);
                } else {
                    $('#error_msg').html('Invalid login. Please check the phone number.');
                    $('#verify_div').css("display", "none");
                    $('#login_div').css("display", "block");
                }
            })
            .fail(function(xhr) {
                console.log("/verifyLogin failed", xhr.status, xhr.responseText);
                $('#error_msg').html('Login request failed. Please try again.');
                $('#verify_div').css("display", "none");
                $('#login_div').css("display", "block");
            });
        // .fail(function(e) {
        //     console.log("Have some error", e.responseText);
        // });
    }

    function verify_process() {
        var userid = $("input[name='id']").val();
        var verification_code = $("input[name='verification_code']").val();
        $.post("/confirmLogin", { id: userid, verification_code: verification_code, _token: $('meta[name="csrf-token_verify"]').attr('content') })
            .done(function(data) {
                var a = data;
                if (a != 0) {
                    if (a == 1) window.location.href = 'choosemenu';
                    else if (a == 2) window.location.href = 'code/generate';
                } else {
                    $('#checkMessage').html('The code entered was incorrect. Please try again.');
                }
            })
            .fail(function(xhr) {
                console.log("/confirmLogin failed", xhr.status, xhr.responseText);
                $('#checkMessage').html('Verification failed due to a server error. Please retry.');
            });
    }
    /*
     $('#generate_button').on('click',function(){
     var company_id = $("input[name='company_id']").attr('value');
     var mfg_date = $("input[name='mfg_date']").attr('value');
     var expiry_date = $("input[name='expiry_date']").attr('value');
     var quantity = $("input[name='quantity']").attr('value');
     var file = $("input[name='file']").attr('value');
     var batch_number = $("input[name='batch_number']").attr('value');
     var medicine_dosage_id = $("input[name='medicine_dosage_id']").attr('value');
     var token = $('meta[name="csrf-token_confirm"]').attr('content');

     setInterval(function(){
     $.get('/progress', function(data) {
     //console.log("each"+data);
     });
     }, 1000);

     $.post("/code/confirm", {company_id: company_id,
     mfg_date: mfg_date,
     expiry_date: expiry_date,
     quantity: quantity,
     file: file,
     batch_number: batch_number,
     medicine_dosage_id: medicine_dosage_id,
     token: token
     },
     function (data) {
     //console.log(data);
     });

     //return false;

     });
     */

    $('#reset_button').on('click', function() {
        resetChecker = 1;
        $(".search-log").find('input:text').val('');
        $(".search-log").find("input[name='daterange']").val('Date');
        $("#printOrderSelect").val('').selectpicker('refresh');
        loadTable();
    });

    $('#activityReset').on('click', function() {
        location.reload();
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm',
        autoclose: true,
        startView: "months",
        minViewMode: "months",
        viewMode: "months"
    });

    $('#mfg_date').datepicker().on("change", function() {
        var d = $('#mfg_date').datepicker('getDate');
        d.setFullYear(d.getFullYear(), d.getMonth() + 23);
        $('#expiry_date').datepicker('setDate', d);
    });

    $("#qr").hide();

    $("#medicine_id").find("input[name='medicine_name']").change(function() {
        var id = $('input[name="medicine_name"]:checked').attr('value');
        // console.log(id);
        if (id == 'Rolac') {
            $("#medicine_type_id").html(
                '<label class="btn btn-primary active">' +
                '<input type="radio" value="Tablet" name="med_type" checked="checked" required> Tablet' +
                '</label>'
            );
            $("#medicine_type_id").show();
            $("#medicine_dosage_id").html(
                '<label class="btn btn-primary active">' +
                '<input type="radio" value="1" name="medicine_dosage" checked="checked" required> 10mg ' +
                '</label>'
            );
            $("#medicine_dosage_id").show();
            $("#qr").hide();
            $("#pregroup").show();
        } else if (id == 'Maxpro Mups') {
            $("#medicine_type_id").html(
                '<label class="btn btn-primary active">' +
                '<input type="radio" value="Tablet" name="med_type" checked="checked" required> Tablet' +
                '</label>'
            );
            $("#medicine_type_id").show();
            $("#medicine_dosage_id").html(
                '<label class="btn btn-primary active">' +
                '<input type="radio" value="13" name="medicine_dosage" checked="checked" required> 20mg ' +
                '</label>'
            );
            $("#medicine_dosage_id").show();
            $("#pregroup").hide();
            $("#qr").show();

        } else {
            $("#qr").hide();
            $("#pregroup").show();
            $.post("/generationPanel/medicineType", { id: id }, function(data) {
                var a = data;
                $("#medicine_type_id").html(a);
                $("#medicine_type_id").show();
                $("#medicine_dosage_id").hide();
                newFunc();
            });
        }
    });

    function loadTable() {

        var company_id = $("input[name='company_id']").attr('value');
        var page_id = $("input[name='pagename']").attr('value');
        var batch = $("input[name='batch']").val();
        var selected = $('#printOrderSelect').selectpicker().val();
        if (selected == '' || selected == null) selected = [];

        if (resetChecker == 1) {
            batch = '';
            selected = [];
            dateStart[0] = '';
            dateEnd[0] = '';
            offset = 15;
        }

        setTimeout(function() {
            if (page_id == 'log_page') {
                $.post("/generationPanel/loadLog", { offset: offset, companyId: company_id }, function(data) {
                    var a = data;
                    $('.inner_table').append(a);
                });
            } else if (page_id == 'template_page') {} else {
                $.post("/generationPanel/loadMore", {
                    offset: offset,
                    companyId: company_id,
                    batch: batch,
                    selected: selected,
                    dateStart: dateStart[0],
                    dateEnd: dateEnd[0]
                }, function(data) {
                    var a = data;
                    console.log(dateStart[0] + " " + dateEnd[0]);
                    if (batch != "" || selected.length > 0 || dateStart[0] != '' || dateEnd[0] != '') {
                        resetChecker = 0;
                        $('.inner_table').html(a);
                    } else $('.inner_table').append(a);
                });
            }
            offset += 15;
            $('#box').scrollTop($('#box')[0].scrollHeight);
        }, 1);
    }

    function searchActivity() {
        var selected = $('#activityLogSelect').selectpicker().val();
        var page_id = $("input[name='pagename']").attr('value');
        var company_id = $("input[name='company_id']").attr('value');
        var userName = $("input[name='activityUserName']").val();

        if (selected == '' || selected == null) selected = [];

        setTimeout(function() {
            if (page_id == 'log_page') {
                //console.log('seleced');
                $.post("/generationPanel/searchActivityLog", {
                        offset: offset2,
                        companyId: company_id,
                        selected: selected,
                        nameInput: userName
                    },
                    function(data) {
                        var a = data;
                        //console.log(a);
                        $('.activity_inner_table').html(a);
                    });
            }
            offset2 += 15;
            $('#activityBox').scrollTop($('#activityBox').scrollHeight - 400);
        }, 1);
    }

    function showMoreLog() {
        var page_id = $("input[name='pagename']").attr('value');
        var company_id = $("input[name='company_id']").attr('value');

        if (activityInputChanged == 0 && activitySelectChanged == 0) {
            setTimeout(function() {
                if (page_id == 'log_page') {
                    $.post("/generationPanel/loadLog", {
                            offset: offset2,
                            companyId: company_id
                        },
                        function(data) {
                            var a = data;
                            $('.activity_inner_table').append(a);
                        });
                }
                offset2 += 15;
            }, 1);
        }
    }

    function newFunc() {
        $("#medicine_type_id").find("input[name='med_type']").change(function() {
            var name = $('input[name="medicine_name"]:checked').attr('value');
            var type = $('input[name="med_type"]:checked').attr('value');

            $.post("/generationPanel/medicineDosage", { name: name, type: type }, function(data) {
                var b = data;
                $("#medicine_dosage_id").html(b);
                $("#medicine_dosage_id").show();
                newFunc2();
            });
        });
    }

    function newFunc2() {
        $("#medicine_dosage_id").find("input[name='medicine_dosage']").change(function() {});
    }

    function autoSelectRolacAfterComingBack() {
        var id = $('input[name="medicine_name"]:checked').attr('value');
        if (id == 'Rolac') {
            $("#medicine_type_id").html(
                '<label class="btn btn-primary active">' +
                '<input type="radio" value="Tablet" name="med_type" checked="checked" required> Tablet' +
                '</label>'
            );
            $("#medicine_type_id").show();
            $("#medicine_dosage_id").html(
                '<label class="btn btn-primary active">' +
                '<input type="radio" value="1" name="medicine_dosage" checked="checked" required> 10mg ' +
                '</label>'
            );
            $("#medicine_dosage_id").show();
        }
    }

    function jps_makeTimestamp(data) {
        var date = new Date(data);
        var yyyy = date.getFullYear();
        var mm = date.getMonth() + 1;
        var dd = date.getDate();
        var hh = date.getHours();
        var min = date.getMinutes();
        var ss = date.getSeconds();

        var mysqlDateTime = yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + min + ':' + ss;

        return mysqlDateTime;
    }

    newFunc();
    newFunc2();
    autoSelectRolacAfterComingBack();
});