import { IPoint } from '@/types/IPoint';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import getAdministrativeAreas, { AdministrativeAreaType } from './getAdministrativeAreas';
import moment from 'moment';

const saveEvent = async (args: ISaveEventArgs, photoFile?: File, eventId?: number) => {
    const { lat, lng } = args.facility.coords;

    const body = {
        sportId: args.sportId,
        startDate: moment(args.startDate).format('YYYY-MM-DD HH:mm:ss'),
        endDate: moment(args.endDate).format('YYYY-MM-DD HH:mm:ss'),
        ticketPrice: +args.ticketPrice * 100,
        gameVariantId: args.gameVariantId,
        genderId: args.genderId,
        minimumSkillLevelId: args.minimumSkillLevelId,
        description: args.description,
        isPublic: args.isPublic,
        facilityId: args.facility?.id,
        facilityName: args.facility?.name,
        facilityStreet: args.facility?.street,
        facilityAddressCoordinates: lat.toFixed(7) + ';' + lng.toFixed(7),
        announcementStatusId: 85,
        sportsPositions: [
            {
                sportsPositionId: 6,
                maximumSeatsNumber: args.availableTicketsCount
            }
        ]
    } as any;

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

    if (eventId) {
        await axios.patch(getApiUrl(`/api/v1/announcements/${eventId}`), body);
    } else {
        const response = await axios.post(getApiUrl('/api/v1/announcements'), body);
        eventId = +response?.data?.data?.announcement?.id;
    }

    if (eventId && photoFile) {
        const formData = new FormData();
        formData.append('photo', photoFile);
        const response = await axios.post(getApiUrl(`/api/v1/announcements/${eventId}/photos`), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    return eventId;
};

export default saveEvent;

export interface ISaveEventArgs {
    administrativeAreas: string[];
    sportId: number;
    startDate: Date;
    endDate: Date;
    ticketPrice: number;
    gameVariantId: number;
    genderId: number;
    minimumSkillLevelId: number;
    description: string;
    isPublic: boolean;
    availableTicketsCount: number;
    facility: {
        id?: number;
        name?: string;
        street: string;
        coords: IPoint;
    }
}