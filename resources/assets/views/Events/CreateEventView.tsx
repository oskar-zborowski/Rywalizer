import geocode from '@/api/geocode';
import { ISport } from '@/api/getSports';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Selectbox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import Textarea from '@/components/Form/Textarea/Textarea';
import Section from '@/components/Section/Section';
import Separator from '@/components/Separator/Separator';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import React, { useEffect, useRef, useState } from 'react';
import View from '../View/View';
import styles from './CreateEventView.scss';

const CreateEventView: React.FC = () => {
    useEffect(() => {
        findAddressCoords('Poznań');
        mapViewerStore.reset();
    }, []);

    const [administrativeAreas, setAdministrativeAreas] = useState<string[]>([]);
    const startDateRef = useRef<HTMLInputElement>();
    const endDateRef = useRef<HTMLInputElement>();
    const priceRef = useRef<HTMLInputElement>();
    const objectNameRef = useRef<HTMLInputElement>();
    const addressRef = useRef<HTMLInputElement>();

    const sportOptions: IOption<ISport>[] = appStore.sports.map(s => {
        return {
            value: s,
            text: s.name
        } as IOption<ISport>;
    });

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
                    <Selectbox dark label="Sport" options={sportOptions} />
                    <Input ref={startDateRef} type="date" label="Data rozpoczęcia" />
                    <Input ref={endDateRef} type="date" label="Data zakończenia" />
                    <Input ref={priceRef} label="Cena" />
                    <Selectbox dark label="Wariant gry" />
                    <Selectbox dark label="Ogłoszenie publiczne" />
                    <Selectbox dark label="Minimalny poziom" />
                    <Selectbox dark label="Płeć" />
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
                <OrangeButton>Dodaj ogłoszenie</OrangeButton>
            </div>
        </View>
    );
};

export default CreateEventView;