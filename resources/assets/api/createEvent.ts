import { IPoint } from '@/types/IPoint';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const createEvent = async (args: ICreateEventArgs) => {
    const areas = 

    await axios.post(getApiUrl('/api/v1/announcements'), {
        facilityId: args.facility.id,
        facilityName: args.facility.name,
        facilityStreetAddress: args.facility.address,
        facilityAddressCoordinates: args.facility.coords.lat + ',' + args.facility.coords.lng,
        sportId: args.sportId,
        startDate: args.startDate.toDateString(),
        endDate: args.endDate.toDateString(),
        ticketPrice: args.ticketPrice,
        gameVariantId: 77, //TODO
        genderId: 9, //TODO
        minimumSkillLevelId: args.minimumSkillLevelId,
        description: args.description,
        isPublic: args.isPublic,
        sportPositions: [] //TODO
    });
};

export interface ICreateEventArgs {
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
    facility: {
        id: number;
        name: string;
        address: string;
        coords: IPoint;
    }
}