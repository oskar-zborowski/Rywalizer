import useScrollbar from '@/layout/Content/Scrollbar/Scrollbar';
import faker from 'faker';
import React from 'react';
import styles from './EventsView.scss';
import EventTile, { EventTileProps } from './EventTile';

faker.locale = 'pl';
const fakeData: EventTileProps[] = [];

const tilesCount = Math.trunc(Math.random() * 40) + 5;

for (let i = 0; i < tilesCount; i++) {
    const availableTickets = Math.round(Math.random() * 100) + 10;
    const soldTickets = Math.round(Math.random() * availableTickets);

    fakeData.push({
        availableTickets,
        soldTickets,
        price: 3500,
        address: faker.address.cityName(),
        locationName: faker.address.cityName(),
        imageSrc: faker.image.image(),
        date: new Date()
    });
}

const EventsView: React.FC = () => {
    const { containerRef } = useScrollbar();

    return (
        <div className={styles.eventsView}>
            <div className={styles.filters}></div>
            <div className={styles.containerWrapper} ref={containerRef}>
                <div className={styles.eventTilesContainer}>
                    {fakeData.map((d, i) => <EventTile {...d} key={i} />)}
                </div>
            </div>
        </div>
    );
};

export default EventsView;