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
    <title>Gallery Cafe | Reservations</title>
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
                    <h3 class="text-primary">Reservations</h3>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Reservations</h4>
                                <h6 class="card-subtitle">List of all reservations</h6>
                                <div class="table-responsive m-t-40">
                                    <table id="reservationTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Reservation ID</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Number of People</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Customer Name</th> 
                                                <th>Contact Number</th> 
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Reservation ID</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Number of People</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Customer Name</th> 
                                                <th>Contact Number</th> 
                                                <th>Action</th> 
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            // Updated query to fetch reservation details along with customer details
                                            $sql = "
                                                SELECT 
                                                    r.reservation_id, 
                                                    r.reservation_date, 
                                                    r.reservation_time, 
                                                    r.number_of_people, 
                                                    r.message, 
                                                    r.status, 
                                                    r.created_at, 
                                                    r.updated_at,
                                                    c.full_name AS customer_name, 
                                                    c.contact_number
                                                FROM 
                                                    table_reservations r
                                                JOIN
                                                    customers c ON r.customer_id = c.customer_id
                                            ";
                                            $query = mysqli_query($db, $sql);

                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="11"><center>No Reservations Data Found</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $status = ($rows['status'] == 'Pending')
                                                        ? '<span class="badge badge-warning">Pending</span>'
                                                        : ($rows['status'] == 'Confirmed'
                                                            ? '<span class="badge badge-info">Confirmed</span>'
                                                            : '<span class="badge badge-danger">Canceled</span>');

                                                    $action_buttons = ($rows['status'] == 'Pending')
                                                        ? '<a href="update_reservation.php?id=' . $rows['reservation_id'] . '&status=Confirmed" class="btn btn-success btn-sm">Confirm</a> 
                                                           <a href="update_reservation.php?id=' . $rows['reservation_id'] . '&status=Canceled" class="btn btn-danger btn-sm">Cancel</a>'
                                                        : '';

                                                    echo '<tr>
                                                            <td>' . htmlspecialchars($rows['reservation_id']) . '</td>
                                                            <td>' . htmlspecialchars($rows['reservation_date']) . '</td>
                                                            <td>' . htmlspecialchars($rows['reservation_time']) . '</td>
                                                            <td>' . htmlspecialchars($rows['number_of_people']) . '</td>
                                                            <td>' . htmlspecialchars($rows['message']) . '</td>
                                                            <td>' . $status . '</td>
                                                            <td>' . htmlspecialchars($rows['created_at']) . '</td>
                                                            <td>' . htmlspecialchars($rows['updated_at']) . '</td>
                                                            <td>' . htmlspecialchars($rows['customer_name']) . '</td>
                                                            <td>' . htmlspecialchars($rows['contact_number']) . '</td>
                                                            <td>' . $action_buttons . '</td>
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
      $(document).ready(function() {
        $('#reservationTable').DataTable({
            "order": [
                [6, 'desc']  // Order by the Created At column in descending order
            ],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
      });
    </script>
</body>
</html>
