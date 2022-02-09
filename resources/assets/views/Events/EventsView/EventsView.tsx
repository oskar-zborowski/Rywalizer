import getEvents, { IEvent } from '@/api/getEvents';
import Input from '@/components/Form/Input/Input';
import Selectbox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import useScrollbar from '@/layout/Content/Scrollbar/Scrollbar';
import React, { useEffect, useState } from 'react';
import styles from './EventsView.scss';
import EventTile from './EventTile';

const EventsView: React.FC = () => {
    const { containerRef } = useScrollbar();
    const [queryString, setQueryString] = useState('');
    const [events, setEvents] = useState<IEvent[]>([]);

    const options: IOption<number>[] = [
        { text: 'Poznań', value: 1 },
        { text: 'Warszawa', value: 1 },
        { text: 'Gdańsk', value: 1 },
        { text: 'Luboń', value: 1 }
    ];

    useEffect(() => {
        getEvents().then(setEvents);
    }, []);

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
                    {events.map((event, i) => <EventTile {...event} key={i} />)}
                </div>
            </div>
        </div>
    );
};

export default EventsView;