import saveEvent from '@/api/saveEvent';
import geocode, { IGeocodeResults } from '@/api/geocode';
import getEvents, { IEvent } from '@/api/getEvents';
import { IGender } from '@/api/getGenders';
import { ISport, ISportSkillLevel } from '@/api/getSports';
import { BlackButton, OrangeButton } from '@/components/Form/Button/Button';
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
import { useLocation, useNavigate, useParams } from 'react-router-dom';
import View from '../View/View';
import styles from './CreateEventView.scss';
import ErrorModal from '@/modals/ErrorModal';
import extractError from '@/api/extractError';
import { AxiosError } from 'axios';
import { runInAction } from 'mobx';
import deleteTaskPhoto from '@/api/deleteTaskPhoto';

const CreateEventView: React.FC = observer(() => {
    const [error, setError] = useState<string>('');
    const [isErrorModalOpen, setIsErrorModalOpen] = useState(false);

    const { id } = useParams();
    const [event, setEvent] = useState<IEvent>(null);
    const navigateTo = useNavigate();

    const minLevelSelect = useSelectBox<ISportSkillLevel>([]);
    const genderSelect = useSelectBox<IGender>([]);
    const sportSelect = useSelectBox<ISport>([], ([opt]) => {
        opt && minLevelSelect.setOptions(opt.value?.skillLevels.map(level => {
            return {
                text: level.name,
                value: level,
                isSelected: event && level.id == event?.minSkillLevelId
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

    const loc = useLocation();

    useEffect(() => {
        if (loc.pathname.includes('dodaj')) {
            setEvent(null);
        }
    }, [loc]);

    const [location, setLocation] = useState<IGeocodeResults>(null);
    const startDateRef = useRef<HTMLInputElement>();
    const endDateRef = useRef<HTMLInputElement>();
    const priceRef = useRef<HTMLInputElement>();
    const ticketsAvailableRef = useRef<HTMLInputElement>();
    const objectNameRef = useRef<HTMLInputElement>();
    const addressRef = useRef<HTMLInputElement>();
    const descriptionRef = useRef<HTMLTextAreaElement>();
    const imagefileRef = useRef<HTMLInputElement>();
    const [newImageUrl, setNewImageUrl] = useState(null);
    const [newImageFile, setNewImageFile] = useState<File>(null);

    const displayImage = (file: File) => {
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            setNewImageUrl(e.target.result);
            setNewImageFile(file);
        };

        reader.readAsDataURL(file);
    };

    useEffect(() => {
        if (!userStore.user) { //TODO sprawdzenie czy user jest partnerem
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
                startDateRef.current.value = moment(event.startDate).format('YYYY-MM-DDThh:mm');
                endDateRef.current.value = moment(event.endDate).format('YYYY-MM-DDThh:mm');
                priceRef.current.value = (event.ticketPrice / 100) + '';
                ticketsAvailableRef.current.value = event.availableTicketsCount + '';
                //eventType
                isPublicSelect.select(opt => opt == event.isPublic);
                minLevelSelect.select(opt => opt?.id == event.minSkillLevelId);
                genderSelect.select(0); //TODO
                objectNameRef.current.value = event.facility.name;
                descriptionRef.current.value = event.description;

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
            descriptionRef.current.value = null;
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
        try {
            const eventId = await saveEvent({
                administrativeAreas: location.administrativeAreas,
                sportId: sportSelect.selectedOptions[0]?.value.id,
                startDate: new Date(startDateRef.current.value),
                endDate: new Date(endDateRef.current.value),
                ticketPrice: +priceRef.current.value,
                description: descriptionRef.current.value,
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
            }, newImageFile, +id);

            if (eventId) {
                navigateTo('/ogloszenia/' + eventId);
            }
        } catch (err) {
            setIsErrorModalOpen(true);
            setError(extractError(err as AxiosError).message);
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
                        label="* Sport"
                        {...sportSelect}
                    />
                    <Input
                        ref={startDateRef}
                        type="datetime-local"
                        label="* Data rozpoczęcia"
                        min={moment(new Date()).format('YYYY-MM-DDThh:mm')}
                    />
                    <Input
                        ref={endDateRef}
                        type="datetime-local"
                        label="* Data zakończenia"
                        min={moment(new Date()).format('YYYY-MM-DDThh:mm')}
                    />
                    <Input ref={priceRef} label="* Cena" />
                    {/* <SelectBox
                        dark
                        label="Wariant gry"
                        {...gameVariantSelect}
                    /> */}
                    <SelectBox
                        dark
                        label="* Ogłoszenie publiczne"
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
                    <Input ref={ticketsAvailableRef} label="* Ilość miejsc" />
                    <Input ref={objectNameRef} label="Nazwa obiektu" style={{ gridColumn: 'span 2' }} />
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
                    <div className={styles.eventImageWrapper}>
                        <label className={styles.label}>Zdjęcie wydarzenia</label>
                        <div className={styles.eventImage}>
                            {(newImageUrl ?? event?.imageUrl) && (
                                <img src={newImageUrl ?? event?.imageUrl} alt="" />
                            )}
                        </div>
                        <input
                            ref={imagefileRef}
                            type="file"
                            style={{ display: 'none' }}
                            accept="image/jpeg, image/png"
                            onChange={(e) => displayImage(e.currentTarget.files?.[0])}
                        />
                        <OrangeButton style={{ width: '100%' }} onClick={() => imagefileRef.current.click()}>
                            Zmień zdjęcie
                        </OrangeButton>
                        <BlackButton style={{ width: '100%', marginTop: '10px' }} onClick={async () => {
                            if (event?.imageId) {
                                try {
                                    await deleteTaskPhoto(event.id, event.imageId);

                                    setNewImageUrl(undefined);
                                    setNewImageFile(undefined);

                                    setEvent(event => {
                                        event.imageId = undefined;
                                        event.imageUrl = undefined;

                                        return { ...event };
                                    });
                                } catch (err) {
                                    setIsErrorModalOpen(true);
                                    setError(extractError(err as AxiosError).message);
                                }
                            } else {
                                setNewImageFile(undefined);
                                setNewImageUrl(undefined);
                            }
                        }}>
                            Usuń zdjęcie
                        </BlackButton>
                    </div>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '10px', flex: '1', alignItems: 'end' }}>
                        <Textarea ref={descriptionRef} label="Opis" placeholder="Opis" style={{ gridColumn: 'span 3', flex: 'none' }} height={180} />
                        <OrangeButton
                            onClick={saveEventInner}
                            style={{ width: 'min-content' }}
                        >
                            {event ? 'Zapisz zmiany' : 'Dodaj ogłoszenie'}
                        </OrangeButton>
                    </div>
                </div>
            </Section>
            <ErrorModal error={error} isOpen={isErrorModalOpen} setIsOpen={(isOpen) => setIsErrorModalOpen(isOpen)} />
        </View>
    );
});

export default CreateEventView;