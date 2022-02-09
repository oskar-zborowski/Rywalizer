import geocode from '@/api/geocode';
import { ISport } from '@/api/getSports';
import Input from '@/components/Form/Input/Input';
import Selectbox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import Section from '@/components/Section/Section';
import Separator from '@/components/Separator/Separator';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import chroma from 'chroma-js';
import React, { createRef, forwardRef, useRef } from 'react';
import View from '../View/View';
import styles from './CreateEventView.scss';

const CreateEventView: React.FC = () => {
    const sportOptions: IOption<ISport>[] = appStore.sports.map(s => {
        return {
            value: s,
            text: s.name
        } as IOption<ISport>;
    });

    const addressRef = useRef<HTMLInputElement>();
    const findAddressCoords = async (address: string) => {
        const { location } = await geocode(address);

        mapViewerStore.setEventPins([{
            ...location, id: 0, color: chroma('pink')
        }]);
    };

    return (
        <View withBackground title="Dodaj Ogłoszenie">
            <Section title="Dane podstawowe">
                <div className={styles.locationSection}>
                    <Selectbox dark label="Sport" initialOptions={sportOptions} />
                    <Input type="date" label="Data rozpoczęcia" />
                    <Input type="date" label="Data zakończenia" />
                    <Input label="Cena" />
                    <Selectbox dark label="Rodzaj płatności" />
                    <Selectbox dark label="Wariant gry" />
                    <Selectbox dark label="Typ ogłoszenia" />
                    <Selectbox dark label="Ogłoszenie publiczne" />
                </div>
            </Section>
            <Separator />
            <Section title="Lokalizacja">
                <div className={styles.locationSection}>
                    <Input label="Nazwa obiektu" style={{ gridColumn: 'span 2' }} />
                    <Input
                        ref={addressRef}
                        label="Adres"
                        style={{ gridColumn: 'span 2' }}
                        onBlur={() => findAddressCoords(addressRef.current.value)}
                    />
                    <Selectbox dark label="Miasto" />
                    <Selectbox dark label="Gmina" />
                    <Selectbox dark label="Powiat" />
                    <Selectbox dark label="Województwo" />
                </div>
            </Section>
            <Separator />
            <Section title="Wymagania">
                <div className={styles.locationSection}>
                    <Selectbox dark label="Minimalny poziom" />
                    <Selectbox dark label="Płeć" />
                    <Selectbox dark label="Kategoria wiekowa" />
                    <Input label="Minimalny wiek" />
                    <Input label="Maksymalny wiek" />
                </div>
            </Section>
            <Separator />
            <Section title="Uczestnicy">
                uczestnicy
            </Section>
            <Separator />
            <Section title="Pozostałe">
                TODO wyjaśnić<br />
                * od kiedy widoczne w serwisie: datetime<br />
                * automatyczne zatwierdzanie: select<br />
                * publiczne: select<br />
                * zdjęcie w tle: file<br />
                * opis: textarea
            </Section>
        </View>
    );
};

export default CreateEventView;