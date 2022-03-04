import { IPoint } from '@/types/IPoint';
import axios from 'axios';
import getAdministrativeAreas, { AdministrativeAreaType } from './getAdministrativeAreas';

const editUser = async (args: IEditUserArgs, imageFile: File): Promise<IEditUserResult> => {
    const body = args as any;

    if (args.administrativeAreas && args.addressCoordinates) {
        const city = await getAdministrativeAreas(
            args.administrativeAreas[3],
            AdministrativeAreaType.CITY
        );

        if (city?.[0]) {
            body.cityId = city[0].id;
        } else {
            body.countryName = 'Polska';
            body.voivodeshipName = args.administrativeAreas[0];
            body.poviatName = args.administrativeAreas[1] ?? 'Domyślny powiat';
            body.communeName = args.administrativeAreas[2] ?? 'Domyślna gmina';
            body.cityName = args.administrativeAreas[3];
        }

        const { lat, lng } = args.addressCoordinates;
        body.addressCoordinates = lat.toFixed(7) + ';' + lng.toFixed(7);
    }

    await axios.patch('/api/v1/user', body);

    if (imageFile) {
        const formData = new FormData();
        formData.append('avatar', imageFile);
        const response = await axios.post('/api/v1/user/avatar', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        const avatar = response.data?.data?.user?.avatars?.[0];

        return {
            avatarId: avatar.id,
            avatarUrl: avatar.filename
        };
    }

    return {};
};

export default editUser;

export interface IEditUserArgs {
    email: string;
    firstName?: string;
    lastName?: string;
    telephone?: string;
    birthDate?: string;
    addressCoordinates?: IPoint;
    facebookProfile?: string;
    instagramProfile?: string;
    website?: string;
    genderId?: number;
    password?: string;
    passwordConfirmation?: string;
    administrativeAreas: string[];
}

export interface IEditUserResult {
    avatarId?: number,
    avatarUrl?: string
}