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

<form id="songForm" method="post" ;">
    <?php
    $database = "identifier.sqlite";
    $conn = new SQLite3($database);

    if (!$conn) {
        die("Connection failed");
    }

    $sql = "SELECT * FROM songs";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error executing the query: " . $conn->lastErrorMsg());
    }

    echo "<table>";
    echo "<tr><th>Songs</th></tr>";

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td><input type='checkbox' name='selectedSongs[]' value='{$row['song_id']}'></td>";
        echo "<td>{$row['song_name']} - {$row['artist']} {$row['length']}</td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>

    <input type="button" value="Play Selected Songs" onclick="processSelection()">
</form>

<table>
    <tr>
        <th>Now Playing</th>
    <tr><td id="nowPlaying">

        </td></tr>
    <tr>
        <td><</td>
     <td>   <button onclick="playCurrentSong()">Play</button>
        </td>
        <td>></td>
    </tr>
</table>

<table>
    <tr>
        <th>Playlist</th>
    <tr><td id="playlist">
            <?php
            $playlistQuery = "SELECT * FROM playlist JOIN songs ON playlist.song_id = songs.song_id";
            $playlistResult = $conn->query($playlistQuery);
            while ($playlistRow = $playlistResult->fetchArray(SQLITE3_ASSOC)) {
                echo "<tr>";
                echo "<td>{$playlistRow['song_name']} - {$playlistRow['artist']} {$playlistRow['length']}</td>";
                echo "</tr>";
            }
            ?>

        </td></tr>
    </tr>
</table>

<script>
    function processSelection() {
        $.ajax({
            type: 'POST',
            url: 'process_selection.php',
            data: $('#songForm').serialize(),
            success: function (data) {
                $('#playlist').html(data);
            },
            error: function () {
                alert('An error occurred while processing the selection.');
            }
        });
    }
</script>

</body>
</html>
