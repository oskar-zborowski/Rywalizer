import saveEvent from '@/api/saveEvent';
import geocode, { IGeocodeResults } from '@/api/geocode';
import getEvents, { IEvent } from '@/api/getEvents';
import { IGender } from '@/api/getGenders';
import { ISport, ISportSkillLevel } from '@/api/getSports';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import SelectBox, { useSelectBox } from '@/components/Form/SelectBox/SelectBox';
import Textarea from '@/components/Form/Textarea/Textarea';
import Section from '@/components/Section/Section';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import userStore from '@/store/UserStore';
import { observer } from 'mobx-react';
import moment from 'moment';
import React, { Fragment, useEffect, useRef, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import View from '../View/View';
import styles from './CreateEventView.scss';

const CreateEventView: React.FC = observer(() => {
    const { id } = useParams();
    const [event, setEvent] = useState<IEvent>(null);
    const navigateTo = useNavigate();

    const minLevelSelect = useSelectBox<ISportSkillLevel>([]);
    const genderSelect = useSelectBox<IGender>([]);
    const sportSelect = useSelectBox<ISport>([], ([opt]) => {
        opt && minLevelSelect.setOptions(opt.skillLevels.map(level => {
            return {
                text: level.name,
                value: level
            };
        }));
    });
    const gameVariantSelect = useSelectBox([
        { text: 'Podstawowy', value: 0, isSelected: true },
        { text: 'Zaawansowany', value: 1 }
    ]);
    const isPublicSelect = useSelectBox([
        { text: 'Tak', value: true, isSelected: true },
        { text: 'Nie', value: false }
    ]);

    const [location, setLocation] = useState<IGeocodeResults>(null);
    const startDateRef = useRef<HTMLInputElement>();
    const endDateRef = useRef<HTMLInputElement>();
    const priceRef = useRef<HTMLInputElement>();
    const ticketsAvailableRef = useRef<HTMLInputElement>();
    const objectNameRef = useRef<HTMLInputElement>();
    const addressRef = useRef<HTMLInputElement>();

    useEffect(() => {
        if (!userStore.user) {
            navigateTo('/');
            return;
        }

        mapViewerStore.reset();

        if (id) {
            getEvents({ id: +id }).then(async events => {
                const event = events?.[0];
                setEvent(event);
            });
        } else {
            findAddressCoords('Poznań');
        }
    }, []);

    useEffect(() => {
        if (event) {
            (async () => {
                sportSelect.select(opt => opt.id == event.sport.id);
                startDateRef.current.value = moment(event.startDate).format('YYYY-MM-DD');
                endDateRef.current.value = moment(event.endDate).format('YYYY-MM-DD');
                priceRef.current.value = (event.ticketPrice / 100) + '';
                ticketsAvailableRef.current.value = event.availableTicketsCount + '';
                //eventType
                isPublicSelect.select(opt => opt == event.isPublic);
                minLevelSelect.select(opt => opt?.id == event.minSkillLevelId);
                genderSelect.select(0); //TODO
                objectNameRef.current.value = event.facility.name;

                const location = await geocode(event.facility.location);
                setLocation(location);
                mapViewerStore.setPosition(location.location, 15);

                const marker = new google.maps.Marker({
                    position: location.location,
                    draggable: true,
                });
        
                const { sw, ne } = location.viewport;
                mapViewerStore.setBounds(sw, ne);
        
                marker.addListener('dragend', async () => {
                    const location = await geocode(
                        marker.getPosition().toJSON()
                    );
        
                    setLocation(location);
                    if (addressRef.current) addressRef.current.value = location.formattedAddress;
                });
        
                marker.addListener('click', () => {
                    const markerPos = marker.getPosition().toJSON();
                    mapViewerStore.setBounds(markerPos, markerPos);
                });
        
                mapViewerStore.setMarkers([marker]);
            })();
        } else {
            sportSelect.select(null);
            startDateRef.current.value = null;
            endDateRef.current.value = null;
            priceRef.current.value = null;
            ticketsAvailableRef.current.value = null;
            //eventType
            isPublicSelect.select(0);
            minLevelSelect.select(0);
            genderSelect.select(0);
            objectNameRef.current.value = null;
        }
    }, [event]);

    useEffect(() => {
        genderSelect.setOptions([{
            text: 'Brak podziału',
            value: null,
            isSelected: true
        },
        ...appStore.genders.map(gender => {
            return {
                text: gender.name,
                value: gender
            };
        })]);
    }, [appStore.genders]);

    useEffect(() => {
        sportSelect.setOptions(appStore.sports.map(sport => {
            return {
                text: sport.name,
                value: sport
            };
        }));
    }, [appStore.sports]);

    const findAddressCoords = async (address: string) => {
        const location = await geocode(address);
        setLocation(location);

        if (addressRef.current) addressRef.current.value = location.formattedAddress;

        const marker = new google.maps.Marker({
            position: location.location,
            draggable: true,
        });

        const { sw, ne } = location.viewport;
        mapViewerStore.setBounds(sw, ne);

        marker.addListener('dragend', async () => {
            const location = await geocode(
                marker.getPosition().toJSON()
            );

            setLocation(location);
            if (addressRef.current) addressRef.current.value = location.formattedAddress;
        });

        marker.addListener('click', () => {
            const markerPos = marker.getPosition().toJSON();
            mapViewerStore.setBounds(markerPos, markerPos);
        });

        mapViewerStore.setMarkers([marker]);
    };

    const header = (title: string) => {
        return (
            <Fragment>
                <h1>{title}</h1>
                {event &&
                    <OrangeButton onClick={saveEventInner}>
                        Zapisz zmiany
                    </OrangeButton>
                }
            </Fragment >
        );
    };

    const saveEventInner = async () => {
        const eventId = await saveEvent({
            administrativeAreas: location.administrativeAreas,
            sportId: sportSelect.selectedOptions[0]?.value.id,
            startDate: new Date(startDateRef.current.value),
            endDate: new Date(endDateRef.current.value),
            ticketPrice: +priceRef.current.value,
            description: '',
            isPublic: isPublicSelect.selectedOptions[0]?.value,
            minimumSkillLevelId: minLevelSelect.selectedOptions[0]?.value.id,
            gameVariantId: 77,
            genderId: genderSelect.selectedOptions[0]?.value?.id,
            availableTicketsCount: +ticketsAvailableRef.current.value,
            facility: {
                name: objectNameRef.current.value,
                coords: location.location,
                street: location.street
            }
        }, +id);

        if (eventId) {
            navigateTo('/ogloszenia/' + eventId);
        }
    };

    return (
        <View
            withBackground
            title={event ? 'Edycja ogłoszenia' : 'Dodaj ogłoszenie'}
            headerContent={header}
        >
            <Section title="Dane podstawowe" titleAlign="right" titleSize={15}>
                <div className={styles.locationSection}>
                    <SelectBox
                        dark
                        searchBar
                        label="Sport"
                        {...sportSelect}
                    />
                    <Input ref={startDateRef} type="date" label="Data rozpoczęcia" />
                    <Input ref={endDateRef} type="date" label="Data zakończenia" />
                    <Input ref={priceRef} label="Cena" />
                    <SelectBox
                        dark
                        label="Wariant gry"
                        {...gameVariantSelect}
                    />
                    <SelectBox
                        dark
                        label="Ogłoszenie publiczne"
                        {...isPublicSelect}
                    />
                    <SelectBox
                        dark
                        label="Minimalny poziom"
                        {...minLevelSelect}
                    />
                    <SelectBox
                        dark
                        label="Płeć"
                        {...genderSelect}
                    />
                    <Input ref={ticketsAvailableRef} label="Ilość miejsc" />
                    <Input ref={objectNameRef} label="Nazwa obiektu" />
                    <Input
                        ref={addressRef}
                        label="Adres"
                        style={{ gridColumn: 'span 2' }}
                        onBlur={() => findAddressCoords(addressRef.current.value)}
                    />
                </div>
            </Section>
            <Section style={{ marginTop: '25px' }} title="Pozostałe" titleAlign="right" titleSize={15}>
                <div className={styles.restSection}>
                    <Input type="file" label="Zdjęcie wydarzenia" />
                    <Textarea label="Opis" value="opis" style={{ gridColumn: 'span 3' }} />
                </div>
            </Section>
            <div style={{ marginTop: '20px', float: 'right' }}>
                <OrangeButton
                    onClick={saveEventInner}
                >
                    {event ? 'Zapisz zmiany' : 'Dodaj ogłoszenie'}
                </OrangeButton>
            </div>
        </View>
    );
});

export default CreateEventView;