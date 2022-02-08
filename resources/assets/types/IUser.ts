import { IGender } from './IGender';
import { Permission } from './Permission';

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