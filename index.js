import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';

const nowPlayingData = {
    songName: 'Never Gonna Give You Up',
    artist: 'Rick Astley',
    length: '3:33',
    link: 'never_gonna_give_you_up.mp3',
};

ReactDOM.render(<App nowPlayingData={nowPlayingData} />, document.getElementById('react-root'));
