// @ts-nocheck
import React from 'react';
import darkTheme from './theme';
import {
    withScriptjs,
    withGoogleMap,
    GoogleMap,
    Marker
} from 'react-google-maps';

// @ts-ignore
import styles from './Map.scss?module';

const MapContainer = withScriptjs(withGoogleMap(props => (
    <GoogleMap
        defaultZoom={8}
        defaultCenter={{ lat: 52.4006553, lng: 16.7615844 }}
        defaultOptions={{ 
            styles: darkTheme,
            disableDefaultUI: true,
        }}
    >
        {props.isMarkerShown && <Marker position={{ lat: -34.397, lng: 150.644 }} />}
    </GoogleMap>
)));

const Map = props => {
    return (
        <MapContainer
            isMarkerShown
            googleMapURL="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y&sensor=false&libraries=visualization"
            containerElement={<div className={styles.container} />}
            loadingElement={<div style={{ height: '100%' }} />}
            mapElement={<div style={{ height: '100%' }} />}
        />
    );
};

export default Map;