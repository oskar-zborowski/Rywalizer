import React from 'react';
import styles from './EventTile.scss';
import chroma from 'chroma-js';

export interface EventTileProps {
    imageSrc: string;
    price: number;
    date: Date;
    locationName: string;
    address: string;
    soldTickets: number;
    availableTickets: number;
}

//TODO nieliniowa funckja, patrz nizej
const colors = chroma.scale(['#7ab661', '#7ab661', '#ffd653', '#bb2121']).colors(20);

const EventTile: React.FC<EventTileProps> = (props) => {
    //TODO zmiana nazwy percent na coś sensowniejszego
    const ratio = props.soldTickets / props.availableTickets;
    const percent = ratio * 100;

    //TODO nieliniowa funckja - powinno być znacznie więcej zielonego ??
    const color = colors[Math.trunc(ratio * (colors.length - 1))];

    return (
        <div className={styles.eventTile}>
            <img src={props.imageSrc} className={styles.image} />
            <div className={styles.detailsRow}>
                <span className={styles.locationName}>{props.locationName}</span>
                <span className={styles.price}>{(props.price / 100).toFixed(2)} zł</span>
            </div>
            <div className={styles.detailsRow}>
                <span className={styles.address}>{props.address}</span>
                <span className={styles.date}>{props.date.toLocaleDateString()}</span>
            </div>
            <div className={styles.divider}></div>
            <div className={styles.detailsRow}>
                <div className={styles.busyBar}>
                    <div className={styles.innerBar} style={{ 
                        width: percent + '%',
                        backgroundColor: color
                    }}></div>
                </div>
                <span className={styles.date}>{props.soldTickets}/{props.availableTickets}</span>
            </div>
        </div>
    );
};

export default EventTile;