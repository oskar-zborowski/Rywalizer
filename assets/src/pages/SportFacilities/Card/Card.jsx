import useResize from '@/src/hooks/useResize';
import React, { useRef } from 'react';

// @ts-ignore
import styles from './Card.scss?module';

const Dots = ({popularity}) => {
    const dotsCount = 5;
    const dots = [...Array(5).keys()];
    const filledDots = +popularity / (100 / dotsCount);

    return (
        <div className={styles.dotsContainer}>
            {dots.map(i => {
                const fillPercentage = Math.max(0, Math.min((filledDots - i) * 100, 100));

                return (
                    <div className={styles.dot} key={i}>
                        <div className={styles.dotInner} style={{ width: `${fillPercentage}%` }}></div>
                    </div>
                );
            })}
        </div>
    );
};

const Card = props => {
    const { 
        facilityName, 
        location, 
        imageUrl, 
        popularity, 
        price,
        onMouseEnter,
        onClick
    } = props;

    const imgRef = useRef(null);
    const { width } = useResize(imgRef);

    return (
        <div className={styles.container} onMouseEnter={onMouseEnter} onClick={onClick}>
            <div className={styles.innerContainer}>
                <div ref={imgRef} className={styles.imageContainer} style={{ height: width }}>
                    <img
                        className={styles.image}
                        src={imageUrl}
                        alt={facilityName}
                    />
                </div>
                <div className={styles.data}>
                    <div className={styles.dataHeader}>{facilityName}</div>
                    <div className={styles.dataLocation}>{location}</div>
                    <div className={styles.dataPrice}>{price}</div>
                    <Dots popularity={popularity}/>
                </div>
            </div>
        </div>
    );
};

export default Card;