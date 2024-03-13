<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My playlist</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<?php
// Assume your link table has columns 'song_id' and 'youtube_link'
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if selectedSongs array is set
    if (isset($_POST['selectedSongs']) && is_array($_POST['selectedSongs'])) {
        // Create SQLite connection
        $database = "identifier.sqlite";
        $conn = new SQLite3($database);

        // Check connection
        if (!$conn) {
            die("Connection failed");
        }

        // Fetch the currently playing song from the playlist table
        $nowPlayingQuery = "SELECT * FROM playlist JOIN songs ON playlist.song_id = songs.song_id LIMIT 1";

        $nowPlayingResult = $conn->query($nowPlayingQuery);

        if ($nowPlayingResult) {
            $nowPlayingRow = $nowPlayingResult->fetchArray(SQLITE3_ASSOC);

            // Display the currently playing song in the middle table
            echo "<table id='cursong'>";
            echo "<tr><td>{$nowPlayingRow['song_name']} - {$nowPlayingRow['artist']} {$nowPlayingRow['length']}</td></tr>";
            echo "</table>";

            // Play the audio locally with play/pause functionality
            echo "<audio id='audioPlayer' controls>";
            echo "<source src='music/{$nowPlayingRow['link']}' type='audio/mpeg'>";
            echo "Your browser does not support the audio element.";
            echo "</audio>";

            $conn->close();
        } else {
            echo "Error fetching Now Playing information.";
        }
    }
} else {
    echo "<table>";
    echo "<tr><th>Now Playing</th></tr>";
    echo "<tr><td>No songs selected</td></tr>";
    echo "</table>";
}
?>

<script>
    var audio = document.getElementById('audioPlayer');
    var playButton = document.getElementById('playButton');

    function togglePlayPause() {
        if (audio.paused) {
            audio.play();
        } else {
            audio.pause();
        }
    }
</script>

</body>
</html>
