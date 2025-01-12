<!---buat nampilin data di formhtml-->

<?php
include 'config.php';

header('Content-Type: application/json');

$query = "SELECT i.*, u.name, u.npm FROM items i
          JOIN signup u ON i.userID = u.userID
          ORDER BY i.date DESC";
$result = mysqli_query($conn, $query);

$items = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
}

echo json_encode($items);
mysqli_close($conn);
?>