import mapViewerStore, { IEventPin } from '@/store/MapViewerStore';
import { GoogleMapsOverlay as DeckGL } from '@deck.gl/google-maps';
import { ScatterplotLayer } from '@deck.gl/layers';
import { autorun } from 'mobx';

export default class EventPinsLayer {

    private readonly deckgl: DeckGL;
    private map: google.maps.Map;
    private pointsLayer: ScatterplotLayer<IEventPin> = null;
    private onClick: (IEventPin: IEventPin) => void;

    public constructor() {
        this.deckgl = new DeckGL({});

        autorun(() => {
            const eventPins = mapViewerStore.eventPins;

            this.pointsLayer = new ScatterplotLayer<IEventPin>({
                id: 'scatter-plot',
                data: eventPins,
                radiusMinPixels: 10,
                radiusMaxPixels: 10,
                getRadius: 10,
                pickable: true,
                stroked: true,
                lineWidthMinPixels: 1,
                lineWidthMaxPixels: 1,
                getLineWidth: 1,
                getLineColor: [0, 0, 0, 128],
                getPosition: p => [p.lng, p.lat],
                getFillColor: p => p.color.rgb(),
                onClick: (e) => this.onClick?.(e.object)
            });

            this.render();
        });
    }

    public initialize(map: google.maps.Map, onClick: (IEventPin: IEventPin) => void): void {
        this.map = map;
        this.onClick = onClick;

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