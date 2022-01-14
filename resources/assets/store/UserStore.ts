import axios from 'axios';
import { makeAutoObservable, runInAction } from 'mobx';

export class UserStore {

    public user: IUser = null;

    public constructor() {
        makeAutoObservable(this);
    }

    public async login(login: string, password: string) {
        const user = await axios.post('/api/login', {
            email: login,
            password: password
        });

        runInAction(() => {
            this.user = user.data as IUser;
        });

        console.log(user.data);
    }

    public async register(data: IRegisterData) {
        //TODO;
    }

}

export interface IRegisterData {
    firstname: string;
    lastname: string;
    birthDate: string;
    gender: string;
    email: string
    password: string
    confirmPassword: string
}

interface IUser {
    firstName: string,
    lastName: string,
    email: string,
    avatar: string,
    birthDate: string,
    addressCoordinates: string,
    telephone: string,
    facebookProfile: string,
    lastLoggedIn: string,
    lastTimeNameChanged: string,
    lastTimePasswordChanged: string,
    genderType: {
        name: 'MALE' | 'FEMALE'
    },
    roleType: {
        name: 'ADMIN',
        accessLevel: '4'
    }
}

const userStore = new UserStore();
export default userStore;