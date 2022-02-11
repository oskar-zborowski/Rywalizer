import React from 'react';
import styles from './EventTile.scss';
import chroma from 'chroma-js';
import BallSvg from '@/static/icons/ball.svg';
import { IEvent } from '@/api/getEvents';
import { Link } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';

const colors = chroma.scale(['#7ab661', '#7ab661', '#ffd653', '#bb2121']);

const EventTile: React.FC<IEvent> = (props) => {
    const ratio = props.soldTicketsCount / props.availableTicketsCount;
    const percent = ratio * 100;
    const progressBarColor = colors(ratio).hex();
    const sportColor = props.sport.color.hex();

    const navigateTo = useNavigate();

    return (
        <div
            className={styles.eventTile}
            onClick={() => navigateTo(`/ogloszenia/${props.id}`)}
        >
            <div className={styles.border} style={{ backgroundColor: sportColor }}></div>
            <div className={styles.tile}>
                <div className={styles.imageWrapper}>
                    {props.imageUrl && <img src={props.imageUrl} className={styles.image} />}
                </div>
                <div className={styles.detailsRow}>
                    <span className={styles.locationName} style={{ color: sportColor }}>
                        {props.facility?.name}
                    </span>
                    <span className={styles.price}>{(props.ticketPrice / 100).toFixed(2)} zł</span>
                </div>
                <div className={styles.detailsRow}>
                    <span className={styles.address}>{props.facility?.street}</span>
                    <span className={styles.date}>{props.startDate.toLocaleDateString()}</span>
                </div>
                <div className={styles.divider}></div>
                <div className={styles.detailsRow}>
                    <div className={styles.busyBar}>
                        <div className={styles.innerBar} style={{
                            width: percent + '%',
                            backgroundColor: progressBarColor
                        }}></div>
                    </div>
                    <span className={styles.date}>{props.soldTicketsCount} / {props.availableTicketsCount}</span>
                </div>
            </div>
            <div className={styles.icon} style={{ backgroundColor: sportColor }}>
                <BallSvg />
            </div>
        </div>
    );
};

export default EventTile;