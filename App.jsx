// App.jsx
import React from 'react';

const NowPlaying = ({ songName, artist, length }) => (
    <table id='cursong'>
        <tr>
            <td>{`${songName} - ${artist} ${length}`}</td>
        </tr>
    </table>
);

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

const App = ({ nowPlayingData }) => (
    <div>
        <NowPlaying {...nowPlayingData} />
        <AudioPlayer {...nowPlayingData} />
    </div>
);

export default App;
