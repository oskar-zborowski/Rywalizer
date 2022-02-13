import { IGender } from '@/api/getGenders';
import { IPoint } from './IPoint';
import { Permission } from './Permission';

export interface IUser {
    id: number;
    firstName: string;
    lastName: string;
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
    isVerified: boolean;
    canChangeName: boolean;
    permissions: Permission[];
    settings: {
        isVisibleInComments: boolean
    }
}