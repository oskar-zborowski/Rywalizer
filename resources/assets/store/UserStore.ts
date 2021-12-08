import axios from 'axios';
import { makeAutoObservable } from 'mobx';

export class UserStore {

    public user: IUser = null;

    public constructor() {
        makeAutoObservable(this);
    }

    public async login(login: string, password: string) {
        await axios.post('/api/login', {
            email: login,
            password: password
        });

        console.log(await axios.get('/api/user'));
    }

    public register() {
        //TODO;
    }

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