<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My playlist</title>
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

        // Create the playlist table if it doesn't exist
        $createTableQuery = "CREATE TABLE IF NOT EXISTS playlist (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                song_id INTEGER
                             )";
        $conn->exec($createTableQuery);

        // Loop through selected songs
        foreach ($_POST['selectedSongs'] as $selectedSongId) {
            // Insert selected song into the playlist table
            $insertQuery = "INSERT INTO playlist (song_id) VALUES ('$selectedSongId')";
            $conn->exec($insertQuery);
        }

        // Fetch songs from the playlist table
        $playlistQuery = "SELECT * FROM playlist JOIN songs ON playlist.song_id = songs.song_id";
        $playlistResult = $conn->query($playlistQuery);

        // Display the playlist in an HTML table
        echo "<div style='max-height: 500px; overflow: auto;'>";

        echo "<table style='border: none'>";

        while ($playlistRow = $playlistResult->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            ;
            echo "<td><input type='checkbox' name='selectedSongs[]' value='{$playlistRow['id']}'></td>";
            echo "<td>{$playlistRow['song_name']} - {$playlistRow['artist']} {$playlistRow['length']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
        // Close connection
        $conn->close();
    } else {
        echo "No songs selected!";
    }
} else {
    // Empty the playlist table on page reload
    $database = "identifier.sqlite";
    $conn = new SQLite3($database);

    // Check connection
    if (!$conn) {
        die("Connection failed");
    }

    // Delete all records from the playlist table
    $deleteQuery = "DELETE FROM playlist";
    $conn->exec($deleteQuery);

    // Close connection
    $conn->close();

    echo "Playlist emptied on page reload!";
}


//remove the song from the playlist once it has been played
$database = "identifier.sqlite";
$conn = new SQLite3($database);


// Check connection
if (!$conn) {
    die("Connection failed");
}


?>
<!-- Root element for React components -->
<div id="react-root"></div>

<script>
    // React component for NowPlaying
    const NowPlaying = ({ songName, artist, length, link }) => (
        <table id='cursong'>
            <tr>
                <td>{`${songName} - ${artist} ${length}`}</td>
            </tr>
        </table>
    );

    // React component for AudioPlayer
    class AudioPlayer extends React.Component {
        constructor(props) {
            super(props);
            this.state = {
                isPlaying: false,
            };
        }

        togglePlayPause = () => {
            this.setState((prevState) => ({
                isPlaying: !prevState.isPlaying,
            }));
        };

        render() {
            return (
                <div>
                    <audio id='audioPlayer' controls>
                        <source src={`music/${this.props.link}`} type='audio/mpeg' />
                        Your browser does not support the audio element.
                    </audio>
                    <button id='playButton' onClick={this.togglePlayPause}>
                        {this.state.isPlaying ? 'Pause' : 'Play'}
                    </button>
                </div>
            );
        }
    }

    // React component for Playlist
    const Playlist = ({ songs }) => (
        <div style={{ maxHeight: '500px', overflow: 'auto' }}>
            <table style={{ border: 'none' }}>
                {songs.map((song) => (
                    <tr key={song.id}>
                        <td>
                            <input type='checkbox' name='selectedSongs[]' value={song.id} />
                        </td>
                        <td>{`${song.song_name} - ${song.artist} ${song.length}`}</td>
                    </tr>
                ))}
            </table>
        </div>
    );
</script>

</body>
</html>
