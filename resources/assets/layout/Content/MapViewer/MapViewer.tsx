import React, { memo, useCallback, useEffect, useState } from 'react';
import { GoogleMap, useJsApiLoader } from '@react-google-maps/api';
import styles from './MapViewer.scss';
import mapStyle from './mapStyle';
import EventPinsLayer from './EventPinsLayer';
import mapViewerStore, { IEventPin } from '@/store/MapViewerStore';
import { IPoint } from '@/types/IPoint';
import chroma from 'chroma-js';

const polandCenter: IPoint = {
    lat: 51.919438,
    lng: 19.145136
};

const eventPinsLayer = new EventPinsLayer();

const MapViewer: React.FC = () => {
    const { isLoaded } = useJsApiLoader({
        id: 'google-map-script',
        googleMapsApiKey: 'AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y'
    });

    const onLoad = useCallback((map) => {
        eventPinsLayer.initialize(map);

        const pins: IEventPin[] = [];

        for (let i = 0; i < 10000; i++) {
            const angle = (Math.random() * 4 * 360) * Math.PI / 180;
            const radius = angle ** 2 * 0.005;
            const lat = polandCenter.lat + (radius * Math.sin(angle)) / 1.6;
            const lng = polandCenter.lng + radius * Math.cos(angle);
            const color = chroma.random();

            pins.push({ lat, lng, color });
        }

        mapViewerStore.setEventPins(pins);
    }, []);

    return isLoaded ? (
        <div className={styles.mapViewer}>
            <GoogleMap
                mapContainerStyle={{ width: '100%', height: '100%' }}
                center={polandCenter}
                zoom={6}
                onLoad={onLoad}
                options={{ disableDefaultUI: true, styles: mapStyle }}
            />
        </div>
    ) : null;
};

export default memo(MapViewer);