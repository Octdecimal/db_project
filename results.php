<?php
include 'db.php';

// Get user input
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$table = isset($_GET['table']) ? $_GET['table'] : 'location_info'; // Default to 'location_info' if 'table' is not set

// Query location_info table
$sql_location = "SELECT * FROM location_info WHERE address LIKE '%$search_term%' OR description LIKE '%$search_term%' OR location_name LIKE '%$search_term%' OR opening_time LIKE '%$search_term%' OR closing_time LIKE '%$search_term%'";
$result_location = $conn->query($sql_location);

// Check for errors in location_info query
if ($conn->error) {
    die("Location query failed: " . $conn->error);
}

$search_term2 = isset($_GET['search']) ? $_GET['search'] : '';
$table = isset($_GET['table']) ? $_GET['table'] : 'trail'; 
// Prepare trail SQL query
$sql_trail = "SELECT trail.tr_cname, trail.trailid, city.city, district.district, trail.tr_dif_class, trail.tr_length, trail.tr_tour 
              FROM trail 
              LEFT JOIN city 
              ON trail.city_id = city.city_id 
              LEFT JOIN district 
              ON trail.district_id = district.district_id 
              WHERE 
              (trail.tr_cname LIKE '%$search_term2%') OR 
              (city.city LIKE '%$search_term2%') OR 
              (district.district LIKE '%$search_term2%');";
$stmt = $conn->prepare($sql_trail);
// $stmt->bind_param('sss', $search_term2, $search_term2, $search_term2);
$stmt->execute();
$result_trail = $stmt->get_result();

// Check for errors in trail query
if ($conn->error) {
    die("Trail query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查詢結果</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>查詢結果</h1>
        <form method="GET" action="results.php">
            <label for="search">輸入景點關鍵字:</label>
            <input type="text" id="search" name="search" placeholder="輸入景點名稱或描述">
            <input type="submit" value="查詢">
        </form>
        <button onclick="window.location.href='index.php'">返回首頁</button>
    </header>
    <main>
        <form method="GET" action="results.php">
            <label for="table">選擇要搜尋的表:</label>
            <select name="table" id="table" onchange="this.form.submit();">
                <option value="location_info" <?php if ($table === 'location_info') echo 'selected'; ?>>Location Info</option>
                <option value="trail" <?php if ($table === 'trail') echo 'selected'; ?>>Trail</option>
            </select>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_term, ENT_QUOTES); ?>" style="display: none;">
            <input type="submit" value="搜尋">
        </form>

        <?php
        if ($table === 'location_info') {
            if ($result_location->num_rows > 0) {
                echo "<ul>";

                while ($row = $result_location->fetch_assoc()) {
                    echo "<li>";
                    echo "<a href='details.php?id={$row['id']}'>{$row['location_name']}</a>";
                    echo "      營業時間 - {$row['opening_time']} ~ {$row['closing_time']}";
                    echo " - {$row['address']}";
                    echo "</li>";
                    echo "<br>";
                }

                echo "</ul>";
            } else {
                echo "沒有找到相關景點。";
            }
        } elseif ($table === 'trail') {
            if ($result_trail->num_rows > 0) {
                echo "<ul>";

                while ($row = $result_trail->fetch_assoc()) {
                    echo "<li>";
                    echo "<a href='detailstrail.php?id={$row['trailid']}'>{$row['tr_cname']} ({$row['city']}, {$row['district']})</a>";
                    echo "</li>";
                }

                echo "</ul>";
            } else {
                echo "沒有找到相關步道。";
            }
        }

        $stmt->close();
        $conn->close();
        ?>
    </main>
</body>
</html>