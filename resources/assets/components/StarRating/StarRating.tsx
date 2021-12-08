import React from 'react';
import styles from './StarRating.scss';

export interface IStarRatings {
    rating: number;
    starsCount?: number;
    size?: string;
    height?: string;
}

const StarRatings: React.FC<IStarRatings> = ({ rating, starsCount, size, height }) => {
    const starsString = [...new Array(starsCount || 5)].map(() => '★');
    const upperStyle = {
        width: rating + '%',
        fontSize: size,
        height: height
    };

    return (
        <div className={styles.starRating}>
            <div className={styles.ratingUpper} style={upperStyle}>
                <span>{starsString}</span>
            </div>
            <div className={styles.ratingLower}>
                <span>{starsString}</span>
            </div>
        </div>
    );
};

export default StarRatings;