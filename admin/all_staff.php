<!DOCTYPE html>
<html lang="en">
<?php
require("auth.php");
checkLoggedIn($db);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Gallery Cafe | Staff</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <?php require('header.php'); ?>
        <?php require('sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Staff</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Staff Members</h4>
                                <h6 class="card-subtitle">List of all staff members</h6>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Staff ID</th>
                                                <th>Full Name</th>
                                                <th>Contact Number</th>
                                                <th>NIC</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Staff ID</th>
                                                <th>Full Name</th>
                                                <th>Contact Number</th>
                                                <th>NIC</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM staff";
                                            $query = mysqli_query($db, $sql);

                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="8"><center>No Staff Data Found</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $status = ($rows['is_active'] == 1)
                                                        ? '<span class="badge badge-info">Active</span>'
                                                        : '<span class="badge badge-danger">Inactive</span>';

                                                    echo '<tr>
                                                            <td>' . $rows['staff_id'] . '</td>
                                                            <td>' . $rows['full_name'] . '</td>
                                                            <td>' . $rows['contact_number'] . '</td>
                                                            <td>' . $rows['nic'] . '</td>
                                                            <td>' . $rows['gender'] . '</td>
                                                            <td>' . $rows['address'] . '</td>
                                                            <td>' . $status . '</td>
                                                            <td>
                                                                <a href="edit_staff.php?id=' . $rows['staff_id'] . '" class="btn btn-info btn-sm">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                                <button onclick="toggleStatus(' . $rows['staff_id'] . ', \'' . $rows['is_active'] . '\')" class="btn btn-danger btn-sm">
                                                                    <i class="fa fa-exchange"></i>
                                                                </button>
                                                            </td>
                                                        </tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
    <script>
    function toggleStatus(staffId, currentStatus) {
    $.ajax({
        url: 'change_staff_status.php',
        type: 'GET',
        data: {
            id: staffId,
            status: currentStatus
        },
        success: function(response) {
            try {
                // Parse the response as JSON
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                if (result.success) {
                    location.reload(); // Reload the page on successful status change
                } else {
                    console.error(result.message || 'Failed to update status');
                }
            } catch (e) {
                console.error('Failed to parse JSON response', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ', status, error);
            location.reload(); // Reload the page on error as well
        }
    });
}


    </script>
</body>

</html>
