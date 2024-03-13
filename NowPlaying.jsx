import React from 'react';

const NowPlaying = ({ songName, artist, length, link }) => (
    <table id='cursong'>
        <tr>
            <td>{`${songName} - ${artist} ${length}`}</td>
        </tr>
    </table>
);

export default NowPlaying;
