import getEvents, { IEvent } from '@/api/getEvents';
import Input from '@/components/Form/Input/Input';
import SelectBox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import SportsSelectBox from '@/components/Form/SelectBox/SportSelectbox';
import useScrollbar from '@/layout/Content/Scrollbar/Scrollbar';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import View from '@/views/View/View';
import React, { Fragment, useEffect, useState } from 'react';
import styles from './EventsView.scss';
import EventTile from './EventTile';

const EventsView: React.FC = () => {
    const { containerRef } = useScrollbar();
    const [queryString, setQueryString] = useState('');
    const [events, setEvents] = useState<IEvent[]>(null);
    const areEventsLoaded = events !== null;

    const options: IOption<number>[] = [
        { text: 'Poznań', value: 1 },
        { text: 'Warszawa', value: 1 },
        { text: 'Gdańsk', value: 1 },
        { text: 'Luboń', value: 1 }
    ];

    useEffect(() => {
        mapViewerStore.reset();
        getEvents().then(setEvents);
    }, []);

    areEventsLoaded && mapViewerStore.setEventPins(events.map(event => {
        return {
            id: event.id,
            color: event.sport.color,
            ...event.facility?.location
        };
    }));

    return (
        <View isLoaderVisible={!areEventsLoaded}>
            {areEventsLoaded && (
                <Fragment>
                    <div className={styles.filters}>
                        <Input
                            value={queryString}
                            onChange={(v) => setQueryString(v)}
                        />
                        <SelectBox
                            options={options}
                            placeholder="Lokalizacja"
                        />
                        <SportsSelectBox
                            searchBar
                            sports={appStore.sports}
                            placeholder="Sporty"
                        />
                        <SelectBox
                            options={options}
                            placeholder="Więcej filtrów"
                        />
                        <SelectBox
                            options={options}
                            placeholder="Sortuj wg: Najlepsze"
                            transparent={true}
                        />
                    </div>
                    <div className={styles.containerWrapper} ref={containerRef}>
                        <div className={styles.eventTilesContainer}>
                            {events.map((event, i) => <EventTile {...event} key={i} />)}
                        </div>
                    </div>
                </Fragment>
            )}
        </View>
    );
};

export default EventsView;