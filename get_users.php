<!---buat nampilin data di userdatahtml-->

<?php
include 'config.php';

header('Content-Type: application/json');

$query = "SELECT userID, name, username, npm, phone FROM signup";
$result = mysqli_query($conn, $query);

$users = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

echo json_encode($users);
mysqli_close($conn);
?>
