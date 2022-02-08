import mapViewerStore, { IEventPin } from '@/store/MapViewerStore';
import { GoogleMapsOverlay as DeckGL } from '@deck.gl/google-maps';
import { ScatterplotLayer } from '@deck.gl/layers';
import { autorun } from 'mobx';

export default class EventPinsLayer {

    private readonly deckgl: DeckGL;
    private map: google.maps.Map;
    private pointsLayer: ScatterplotLayer<IEventPin> = null;

    public constructor() {
        this.deckgl = new DeckGL({});

        autorun(() => {
            const eventPins = mapViewerStore.eventPins;

            this.pointsLayer = new ScatterplotLayer<IEventPin>({
                id: 'scatter-plot',
                data: eventPins,
                radiusMinPixels: 5,
                radiusMaxPixels: 15,
                getRadius: 15,
                pickable: true,
                getPosition: p => [p.lng, p.lat],
                getFillColor: p => p.color.rgb(),
                onClick: (e) => {
                    alert(JSON.stringify(e.object));
                }
            });

            this.render();
        });
    }

    public initialize(map: google.maps.Map): void {
        this.map = map;
        this.map.setOptions({
            draggableCursor: 'crosshair',
            draggingCursor: 'crosshair'
        });

        this.deckgl.setMap(map);
        this.render();
    }

    public render() {
        this.deckgl.setProps({ layers: [this.pointsLayer] });
    }

}