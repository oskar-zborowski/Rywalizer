import axios from 'axios';
import { makeAutoObservable, runInAction } from 'mobx';

export class UserStore {

    public user: IUser = null;

    public constructor() {
        makeAutoObservable(this);
    }

    public async getUser() {
        await axios.get('api/v1/user');
    }

    public async login(login: string, password: string) {
        const user = await axios.post('api/v1/auth/login', {
            email: login,
            password: password
        });

        runInAction(() => {
            this.user = user.data as IUser;
        });

        console.log(user.data);
    }

    public async logout() {
        await axios.delete('api/v1/auth/logout');
    }

    public async register(data: IRegisterData) {
        const user = await axios.post('api/v1/auth/register', data);

        console.log(user.data);
    }

}

export interface IRegisterData {
    firstName: string;
    lastName: string;
    birthDate: string;
    genderId: number;
    email: string;
    password: string;
    passwordConfirmation: string;
    acceptedAgreements: number[]
}

// "user": {
//     "id": "1",
//     "firstName": "Oskar",
//     "lastName": "Zborowski",
//     "avatars": null,
//     "email": "oskarzborowski@gmail.com",
//     "telephone": null,
//     "birthDate": "02.11.1998",
//     "gender": {
//       "name": "MALE",
//       "descriptionSimple": "Mężczyzna",
//       "icon": "male-icon.png"
//     },
//     "role": "USER",
//     "city": null,
//     "addressCoordinates": null,
//     "facebookProfile": null,
//     "instagramProfile": null,
//     "website": null,
//     "isVerified": false,
//     "canChangeName": true,
//     "permissions": null
//   },
//   "userSetting": {
//     "isVisibleInComments": true
//   }

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