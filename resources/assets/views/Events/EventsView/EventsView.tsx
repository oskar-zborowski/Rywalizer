import Input from '@/components/Form/Input/Input';
import Selectbox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import useScrollbar from '@/layout/Content/Scrollbar/Scrollbar';
import faker from 'faker';
import React, { useState } from 'react';
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
        date: new Date(),
        color: ['#FFD653', '#FF5E44', '#5CD3E6', '#7AB661'][Math.round(Math.random() * 3)]
    });
}

const EventsView: React.FC = () => {
    const { containerRef } = useScrollbar();

    const [queryString, setQueryString] = useState('');

    const options: IOption<number>[] = [
        { text: 'Poznań', value: 1 },
        { text: 'Warszawa', value: 1 },
        { text: 'Gdańsk', value: 1 },
        { text: 'Luboń', value: 1 }
    ];

    return (
        <div className={styles.eventsView}>
            <div className={styles.filters}>
                <Input
                    value={queryString}
                    onChange={(v) => setQueryString(v)}
                />
                <Selectbox
                    initialOptions={options}
                    placeholder="Lokalizacja"
                />
                <Selectbox
                    initialOptions={options}
                    placeholder="Sporty"
                />
                <Selectbox
                    initialOptions={options}
                    placeholder="Więcej filtrów"
                />
                <Selectbox
                    initialOptions={options}
                    placeholder="Sortuj wg: Najlepsze"
                    transparent={true}
                />
            </div>
            <div className={styles.containerWrapper} ref={containerRef}>
                <div className={styles.eventTilesContainer}>
                    {fakeData.map((d, i) => <EventTile {...d} key={i} />)}
                </div>
            </div>
        </div>
    );
};

export default EventsView;