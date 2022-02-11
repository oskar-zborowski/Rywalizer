import { GoogleMap } from '@react-google-maps/api';
import Component, { el } from '../../../components/Component';
import mapStyle from './mapStyle';
import styles from './MapViewer.scss';

// const polandCenter: IPoint = {
//     lat: 51.919438,
//     lng: 19.145136
// };

// const eventPinsLayer = new EventPinsLayer();

// const MapViewer: React.FC = () => {
//     const { isLoaded } = useJsApiLoader({
//         id: 'google-map-script',
//         googleMapsApiKey: 'AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y'
//     });

//     const navigateTo = useNavigate();

//     const onLoad = useCallback((map) => {
//         mapViewerStore.setMap(map);
//         eventPinsLayer.initialize(map, pin => navigateTo(`/ogloszenia/${pin.id}`));
//         // markersLayer.initialize(map);
//         // const pins: IEventPin[] = [];

//         // for (let i = 0; i < 10000; i++) {
//         //     const angle = (Math.random() * 4 * 360) * Math.PI / 180;
//         //     const radius = Math.random() * 3;
//         //     const lat = polandCenter.lat + (radius * Math.sin(angle)) / 1.6;
//         //     const lng = polandCenter.lng + radius * Math.cos(angle);
//         //     const color = chroma.random();

//         //     pins.push({ id: 1, lat, lng, color });
//         // }

//         // mapViewerStore.setEventPins(pins);
//     }, []);

//     return isLoaded ? (
//         <div className={styles.mapViewer}>
//             <GoogleMap
//                 mapContainerStyle={{ width: '100%', height: '100%' }}
//                 center={polandCenter}
//                 zoom={6}
//                 onLoad={onLoad}
//                 options={{ 
//                     disableDefaultUI: true, 
//                     styles: mapStyle,
//                     draggableCursor: 'crosshair',
//                     draggingCursor: 'crosshair'
//                 }}
//             />
//         </div>
//     ) : null;
// };

// export default memo(MapViewer);

export default class MapViewer extends Component {

    public constructor() {
        super();

        this._vdom = el(`div.${styles.mapViewer}`);
    }
}