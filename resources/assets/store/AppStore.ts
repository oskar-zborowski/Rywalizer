import { IGender } from '@/types/IGender';
import { ISport } from '@/types/ISport';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import chroma from 'chroma-js';
import { makeAutoObservable, runInAction } from 'mobx';

export class AppStore {

    public genders: IGender[] = [];
    public sports: ISport[] = [];

    public constructor() {
        makeAutoObservable(this);
    }

    public fetchData() {
        this.fetchGenders();
        this.fetchSports();
    }

    public async fetchGenders() {
        const response = await axios.get(getApiUrl('api/v1/genders'));
        const genders = response?.data?.data?.gender;

        runInAction(() => {
            this.genders = genders?.map((g: any) => {
                return {
                    id: g.id,
                    name: g.descriptionSimple,
                    icon: g.icon
                } as IGender;
            });
        });
    }

    public async fetchSports() {
        const response = await axios.get(getApiUrl('api/v1/sports'));
        const sports = response?.data?.data?.sport;

        runInAction(() => {
            this.sports = sports.map((s: any) => {
                return {
                    id: s.id,
                    name: s.descriptionSimple,
                    icon: s.icon,
                    color: chroma(chroma.valid(s.color) ? s.color : '#000')
                } as ISport;
            });
        });
    }

}

const appStore = new AppStore();
export default appStore;