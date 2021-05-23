import React from 'react';
import styles from './Map.scss?module';
import darkTheme from './themes/dark-theme';
import {
    withScriptjs,
    withGoogleMap,
    GoogleMap,
    Marker
} from 'react-google-maps';

const MapContainer = withScriptjs(withGoogleMap(props => (
    <GoogleMap
        defaultZoom={8}
        defaultCenter={{ lat: 52.4006553, lng: 16.7615844 }}
        defaultOptions={{ styles: darkTheme }}
    >
        {props.isMarkerShown && <Marker position={{ lat: -34.397, lng: 150.644 }} />}
    </GoogleMap>
)));

const Map = props => {
    return (
        <MapContainer
            isMarkerShown
            googleMapURL="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y&sensor=false&libraries=visualization"
            loadingElement={<div style={{ height: '100%' }} />}
            containerElement={<div className={styles.container} />}
            mapElement={<div style={{ height: '100%' }} />}
        />
    );
};

export default Map;