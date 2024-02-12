
<?php
$database = "identifier.sqlite";

// Create SQLite connection
$conn = new SQLite3($database);

// Check connection
if (!$conn) {
    die("Connection failed");
}

// Read JSON file
$jsonFile = 'songs.json'; // Replace with your actual JSON file path
$jsonData = file_get_contents($jsonFile);
$dataArray = json_decode($jsonData, true);

// Iterate through data and insert into the database
foreach ($dataArray as $record) {
    // Assuming your JSON structure has keys like 'artist', 'song_name', 'length', 'genre'
    $artist = $record['artist'];
    $songName = $record['song_name'];
    $length = $record['length'];
    $genre = $record['genre'];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO songs (artist, song_name, length, genre) VALUES ('$artist', '$songName', '$length', '$genre')";

    if ($conn->query($sql) === false) {
        echo "Error inserting record: " . $conn->error;
    }
}

 Close connection
$conn->close();


echo "</table>";
?>

