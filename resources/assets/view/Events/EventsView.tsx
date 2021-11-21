import React from 'react';
import EventTile, { EventTileProps } from './EventTile';
import styles from './EventsView.scss';
import faker from 'faker';

faker.locale = 'pl';
const fakeData: EventTileProps[] = [];

for (let i = 0; i < 20; i++) {
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
    return (
        <div className={styles.eventsView}>
            <div className={styles.filters}></div>
            <div className={styles.containerWrapper}>
                <div className={styles.eventTilesContainer}>
                    {fakeData.map((d, i) => <EventTile {...d} key={i} />)}
                </div>
            </div>
        </div>
    );
};

export default EventsView;