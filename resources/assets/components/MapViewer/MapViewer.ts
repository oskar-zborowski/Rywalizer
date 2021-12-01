import Component, { el } from '@/app/Component';
import styles from './MapViewer.scss';
import mapStyle from './mapStyle';

export class MapViewer extends Component {

    private _map: google.maps.Map;

    public constructor() {
        super();
    }

    protected render(): JQuery {
        const container = $(document.createElement('div')).addClass(styles.mapViewer);

        this._map = new google.maps.Map(container.get(0), {
            // center: MapViewer.POLAND_CENTER,
            zoom: 6,
            controlSize: 24,
            styles: mapStyle,
            draggableCursor: 'crosshair',
            draggingCursor: 'crosshair',
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            },
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP
            }
        });

        return container;
    }

}