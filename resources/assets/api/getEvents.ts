import { IComment } from '@/components/Comments/Comments';
import appStore from '@/store/AppStore';
import { IPoint } from '@/types/IPoint';
import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import { when } from 'mobx';
import { ISport } from './getSports';

export interface IGetEventsParams {
    id?: number;
    filters?: {
        search?: string;
        sportIds?: number[];
        sort?: 'minimum_skill_level_id' | 'start_date' | 'ticket_price'
        sortDir?: 'asc' | 'desc'
    }
}

const getEvents = async (params?: IGetEventsParams) => {
    await when(() => !!appStore.sports.length);
    await when(() => !!appStore.genders.length);

    const { id, filters = {} } = params ?? {};
    let entries: any[];

    if (!isNaN(id)) {
        const response = await axios.get(getApiUrl(`api/v1/announcements/${id}`));
        entries = [response?.data?.data];
    } else {
        const { sportIds, sort, sortDir = 'asc', search } = filters;

        const response = await axios.get(getApiUrl('api/v1/announcements'), {
            params: {
                in: sportIds && sportIds.length ? `sport_id,${sportIds.join(',')}` : undefined,
                search,
                sort: sort ? `${sort},${sortDir}` : undefined,
            }
        });

        entries = response?.data?.data;
    }

    return entries ? entries.map((entry: any) => {
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
            seats: announcement.announcementSeats?.map(s => {
                return {
                    id: s.id
                };
            }),
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
                imageUrl: partner.logos?.[0]?.filename,
                contactEmail: partner.contactEmail,
                telephone: partner.telephone,
                facebook: partner.facebook,
                instagram: partner.instagram,
                website: partner.website,
                isVerified: partner.verified,
                avarageRating: partner.avarageRating,
                ratingCounter: partner.ratingCounter
            } : null,
            participants: announcement.announcementParticipants ? announcement.announcementParticipants.map((participant: any) => {
                return {
                    id: participant.id,
                    fullName: participant.name,
                    // gender: appStore.genders.find(s => s.id == +participant.gender.id),
                    avatarUrl: participant.avatar?.[0]?.filename,
                    itsMe: !!participant.itsMe,
                    seatId: +participant.announcementSeat.id
                    //TODO status
                };
            }) : [],
            comments: announcement.comments ? announcement.comments.map((comment: any) => {
                return {
                    username: comment.user.name,
                    userAvatarUrl: comment.user.avatar[0]?.filename,
                    createdAt: new Date(comment.date).toLocaleDateString(),
                    comment: comment.comment,
                    comments: []
                };
            }) : []
        };
        return event;
    }) : [] as IEvent[];
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
    seats: {
        id: number;
    }[]
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
        itsMe: boolean;
        seatId: number;
        //itsme
        //status
    }[];
    comments: IComment[];
    //TODO reszta pól
}

interface IPartner {
    id: number;
    fullName: string;
    imageUrl: string;
    contactEmail: string;
    telephone: string;
    facebook: string;
    instagram: string;
    website: string;
    isVerified: boolean;
    avarageRating: number;
    ratingCounter: number;
}