import getEvents, { IEvent, IGetEventsParams } from '@/api/getEvents';
import { ISport } from '@/api/getSports';
import Input from '@/components/Form/Input/Input';
import SelectBox, { IOption, useSelectBox } from '@/components/Form/SelectBox/SelectBox';
import SportsSelectBox from '@/components/Form/SelectBox/SportSelectbox';
import useScrollbar from '@/layout/Content/Scrollbar/Scrollbar';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import View from '@/views/View/View';
import React, { Fragment, useEffect, useRef, useState } from 'react';
import { useParams } from 'react-router-dom';
import styles from './EventsView.scss';
import EventTile from './EventTile';

const EventsView: React.FC = () => {
    const { alias } = useParams();
    const initFilters: IGetEventsParams['filters'] = {
        partnerAlias: alias ?? undefined
    };

    const { containerRef } = useScrollbar();
    const [queryString, setQueryString] = useState('');
    const [events, setEvents] = useState<IEvent[]>(null);
    const [filters, setFilters] = useState<IGetEventsParams['filters']>(initFilters);
    const areEventsLoaded = events !== null;

    useEffect(() => {
        mapViewerStore.reset();
        mapViewerStore.setPosition({
            lat: 52.409538, 
            lng: 16.931992
        }, 12);
    }, []);

    useEffect(() => {
        getEvents({
            filters: filters
        }).then(setEvents);
    }, [filters]);

    areEventsLoaded && mapViewerStore.setEventPins(events.map(event => {
        return {
            id: event.id,
            color: event.sport.color,
            ...event.facility?.location
        };
    }));

    const sportsSelect = useSelectBox<ISport>([], (opts) => {
        setFilters(filters => {
            filters.sportIds = opts.map(opt => opt.value.id);
            return { ...filters };
        });
    });

    const sortSelect = useSelectBox<[IGetEventsParams['filters']['sort'], IGetEventsParams['filters']['sortDir']]>([
        { text: 'Cena malejąco', value: ['ticket_price', 'desc'] },
        { text: 'Cena rosnąco', value: ['ticket_price', 'asc'], isSelected: true },
        { text: 'Data malejąco', value: ['start_date', 'asc'] },
        { text: 'Data rosnąco', value: ['start_date', 'desc'] },
        { text: 'Poziom malejąco', value: ['minimum_skill_level_id', 'desc'] },
        { text: 'Poziom rosnąco', value: ['minimum_skill_level_id', 'asc'] }
    ], ([opt]) => {
        sortSelect.setPlaceholder(`Sortuj wg: ${opt?.text}`);
        setFilters(filters => {
            filters.sort = opt.value[0];
            filters.sortDir = opt.value[1];
            return { ...filters };
        });
    });

    useEffect(() => {
        sportsSelect.setPlaceholder('Sporty');
        sportsSelect.setOptions(appStore.sports.map(sport => {
            return {
                text: sport.name,
                value: sport
            };
        }));
    }, [appStore.sports]);

    return (
        <View isLoaderVisible={!areEventsLoaded}>
            {areEventsLoaded && (
                <Fragment>
                    <div className={styles.filters}>
                        <Input
                            placeholder="Nazwa obiektu / region / ulica"
                            value={queryString}
                            style={{ gridColumn: 'span 2' }}
                            onChange={(v) => setQueryString(v)}
                            onBlur={() => {
                                setFilters(filters => {
                                    filters.search = queryString;
                                    return { ...filters };
                                });
                            }}
                        // style={{flex: 100}}
                        />
                        {/* <SelectBox
                            {...locationSelect}
                            searchBar
                            placeholder="Lokalizacja"
                        /> */}
                        <SelectBox
                            searchBar
                            multiselect
                            minWidth={230}
                            {...sportsSelect}
                        />
                        {/* <SelectBox
                            options={options}
                            placeholder="Więcej filtrów"
                        /> */}
                        <SelectBox
                            {...sortSelect}
                        // transparent={true}
                        />
                    </div>
                    <div className={styles.containerWrapper} ref={containerRef}>
                        <div className={styles.eventTilesContainer}>
                            {events.length ? events.map((event, i) => <EventTile {...event} key={i} />) : 'Brak wyników'}
                        </div>
                    </div>
                </Fragment>
            )}
        </View>
    );
};

export default EventsView;