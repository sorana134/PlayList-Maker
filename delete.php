<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = "identifier.sqlite";
    $conn = new SQLite3($database);

    if (!$conn) {
        die("Connection failed");
    }

    if (isset($_POST['selectedSongs']) && is_array($_POST['selectedSongs'])) {
        // Fetch the currently playing song from the playlist table
        $nowPlayingQuery = "SELECT * FROM playlist JOIN songs ON playlist.song_id = songs.song_id LIMIT 1";
        $nowPlayingResult = $conn->query($nowPlayingQuery);

        if ($nowPlayingResult) {
            $nowPlayingRow = $nowPlayingResult->fetchArray(SQLITE3_ASSOC);

            // Extract 'song_id' from the currently playing song
            $songIdToRemove = $nowPlayingRow['song_id'];

            // Construct the SQL query to delete the song from the playlist
            foreach ($_POST['selectedSongs'] as $selectedSongId) {
                if ($selectedSongId == $songIdToRemove) {
                    $sql = "DELETE FROM playlist WHERE song_id = :songId";
                    // Prepare the statement
                    $stmt = $conn->prepare($sql);
                    // Bind the parameter
                    $stmt->bindParam(':songId', $songIdToRemove, SQLITE3_INTEGER);

                    // Execute the statement
                    $result = $stmt->execute();
                }
            }

            // Fetch the updated playlist
            $playlistQuery = "SELECT * FROM playlist JOIN songs ON playlist.song_id = songs.song_id";
            $playlistResult = $conn->query($playlistQuery);

            // Check if the deletion was successful
            if ($playlistResult) {
                echo "<table>";
                echo "<tr><th>Playlist</th></tr>";

                while ($playlistRow = $playlistResult->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$playlistRow['song_name']} - {$playlistRow['artist']} {$playlistRow['length']}</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "Error fetching updated playlist: " . $conn->lastErrorMsg();
            }
        } else {
            echo "Error fetching Now Playing information.";
        }
    } else {
        echo "No songs selected!";
    }

    // Close the database connection
    $conn->close();
}
?>
