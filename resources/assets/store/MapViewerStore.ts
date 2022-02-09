import { IPoint } from '@/types/IPoint';
import chroma from 'chroma-js';
import { makeAutoObservable } from 'mobx';

export interface IEventPin extends IPoint {
    id: number;
    color: chroma.Color;
}

export class MapViewerStore {

    public eventPins: IEventPin[] = [];

    public constructor() {
        makeAutoObservable(this);
    }

    public setEventPins(eventPins: IEventPin[]): void {
        this.eventPins = eventPins;
    }

}

const mapViewerStore = new MapViewerStore();
export default mapViewerStore;