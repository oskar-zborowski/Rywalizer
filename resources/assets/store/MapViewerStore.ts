import { IPoint } from '@/types/IPoint';
import chroma from 'chroma-js';
import { makeAutoObservable } from 'mobx';

export interface IEventPin extends IPoint {
    id: number;
    color: chroma.Color;
}

export class MapViewerStore {

    public eventPins: IEventPin[] = [];
    public markers: google.maps.Marker[] = [];
    public map: google.maps.Map;

    public constructor() {
        makeAutoObservable(this);
    }

    public reset() {
        this.markers.forEach(marker => marker.setMap(null));
        this.eventPins = [];
        this.markers = [];
    }

    public setEventPins(eventPins: IEventPin[]) {
        this.eventPins = eventPins;
    }

    public setMap(map: google.maps.Map) {
        this.map = map;
    }

    public setMarkers(markers: google.maps.Marker[]) {
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = markers;
        this.markers.forEach(marker => marker.setMap(this.map));
    }

    public addMarker(marker: google.maps.Marker) {
        this.markers.push(marker);
    }

    public setBounds(sw: IPoint, ne: IPoint) {
        this.map.fitBounds(new google.maps.LatLngBounds(sw, ne));
    }

}

const mapViewerStore = new MapViewerStore();
export default mapViewerStore;