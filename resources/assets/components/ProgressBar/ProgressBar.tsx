import chroma from 'chroma-js';
import React from 'react';
import styles from './ProgressBar.scss';

export interface IProgressBarProps {
    progress: number;
    colors?: string[];
    colorsCount?: number;
    colorIndexFunction?: (progress: number, colorsCount: number) => number
}

const defaultColorIndexFunction = (progress: number, colorsCount: number) => {
    const maxIndex = colorsCount - 1;

    return Math.max(Math.min(Math.round(progress / 100 * maxIndex), maxIndex), 0);
};

const ProgressBar: React.FC<IProgressBarProps> = (props) => {
    const {
        progress,
        colors = ['#7ab661'],
        colorsCount = colors.length + (colors.length - 1) * 5,
        colorIndexFunction = defaultColorIndexFunction
    } = props;

    const colorsArray = chroma.scale(colors).colors(colorsCount);
    const colorIndex = colorIndexFunction(progress, colorsCount);
    const color = colorsArray[colorIndex];
    const barStyle = {
        width: progress + '%',
        backgroundColor: color
    };

    return (
        <div className={styles.progressBar}>
            <div className={styles.innerBar} style={barStyle}></div>
        </div>
    );
};

export default ProgressBar;