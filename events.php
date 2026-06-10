<?php
// Include the database connection
include('db_connection.php');

// Fetch all events from the database
$query = "SELECT Events.EventID, Events.EventName, Events.EventType, Events.EventDate, 
          Events.EventTime, Events.Description, Members.FirstName, Members.LastName 
          FROM Events 
          LEFT JOIN Members ON Events.CreatedBy = Members.MemberID";
$result = mysqli_query($conn, $query);

// Handle the event form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $eventName = $_POST['eventName'];
    $eventType = $_POST['eventType'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $description = $_POST['description'];
    $createdBy = 1; // Assume a logged-in user with ID 1 for simplicity

    if ($_POST['action'] == 'create') {
        // Create new event
        $checkQuery = "SELECT * FROM Events WHERE EventDate = '$eventDate' AND EventTime = '$eventTime'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $errorMsg = "An event is already scheduled for this date and time. Please choose another time.";
        } else {
            $insertQuery = "INSERT INTO Events (EventName, EventType, EventDate, EventTime, Description, CreatedBy)
                            VALUES ('$eventName', '$eventType', '$eventDate', '$eventTime', '$description', '$createdBy')";

            if (mysqli_query($conn, $insertQuery)) {
                header('Location: events.php');
                exit();
            } else {
                $errorMsg = "Error: " . mysqli_error($conn);
            }
        }
    } elseif ($_POST['action'] == 'update') {
        // Update event
        $eventID = $_POST['eventID'];
        $updateQuery = "UPDATE Events SET EventName = '$eventName', EventType = '$eventType', 
                        EventDate = '$eventDate', EventTime = '$eventTime', Description = '$description'
                        WHERE EventID = '$eventID'";

        if (mysqli_query($conn, $updateQuery)) {
            header('Location: events.php');
            exit();
        } else {
            $errorMsg = "Error: " . mysqli_error($conn);
        }
    }
}

// Handle event deletion
if (isset($_GET['deleteEvent'])) {
    $eventID = $_GET['deleteEvent'];
    $deleteQuery = "DELETE FROM Events WHERE EventID = '$eventID'";

    if (mysqli_query($conn, $deleteQuery)) {
        header('Location: events.php');
        exit();
    } else {
        $errorMsg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Scheduling - Church Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar style */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Church Management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white">Church Management</h3>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="index.php"><i class="fas fa-users"></i> Members</a>
        <a href="ministries.php"><i class="fas fa-church"></i> Ministries</a>
        <a href="donations.php"><i class="fas fa-donate"></i> Donations</a>
        <a href="receipts.php"><i class="fas fa-file-invoice"></i> Receipts</a>
        <a href="financial_reports.php"><i class="fas fa-chart-line"></i> Financial Reports</a>
        <a href="donation_categories.php"><i class="fas fa-tags"></i> Donation Categories</a>
        <a href="events.php" class="active"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="users.php"><i class="fas fa-calendar-alt"></i> Users</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Event Scheduling</h2>

        <!-- Error message if scheduling conflict occurs -->
        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <!-- Button to trigger Add Event Modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addEventModal">Add New Event</button>
        
        <!-- Event Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Event Type</th>
                    <th>Event Date</th>
                    <th>Event Time</th>
                    <th>Description</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['EventID']; ?></td>
                    <td><?php echo $row['EventName']; ?></td>
                    <td><?php echo $row['EventType']; ?></td>
                    <td><?php echo $row['EventDate']; ?></td>
                    <td><?php echo $row['EventTime']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editEventModal" data-id="<?php echo $row['EventID']; ?>" 
                        data-name="<?php echo $row['EventName']; ?>" data-type="<?php echo $row['EventType']; ?>" 
                        data-date="<?php echo $row['EventDate']; ?>" data-time="<?php echo $row['EventTime']; ?>" 
                        data-description="<?php echo $row['Description']; ?>">Edit</button>
                        
                        <a href="events.php?deleteEvent=<?php echo $row['EventID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="events.php" method="POST">
                        <div class="form-group">
                            <label for="eventName">Event Name</label>
                            <input type="text" class="form-control" id="eventName" name="eventName" required>
                        </div>
                        <div class="form-group">
                            <label for="eventType">Event Type</label>
                            <select class="form-control" id="eventType" name="eventType" required>
                                <option value="Wedding">Wedding</option>
                                <option value="Baptism">Baptism</option>
                                <option value="Service">Service</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="eventDate">Event Date</label>
                            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                        </div>
                        <div class="form-group">
                            <label for="eventTime">Event Time</label>
                            <input type="time" class="form-control" id="eventTime" name="eventTime" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="action" value="create" class="btn btn-primary">Add Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="events.php" method="POST">
                        <input type="hidden" id="editEventID" name="eventID">
                        <div class="form-group">
                            <label for="editEventName">Event Name</label>
                            <input type="text" class="form-control" id="editEventName" name="eventName" required>
                        </div>
                        <div class="form-group">
                            <label for="editEventType">Event Type</label>
                            <select class="form-control" id="editEventType" name="eventType" required>
                                <option value="Wedding">Wedding</option>
                                <option value="Baptism">Baptism</option>
                                <option value="Service">Service</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editEventDate">Event Date</label>
                            <input type="date" class="form-control" id="editEventDate" name="eventDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editEventTime">Event Time</label>
                            <input type="time" class="form-control" id="editEventTime" name="eventTime" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="action" value="update" class="btn btn-primary">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, jQuery, and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- Populate Edit Modal with Event Data -->
    <script>
        $('#editEventModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var eventID = button.data('id');
            var eventName = button.data('name');
            var eventType = button.data('type');
            var eventDate = button.data('date');
            var eventTime = button.data('time');
            var description = button.data('description');
            
            // Update modal content
            var modal = $(this);
            modal.find('#editEventID').val(eventID);
            modal.find('#editEventName').val(eventName);
            modal.find('#editEventType').val(eventType);
            modal.find('#editEventDate').val(eventDate);
            modal.find('#editEventTime').val(eventTime);
            modal.find('#editDescription').val(description);
        });
    </script>
</body>
</html>
