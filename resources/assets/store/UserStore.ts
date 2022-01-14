import axios from 'axios';
import { makeAutoObservable, runInAction } from 'mobx';

export class UserStore {

    public user: IUser = null;

    public constructor() {
        makeAutoObservable(this);
    }

    public async getUser() {
        const response = await axios.get('api/v1/user');
        const user = this.prepareUserData(response.data);
        runInAction(() => this.user = user);

        return user;
    }

    public async login(login: string, password: string) {
        const response = await axios.post('api/v1/auth/login', {
            email: login,
            password: password
        });

        const user = this.prepareUserData(response.data);
        runInAction(() => this.user = user);

        return user;
    }

    public async logout() {
        await axios.delete('api/v1/auth/logout');

        runInAction(() => this.user = null);
    }

    public async register(data: IRegisterData) {
        const response = await axios.post('api/v1/auth/register', data);
        const user = this.prepareUserData(response.data);
        runInAction(() => this.user = user);

        return user;
    }

    private prepareUserData(responseData: any): IUser {
        return {
            id: responseData.user.id,
            firstName: responseData.user.firstName,
            lastName: responseData.user.lastName,
            avatarUrls: responseData.user.avatars,
            email: responseData.user.email,
            phoneNumber: responseData.user.telephone,
            birthDate: responseData.user.birthDate,
            gender: responseData.user.gender,
            role: responseData.user.role,
            city: responseData.user.city,
            addressCoordinates: responseData.user.addressCoordinates,
            facebookProfile: responseData.user.facebookProfile,
            instagramProfile: responseData.user.instagramProfile,
            website: responseData.user.website,
            isVerified: responseData.user.isVerified,
            canChangeName: responseData.user.canChangeName,
            permissions: responseData.user.permissions,
            settings: responseData.userSettings
        };
    }

}

export enum Permission {
    //TODO
}

export interface IGender {
    name: string;
    descriptionSimple: string;
    iconUrl: string;
}

export interface IUser {
    id: number;
    firstName: string;
    lastName: string;
    avatarUrls: string[];
    email: string;
    phoneNumber: string;
    birthDate: string;
    gender: IGender;
    role: 'USER',
    city: string;
    addressCoordinates: string;
    facebookProfile: string;
    instagramProfile: string;
    website: string;
    isVerified: boolean;
    canChangeName: boolean;
    permissions: Permission[];
    settings: {
        isVisibleInComments: boolean
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

const userStore = new UserStore();
export default userStore;