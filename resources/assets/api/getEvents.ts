import appStore from '@/store/AppStore';
import { IPoint } from '@/types/IPoint';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import { when } from 'mobx';
import { IGender } from './getGenders';
import { IPartner } from './getPartners';
import { ISport } from './getSports';

export interface IGetEventsParams {
    id?: number;
    filters?: {
        sport?: number;
    }
}

const getEvents = async (params?: IGetEventsParams) => {
    await when(() => !!appStore.sports.length);
    await when(() => !!appStore.genders.length);

    const { id, ...queryParams } = params ?? {};
    let entries: any[];

    if (!isNaN(id)) {
        const response = await axios.get(getApiUrl(`api/v1/announcements/${id}`));
        entries = [response?.data?.data];
    } else {
        const response = await axios.get(getApiUrl('api/v1/announcements'), { params: queryParams });
        entries = response?.data?.data;
    }

    return entries.map((entry: any) => {
        const announcement = entry.announcement;
        const facility = entry.facility;
        const partner = entry.partner?.partner;

        const event: IEvent = {
            id: +announcement.id,
            sport: appStore.sports.find(s => s.id == +announcement.sport.id),
            startDate: new Date(announcement.startDate),
            endDate: new Date(announcement.endDate),
            ticketPrice: +announcement.ticketPrice,
            minSkillLevelId: +announcement.minimumSkillLevel,
            minAge: +announcement.minimalAge,
            maxAge: +announcement.maximumAge,
            description: announcement.description,
            soldTicketsCount: +announcement.participantsCounter,
            availableTicketsCount: +announcement.maximumParticipantsNumber,
            isPublic: !!announcement.isPublic,
            imageUrl: announcement.frontImage?.[0]?.filename,
            backgroundImageUrl: announcement.backgroundImage,
            facility: facility ? {
                id: +facility.id,
                name: facility.name,
                street: facility.street,
                city: {
                    id: +facility.city?.id,
                    name: facility.city?.name
                },
                location: {
                    lat: +facility.addressCoordinates.lat,
                    lng: +facility.addressCoordinates.lng
                }
            } : null,
            partner: partner ? {
                id: partner.id,
                fullName: partner.name,
                logos: [],
                contactEmail: partner.contactEmail,
                telephone: partner.telephone,
                facebook: partner.facebook,
                instagram: partner.instagram,
                website: partner.website,
                isVerified: partner.verified,
                avarageRating: partner.avarageRating,
                ratingCounter: partner.ratingCounter
            } : null,
            participants: announcement.announcementParticipants?.map((participant: any) => {
                return {
                    id: participant.id,
                    fullName: participant.name,
                    // gender: appStore.genders.find(s => s.id == +participant.gender.id),
                    avatarUrl: participant.avatar
                };
            })
        };

        return event;
    }) as IEvent[];
};

export default getEvents;

export interface IEvent {
    id: number,
    sport: ISport,
    partner: IPartner;
    startDate: Date,
    endDate: Date,
    ticketPrice: number,
    // gameVariant: {
    //     id: 77,
    //     name: 'STANDARD'
    // },
    minSkillLevelId: number,
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
    backgroundImageUrl: string;
    facility: {
        id: number;
        name: string;
        street: string;
        city: {
            id: number;
            name: string;
        },
        location: IPoint;
    };
    participants: {
        id: number;
        fullName: string;
        // gender: IGender; //TODO potrzebny idk
        avatarUrl: string;
        //itsme
        //status
    }[]
    //TODO reszta pól
}