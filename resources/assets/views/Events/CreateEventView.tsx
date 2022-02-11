import createEvent from '@/api/createEvent';
import geocode from '@/api/geocode';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import SelectBox from '@/components/Form/SelectBox/SelectBox';
import SportsSelectBox from '@/components/Form/SelectBox/SportSelectbox';
import Textarea from '@/components/Form/Textarea/Textarea';
import Section from '@/components/Section/Section';
import Separator from '@/components/Separator/Separator';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import { observer } from 'mobx-react';
import React, { useEffect, useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import View from '../View/View';
import styles from './CreateEventView.scss';

const CreateEventView: React.FC = observer(() => {
    useEffect(() => {
        findAddressCoords('Poznań');
        mapViewerStore.reset();
    }, []);

    const navigateTo = useNavigate();

    const [administrativeAreas, setAdministrativeAreas] = useState<string[]>([]);
    const [sportId, setSportId] = useState<number>(null);
    const startDateRef = useRef<HTMLInputElement>();
    const endDateRef = useRef<HTMLInputElement>();
    const priceRef = useRef<HTMLInputElement>();
    const [eventType, setEventType] = useState<number>(null);
    const [isPublic, setIsPublic] = useState<boolean>(true);
    const [minSkillLevel, setMinSkillLevel] = useState<number>(null);
    const [genderId, setGenderId] = useState<number>(null);
    const objectNameRef = useRef<HTMLInputElement>();
    const addressRef = useRef<HTMLInputElement>();

    const findAddressCoords = async (address: string) => {
        const { location, formattedAddress, administrativeAreas, viewport } = await geocode(address);
        setAdministrativeAreas(administrativeAreas);
        addressRef.current.value = formattedAddress;
        const marker = new google.maps.Marker({
            position: location,
            draggable: true,
        });

        const { sw, ne } = viewport;
        mapViewerStore.setBounds(sw, ne);

        marker.addListener('dragend', async () => {
            const { formattedAddress, administrativeAreas } = await geocode(
                marker.getPosition().toJSON()
            );

            setAdministrativeAreas(administrativeAreas);
            addressRef.current.value = formattedAddress;
        });

        marker.addListener('click', () => {
            const markerPos = marker.getPosition().toJSON();
            mapViewerStore.setBounds(markerPos, markerPos);
        });

        mapViewerStore.setMarkers([marker]);
    };

    return (
        <View withBackground title="Dodaj Ogłoszenie">
            <Section title="Dane podstawowe" titleAlign="right" titleSize={15}>
                <div className={styles.locationSection}>
                    <SportsSelectBox
                        dark
                        searchBar
                        label="Sport"
                        sports={appStore.sports}
                        onChange={([option]) => setSportId(option.value.id)}
                    />
                    <Input ref={startDateRef} type="date" label="Data rozpoczęcia" />
                    <Input ref={endDateRef} type="date" label="Data zakończenia" />
                    <Input ref={priceRef} label="Cena" />
                    <SelectBox
                        dark
                        label="Wariant gry"
                        options={[
                            { text: 'Podstawowy', value: 0, isSelected: true },
                            { text: 'Zaawansowany', value: 1 }
                        ]}
                        onChange={([option]) => setEventType(option.value)}
                    />
                    <SelectBox
                        dark
                        label="Ogłoszenie publiczne"
                        options={[
                            { text: 'tak', value: true, isSelected: true },
                            { text: 'nie', value: false }
                        ]}
                        onChange={([option]) => setIsPublic(option.value)}
                    />
                    <SelectBox
                        dark
                        label="Minimalny poziom"
                        options={[
                            {
                                text: 'Wszystkie poziomy',
                                value: null,
                                isSelected: true
                            },
                            ...[...Array(5).keys()].map(i => {
                                return {
                                    text: `Poziom ${i + 1}`,
                                    value: i + 1
                                };
                            })
                        ]}
                        onChange={([option]) => setMinSkillLevel(option.value)}
                    />
                    <SelectBox
                        dark
                        label="Płeć"
                        options={[
                            { text: 'Brak podziału', value: null, isSelected: true },
                            { text: 'Kobiety', value: 10 },
                            { text: 'Mężczyźni', value: 9 }
                        ]}
                        onChange={([option]) => setGenderId(option.value)}
                    />
                    <Input ref={objectNameRef} label="Nazwa obiektu" style={{ gridColumn: 'span 2' }} />
                    <Input
                        ref={addressRef}
                        label="Adres"
                        style={{ gridColumn: 'span 2' }}
                        onBlur={() => findAddressCoords(addressRef.current.value)}
                    />
                </div>
            </Section>
            {/* <Separator />
            <Section title="Uczestnicy" titleAlign="right" titleSize={15}>
                uczestnicy
            </Section> */}
            <Separator />
            <Section title="Pozostałe" titleAlign="right" titleSize={15}>
                <div className={styles.restSection}>
                    <Input type="file" label="Zdjęcie wydarzenia" />
                    <div></div>
                    {/* <Input ref={startDateRef} type="file" label="Zdjęcie w tle" /> */}
                    <Textarea label="Opis" value="opis" style={{ gridColumn: 'span 2' }} />
                </div>
            </Section>
            <div style={{ marginTop: '20px', float: 'right' }}>
                <OrangeButton
                    onClick={async () => {
                        const eventId = await createEvent({
                            administrativeAreas,
                            sportId,
                            startDate: new Date(startDateRef.current.value),
                            endDate: new Date(endDateRef.current.value),
                            ticketPrice: +priceRef.current.value,
                            description: '',
                            isPublic,
                            minimumSkillLevelId: minSkillLevel,
                            gameVariantId: 77,
                            genderId,
                            availableTicketsCount: 15,
                            facility: {
                                name: 'jakis obiekt',
                                coords: {
                                    lat: 50, lng: 20
                                },
                                street: 'Nagietkowa 45'
                            }
                        });

                        if (eventId) {
                            navigateTo('/ogloszenia/' + eventId);
                        }
                    }}
                >
                    Dodaj ogłoszenie
                </OrangeButton>
            </div>
        </View>
    );
});

export default CreateEventView;