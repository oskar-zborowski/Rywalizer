import appStore from '@/store/AppStore';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import { when } from 'mobx';
import { ISport } from './getSports';

export interface IGetEventsParams {
    id?: number;
}

const getEvents = async (params?: IGetEventsParams) => {
    await when(() => !!appStore.sports.length);
    await when(() => !!appStore.genders.length);

    const { id, ...queryParams } = params ?? {};
    let entries: any[];

    if (!isNaN(id)) {
        const response = await axios.get(getApiUrl(`api/v1/announcement/${id}`));
        entries = [response?.data?.data];
    } else {
        const response = await axios.get(getApiUrl('api/v1/announcements'), { params: queryParams });
        entries = response?.data?.data;
    }

    return entries.map((entry: any) => {
        const announcement = entry.announcement;
        const facility = entry.facility;
        const event: IEvent = {
            id: +announcement.id,
            sport: appStore.sports.find(s => s.id == +announcement.sport.id),
            startDate: new Date(announcement.startDate),
            endDate: new Date(announcement.endDate),
            ticketPrice: +announcement.ticketPrice,
            minSkillLevel: +announcement.minimumSkillLevel,
            minAge: +announcement.minimalAge,
            maxAge: +announcement.maximumAge,
            description: announcement.description,
            soldTicketsCount: +announcement.participantsCounter,
            availableTicketsCount: +announcement.maximumParticipantsNumber,
            isPublic: !!announcement.isPublic,
            // imageUrl: announcement.image,
            imageUrl: 'https://s4.tvp.pl/images2/4/f/1/uid_4f11ef64aa8450c6ab80d4409732bf131631783889434_width_1200_play_0_pos_0_gs_0_height_678_polska-slowenia-polfinal-me-siatkarzy-transmisja-na-zywo-online-fot-pap.jpg',
            facility: {
                id: +facility.id,
                name: facility.name,
                street: facility.street,
                city: {
                    id: +facility.city.id,
                    name: facility.city.name
                }
            }
        };

        return event;
    }) as IEvent[];
};

export default getEvents;

export interface IEvent {
    id: number,
    sport: ISport,
    startDate: Date,
    endDate: Date,
    ticketPrice: number,
    // gameVariant: {
    //     id: 77,
    //     name: 'STANDARD'
    // },
    minSkillLevel: number,
    // gender: null,
    // ageCategory: null,
    minAge: number,
    maxAge: number,
    description: string,
    soldTicketsCount: number,
    availableTicketsCount: number,
    // announcementType: null,
    // announcementStatus: {
    //     id: 85,
    //     name: 'ACTIVE'
    // },
    // isAutomaticallyApproved: '1',
    isPublic: boolean,
    imageUrl: string,
    facility: {
        id: number;
        name: string;
        street: string;
        city: {
            id: number;
            name: string;
        }
    };
    //TODO reszta pól
}