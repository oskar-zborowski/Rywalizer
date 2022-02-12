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

    public async fetchData() {
        await this.fetchGenders();
        await this.fetchSports();
    }

    public async fetchGenders() {
        try {
            const genders = await getGenders();

            runInAction(() => {
                this.genders = genders;
            });
        } catch (e) {
            console.error(e);
        }
    }

    public async fetchSports() {
        try {
            const sports = await getSports();

            runInAction(() => {
                this.sports = sports;
            });
        } catch (e) {
            console.error(e);
        }
    }

}

const appStore = new AppStore();
export default appStore;