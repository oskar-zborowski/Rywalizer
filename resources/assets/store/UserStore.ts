import { IUser } from '@/types/IUser';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import { makeAutoObservable, runInAction } from 'mobx';

export class UserStore {

    public user: IUser;

    public constructor() {
        makeAutoObservable(this);
    }

    public async getUser() {
        try {
            const response = await axios.get(getApiUrl('api/v1/user'));
            const user = this.prepareUserData(response.data.data);
            runInAction(() => this.user = user);
        } catch (err) {
            console.error(err);
        }
    }

    public async login(login: string, password: string) {
        const response = await axios.post(getApiUrl('api/v1/auth/login'), {
            email: login,
            password: password
        });

        const user = this.prepareUserData(response.data.data);
        runInAction(() => this.user = user);

        return user;
    }

    public async logout() {
        await axios.delete(getApiUrl('api/v1/auth/logout'));

        runInAction(() => this.user = null);
    }

    public async register(data: IRegisterData) {
        const response = await axios.post(getApiUrl('api/v1/auth/register'), data);
        const user = this.prepareUserData(response.data.data);
        runInAction(() => this.user = user);

        return user;
    }

    public async remindPassword(email: string) {
        const response = await axios.post(getApiUrl('api/v1/account/password'), { email });

        console.log(response);
    }

    public async resetPassword(password: string, passwordConfirmation: string, token: string) {
        const response = await axios.put(getApiUrl('api/v1/account/password'), {
            password,
            passwordConfirmation,
            token
            //TODO checkbox z wylogowywwaniem ze wszystkich urządzen
        });

        console.log(response);
    }

    private prepareUserData(responseData: any): IUser {
        let coords = responseData.user.addressCoordinates;

        if (!responseData.user.addressCoordinates.lat || responseData.user.addressCoordinates.lng) {
            coords = null;
        }

        return {
            id: responseData.user.id,
            firstName: responseData.user.firstName,
            lastName: responseData.user.lastName,
            avatarUrl: responseData.user.avatars?.[0],
            avatarUrls: responseData.user.avatars,
            email: responseData.user.email,
            phoneNumber: responseData.user.telephone,
            birthDate: responseData.user.birthDate,
            gender: responseData.user.gender ? {
                id: responseData.user.gender.id,
                name: responseData.user.gender.descriptionSimple,
                iconUrl: null //TODO
            } : null,
            role: responseData.user.role,
            city: responseData.user.city,
            addressCoordinates: coords,
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