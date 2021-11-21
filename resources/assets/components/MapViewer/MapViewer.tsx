import React, { memo, useCallback, useState } from 'react';
import { GoogleMap, useJsApiLoader } from '@react-google-maps/api';
import styles from './MapViewer.scss';
import mapStyle from './mapStyle';

const center = {
    lat: 52,
    lng: 20
};

const MapViewer: React.FC = () => {
    const { isLoaded } = useJsApiLoader({
        id: 'google-map-script',
        googleMapsApiKey: 'AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y'
    });

    const [map, setMap] = useState(null);

    const onLoad = useCallback((map) => {
        // const bounds = new window.google.maps.LatLngBounds();
        // map.fitBounds(bounds);
        setMap(map);
    }, []);

    const onUnmount = useCallback((map) => {
        setMap(null);
    }, []);

    return isLoaded ? (
        <div className={styles.mapViewer}>
            <GoogleMap
                mapContainerStyle={{ width: '100%', height: '100%' }}
                center={center}
                zoom={6}
                onLoad={onLoad}
                onUnmount={onUnmount}
                options={{ disableDefaultUI: true, styles: mapStyle }}
            >
                { /* Child components, such as markers, info windows, etc. */}
                <></>
            </GoogleMap>
        </div>
    ) : <></>;
};

export default memo(MapViewer);