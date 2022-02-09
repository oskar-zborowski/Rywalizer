import getGenders, { IGender } from '@/api/getGenders';
import getSports, { ISport } from '@/api/getSports';
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
        const genders = await getGenders();

        runInAction(() => {
            this.genders = genders;
        });
    }

    public async fetchSports() {
        const sports = await getSports();

        runInAction(() => {
            this.sports = sports;
        });
    }

}

const appStore = new AppStore();
export default appStore;