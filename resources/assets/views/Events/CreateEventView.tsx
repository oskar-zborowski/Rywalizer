import geocode from '@/api/geocode';
import { ISport } from '@/api/getSports';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import GenderSelectBox from '@/components/Form/SelectBox/GenderSelectBox';
import SelectBox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import SportsSelectBox from '@/components/Form/SelectBox/SportSelectbox';
import Textarea from '@/components/Form/Textarea/Textarea';
import Section from '@/components/Section/Section';
import Separator from '@/components/Separator/Separator';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import { observer } from 'mobx-react';
import React, { useEffect, useRef, useState } from 'react';
import View from '../View/View';
import styles from './CreateEventView.scss';

const CreateEventView: React.FC = observer(() => {
    useEffect(() => {
        findAddressCoords('Poznań');
        mapViewerStore.reset();
    }, []);

    const [administrativeAreas, setAdministrativeAreas] = useState<string[]>([]);
    const [sportId, setSportId] = useState<number>(null);
    const startDateRef = useRef<HTMLInputElement>();
    const endDateRef = useRef<HTMLInputElement>();
    const priceRef = useRef<HTMLInputElement>();
    const [eventType, setEventType] = useState<number>(null);
    const [isPublic, setIsPublic] = useState<boolean>(null);
    const [minimalLevel, setMinimalLevel] = useState<number>(null);
    const [genderId, setGenderId] = useState<number>(null);
    const objectNameRef = useRef<HTMLInputElement>();
    const addressRef = useRef<HTMLInputElement>();

    const findAddressCoords = async (address: string) => {
        const { location, formattedAddress, administrativeAreas, viewport } = await geocode(address);
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

            addressRef.current.value = formattedAddress;
            setAdministrativeAreas(administrativeAreas);
        });

        marker.addListener('click', () => {
            const markerPos = marker.getPosition().toJSON();
            mapViewerStore.setBounds(markerPos, markerPos);
        });

        mapViewerStore.setMarkers([marker]);
        setAdministrativeAreas(administrativeAreas);
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
                        onChange={([option]) => setEventType(option.value.id)}
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
                        onChange={([option]) => setMinimalLevel(option.value)}
                    />
                    <SelectBox
                        dark
                        label="Płeć"
                        options={[
                            { text: 'Brak podziału', value: 0, isSelected: true },
                            { text: 'Kobiety', value: 1 },
                            { text: 'Mężczyźni', value: 2 }
                        ]}
                        onChange={([option]) => setEventType(option.value)}
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
            <Separator />
            <Section title="Uczestnicy" titleAlign="right" titleSize={15}>
                uczestnicy
            </Section>
            <Separator />
            <Section title="Pozostałe" titleAlign="right" titleSize={15}>
                <div className={styles.restSection}>
                    <Input ref={startDateRef} type="file" label="Zdjęcie wydarzenia" />
                    <Input ref={startDateRef} type="file" label="Zdjęcie w tle" />
                    <Textarea label="Opis" value="opis" style={{ gridColumn: 'span 2' }} />
                </div>
            </Section>
            <div style={{ marginTop: '20px', float: 'right' }}>
                <OrangeButton
                    onClick={() => { console.log('create'); }}
                >
                    Dodaj ogłoszenie
                </OrangeButton>
            </div>
        </View>
    );
});

export default CreateEventView;