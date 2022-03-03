import { IGender } from '@/api/getGenders';
import { IPoint } from '@/types/IPoint';
import { Permission } from '@/types/Permission';
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

    public async verifyEmail(token: string) {
        const response = await axios.put('/api/v1/user/email', {token});
        const user = this.prepareUserData(response.data.data);
        runInAction(() => this.user = user);
    }

    public async remindPassword(email: string) {
        const response = await axios.post(getApiUrl('api/v1/account/password'), { email });
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

        if (!coords.lat || !coords.lng) {
            coords = null;
        }

        return {
            id: responseData.user.id,
            firstName: responseData.user.firstName,
            lastName: responseData.user.lastName,
            avatarId: responseData.user.avatars?.[0].id,
            avatarUrl: responseData.user.avatars?.[0].filename,
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
            isEmailVerified: responseData.user.isEmailVerified,
            canChangeName: responseData.user.canChangeName,
            permissions: responseData.user.permissions,
            settings: responseData.userSettings,
            isPartner: responseData.user.isPartner
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

export interface IUser {
    id: number;
    firstName: string;
    lastName: string;
    avatarId: number;
    avatarUrl: string;
    avatarUrls: string[];
    email: string;
    phoneNumber: string;
    birthDate: string;
    gender: IGender;
    role: 'USER',
    city: string;
    addressCoordinates: IPoint;
    facebookProfile: string;
    instagramProfile: string;
    website: string;
    isEmailVerified: boolean;
    isVerified: boolean;
    canChangeName: boolean;
    permissions: Permission[];
    isPartner: boolean;
    settings: {
        isVisibleInComments: boolean
    }
}

const userStore = new UserStore();
export default userStore;