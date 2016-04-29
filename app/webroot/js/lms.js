$(function() {
    $('#userAccountCreate').click(function (event) {
        event.preventDefault();

        var isValid = true;
        if ($('#username').val() == '') {
            $('#err_username').empty();
            $('#err_username').append('Username can not be empty.');
            isValid = false;
        }

        if (isValid) {
            // Check employe id is available.
            $.ajax({
                url: base_url + '/admins/isUsernameAvailable',
                type: "post",
                dataType: 'json',
                data: {
                    'username': $('#username').val()
                },
                success: function (data) {
                    if (data.result == 'success') {
                        $('#usersCreateNewUserForm').submit();
                    } else if (data.result == 'error') {
                        $('#err_username').empty();
                        if (data.error_username != '') {
                            $('#err_username').append(data.error_username);
                        }
                    } else {

                    }
                },
                error: function (response, status) {
                    alert('error' + response);
                }
            });
        }
    });

    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        showOn: "button",
        buttonImage: "/images/iconCalendar.gif",
        buttonImageOnly: true
    });

    $("#datepicker2").datepicker({
        dateFormat: "yy-mm-dd",
        showOn: "button",
        buttonImage: "/images/iconCalendar.gif",
        buttonImageOnly: true
    });

    $('#projectList').chosen({});

    $('#projectList').on('change', function (evt, params) {
        var projectSelectedArr = $('#projectList').val();
        $.ajax({
            url: base_url + 'users/getUsersByProjects',
            type: 'GET',
            dataType: 'json',
            data: {
                projectSelected: projectSelectedArr
            },
            success: function (response) {
                var projectSelectEle = $('#Work_from_homesEmployee');
                $(projectSelectEle).empty();

                var optionStr = '';
                if (response.length) {
                    optionStr = '<option value="all" selected="selected">All Employees</option>';

                } else {
                    optionStr = '<option value=0 selected="selected">No Employees</option>';
                }
                $(projectSelectEle).append(optionStr);

                $.each(response, function (index, value) {
                    optionStr = '<option value="'
//                            + value.User.EmpId + '" data-id="'
//                            + value.User.id + '" data-empid="'
//                            + value.User.EmpId + '">' 
//                            + value.User.EmpName + '</option>';
                    + value.User.EmpId + '">'         //added by chamith
                    + value.User.Surname_RestName + '</option>';
                    $(projectSelectEle).append(optionStr);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Could not get the employees list");
            }
        });
    });

    $('#wfhFromDateReset').click(function () {
        $("#datepicker").datepicker("setDate", null);
    });

    $('#wfhToDateReset').click(function () {
        $("#datepicker2").datepicker("setDate", null);
    });

    /**
     * /admins/leave_request
     */
    $('[id^="confirmLeveReject_"]').click(function (event) {
        event.preventDefault();
//        var linkHref = $(this).attr('href');
        var elementId = $(this).attr('id');
        var leaveRequestId = elementId.substring(elementId.indexOf('_') + 1, elementId.length);
        var elementRowId = '#leave-request_' + leaveRequestId;

        $('#leave-reject-form [name="leave-request-id"]').val(leaveRequestId);
        $('#leave-reject-form [name="name"]').val($(elementRowId + ' td:nth-child(1) a').text().trim());
        $('#leave-reject-form [name="leave-type"]').val($(elementRowId + ' td:nth-child(2)').text().trim());
        $('#leave-reject-form [name="leave-time"]').val($(elementRowId + ' td:nth-child(5)').text().trim());
        $('#leave-reject-form [name="from-date"]').val($(elementRowId + ' td:nth-child(3)').text().trim());
        $('#leave-reject-form [name="to-date"]').val($(elementRowId + ' td:nth-child(4)').text().trim());
        $('#leave-reject-form [name="comment"]').val($(elementRowId + ' td:nth-child(7)').text().trim());

        leaveRejectForm.dialog("open");
    });

    var leaveRejectForm = $('#leave-reject-form').dialog({
        resizable: false,
        autoOpen: false,
        height: 400,
        width: 554,
        modal: true,
        buttons: {
            "Reject": function () {
                leaveRejectConfirmDialog.dialog("open");
            },
            Cancel: function () {
                leaveRejectForm.dialog("close");
            }
        },
        close: function () {
            $('#leave-reject-form [name="leave-request-id"]').val('');
            $('#leave-reject-form [name="name"]').val('');
            $('#leave-reject-form [name="leave-type"]').val('');
            $('#leave-reject-form [name="leave-time"]').val('');
            $('#leave-reject-form [name="from-date"]').val('');
            $('#leave-reject-form [name="to-date"]').val('');
            $('#leave-reject-form [name="comment"]').val('');
            $('#leave-reject-form [name="reject-comment"]').val('');
        }
    });

    var leaveRejectConfirmDialog = $("#leave-reject-confirm-dialog").dialog({
        resizable: false,
        autoOpen: false,
        height: 168,
        modal: true,
        buttons: {
            "Yes": function () {
                $('#leave-reject-form form').submit();
            },
            "No": function () {
                $(this).dialog("close");
            }
        }
    });

    /**
     * /WorkFromHomes/wfhreport
     */
    var wfhCancelConfirmDialog = $("#wfh-cancel-confirm-dialog").dialog({
        resizable: false,
        autoOpen: false,
        height: 168,
        modal: true
    });

    $('[id^="cancelWfhRequest_"]').click(function (event) {
        event.preventDefault();

        var cancelWfhReuestHref = $(this).attr('href');

        wfhCancelConfirmDialog.dialog('option', 'buttons',
            [
                {
                    text: 'Yes',
                    click: function () {
                        window.open(cancelWfhReuestHref, '_self');
                    }
                },
                {
                    text: 'No',
                    click: function () {
                        wfhCancelConfirmDialog.dialog("close");
                    }
                }
            ]);
        wfhCancelConfirmDialog.dialog("open");
    });

    /**
     * /admins/wfh_request
     */
    var wfhRejectForm = $('#wfh-reject-form').dialog({
        resizable: false,
        autoOpen: false,
        height: 400,
        width: 554,
        modal: true,
        buttons: {
            "Reject": function () {
                wfhRejectConfirmDialog.dialog("open");
            },
            Cancel: function () {
                wfhRejectForm.dialog("close");
            }
        },
        close: function () {
            $('#wfh-reject-form [name="wfh-request-id"]').val('');
            $('#wfh-reject-form [name="name"]').val('');
            $('#wfh-reject-form [name="wfh-time"]').val('');
            $('#wfh-reject-form [name="from-date"]').val('');
            $('#wfh-reject-form [name="to-date"]').val('');
            $('#wfh-reject-form [name="comment"]').val('');
            $('#wfh-reject-form [name="reject-comment"]').val('');
        }
    });

    var wfhRejectConfirmDialog = $("#wfh-reject-confirm-dialog").dialog({
        resizable: false,
        autoOpen: false,
        height: 168,
        width: 365,
        modal: true,
        buttons: {
            "Yes": function () {
                $('#wfh-reject-form form').submit();
            },
            "No": function () {
                $(this).dialog("close");
            }
        }
    });

    $('[id^="confirmWfhReject_"]').click(function (event) {
        event.preventDefault();
        var requestId = $(this).data('wfhid');
        var elementRowId = '#wfh-request_' + requestId;

        $('#wfh-reject-form [name="wfh-request-id"]').val(requestId);
        $('#wfh-reject-form [name="name"]').val($(elementRowId + ' td:nth-child(1)').text().trim());
        $('#wfh-reject-form [name="wfh-time"]').val($(elementRowId + ' td:nth-child(4)').text().trim());
        $('#wfh-reject-form [name="from-date"]').val($(elementRowId + ' td:nth-child(2)').text().trim());
        $('#wfh-reject-form [name="to-date"]').val($(elementRowId + ' td:nth-child(3)').text().trim());
        $('#wfh-reject-form [name="comment"]').val($(elementRowId + ' td:nth-child(6)').text().trim());

        wfhRejectForm.dialog("open");
    });


    $('.report-lieu-edit').click(function (event) {
        var empid = $(this).data('empid');

        $('#liue-input_' + empid).prop("disabled", false);

        $('#liue-edit_' + empid).parent().toggleClass('leave-summary-liue-leave').toggleClass('hidden');
        $('#liue-cancel_' + empid).parent().toggleClass('leave-summary-liue-leave').toggleClass('hidden');
    });

    $('.report-lieu-cancel').click(function (event) {
        var empid = $(this).data('empid');
        var currentLeaves = $('#liue-input_' + empid).data('liue-leaves');

        $('#liue-input_' + empid).val(currentLeaves);
        $('#liue-input_' + empid).prop("disabled", true);

        $('#liue-edit_' + empid).parent().toggleClass('leave-summary-liue-leave').toggleClass('hidden');
        $('#liue-cancel_' + empid).parent().toggleClass('leave-summary-liue-leave').toggleClass('hidden');
    });

    $('.report-lieu-save').click(function (event) {
        var empid = $(this).data('empid');
        var newLeaves = $('#liue-input_' + empid).val();

        if ($.isNumeric(newLeaves) && newLeaves >= 0) {
            $.ajax({
                url: base_url + 'Reports/updateLeaveCount',
                type: 'GET',
                dataType: 'json',
                data: {
                    empid: empid,
                    leaveType: 'liue',
                    leaveCount: newLeaves
                },
                success: function (response) {
                    if (response.success === true) {
                        $('#liue-input_' + empid).data('liue-leaves', newLeaves);

                        var currentLeaves = $('#liue-input_' + empid).data('liue-leaves');

                        $('#liue-input_' + empid).val(currentLeaves);
                        $('#liue-input_' + empid).prop("disabled", true);

                        $('#liue-edit_' + empid).parent().toggleClass('leave-summary-liue-leave').toggleClass('hidden');
                        $('#liue-cancel_' + empid).parent().toggleClass('leave-summary-liue-leave').toggleClass('hidden');
                    } else {
                        alert(response.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Could not save leaves.");
                }
            });
        } else {
            alert('Please enter valid numeric value.');
        }

    });


    //$('.user-edit').each(function () {
    $('.user-edit').on('click', function() {
        //$(this).click(function (event) {
            var empid = $(this).data('empid');

            $('#user-role_' + empid).prop("disabled", false);
            $(this).parents("td").find('#userRole').prop("disabled", false);

            $('#user-edit_' + empid).parent().toggleClass('employee-role').toggleClass('hidden');
            $('#user-cancel_' + empid).parent().toggleClass('employee-role').toggleClass('hidden');


        //});
    });

    $('.user-cancel').click(function (event) {
        var empid = $(this).data('empid');

        $('#user-input_' + empid).prop("disabled", true);
        location.reload();
        $('#userRole').prop("disabled", true);

        $('#user-edit_' + empid).parent().toggleClass('employee-role').toggleClass('hidden');
        $('#user-cancel_' + empid).parent().toggleClass('employee-role').toggleClass('hidden');
    });

    $('.user-save').on('click', function () {
        var empId = $(this).data('empid');
        var selectedRole = $('#user-role_' + empId).val();

        $.ajax({
            url : base_url + 'EmployeesActions/updateUser',
            type: 'GET',
            dataType: 'json',
            data: {
                empid: empId,
                empRole: selectedRole
            },
            success : function(response) {
                if (response.success === true) {
                    $('#user-role_' + empId).prop("disabled", true);
                    $('#user-edit_'+empId).parent().toggleClass('employee-role').toggleClass('hidden');
                    $('#user-cancel_'+empId).parent().toggleClass('employee-role').toggleClass('hidden');
                } else {
                    alert(response.msg);
                }
            },
            error : function() {
                alert("Could not save data.");
            }
        });
    });

    $(".viewButton").on("click", function() {
        var tableid = $(this).data('tdid');
        $.ajax({
            url : base_url + 'Admins/deleteProject',
            type: 'GET',
            dataType: 'json',
            data: {
                tdid: tableid
            },
            success : function(response) {
                if (response.success === true) {


                } else {
                    alert(response.msg);
                }
            },
            error : function() {
                alert("Could not Delete data.");
            }
        });
        if (confirm("Are you sure?")) {
            $(this).parent().remove();
        }
        return false;

    });

    $('.user-delete').on('click', function() {
        var empid = $(this).data('deleteid');

        $.ajax({
            url : base_url + 'EmployeesActions/deleteEmployee',
            type: 'GET',
            dataType: 'json',
            data: {
                empid: empid
            },
            success : function(response) {
                if (response.success === true) {


                } else {
                    alert(response.msg);
                }
            },
            error : function() {
                alert("Could not Delete data.");
            }
        });
        if (confirm("Are you sure, You want to delete this User?")) {
            $(this).parent().remove();
        }
        return false;
    });

    $('.assignButton').on('click', function () {

        var projectIdId = $(this).data('empid');
        var EmpId = $('#user-role_' + empId).val();

        $.ajax({
            url : base_url + 'EmployeesActions/updateUser',
            type: 'GET',
            dataType: 'json',
            data: {
                empid: empId,
                empRole: selectedRole
            },
            success : function(response) {
                if (response.success === true) {
                    $('#user-role_' + empId).prop("disabled", true);
                    $('#user-edit_'+empId).parent().toggleClass('employee-role').toggleClass('hidden');
                    $('#user-cancel_'+empId).parent().toggleClass('employee-role').toggleClass('hidden');
                } else {
                    alert(response.msg);
                }
            },
            error : function() {
                alert("Could not save data.");
            }
        });
    });

    $('.addButton').on('click', function () {

        var projectId = $('#projectList').val();
        var EmpId = $('#employeeNames').val();

        $.ajax({
            url : base_url + 'Admins/addProject',
            type: 'GET',
            dataType: 'json',
            data: {
                proid: projectId,
                empid: EmpId
            },
            success : function(response) {
                if (response.success === true) {
                    //alert(response.msg);
                    $("#content").prepend("<div id='flashMessage' class='message'>Successfully assigned employees to the project.</div>");
                } else {
                    $("#content").prepend("<div id='flashMessage' class='message'>This employee is already assigned to this project</div>");
                }
            },
            error : function() {
                alert("Could not save data.");
            }
        });

    });
   /* $(document).on('click', '.addButton', function(){
        alert('hi');
    });*/
});
   // End : $(function() {
