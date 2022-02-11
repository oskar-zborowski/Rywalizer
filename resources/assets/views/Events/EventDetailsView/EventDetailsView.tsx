
import StarRatings from '@/components/StarRating/StarRating';
import React, { Fragment, useEffect, useState } from 'react';
import styles from './EventDetailsView.scss';
import prof from '@/static/images/prof.png';
import Icon from '@/components/Icon/Icon';

import LocationSvg from '@/static/icons/location.svg';
import CalendarSvg from '@/static/icons/calendar.svg';
import UserSvg from '@/static/icons/my-account.svg';
import ContactSvg from '@/static/icons/food.svg';
import TelephoneSvg from '@/static/icons/telephone.svg';
import MailSvg from '@/static/icons/mail.svg';
import FacebookSvg from '@/static/icons/facebook.svg';
import Link from '@/components/Link/Link';
import faker from 'faker';
import Comments, { IComment } from '@/components/Comments/Comments';
import ProgressBar from '@/components/ProgressBar/ProgressBar';
import StackPanel from '@/components/StackPanel/StackPanel';
import { useParams } from 'react-router-dom';
import getEvents, { IEvent } from '@/api/getEvents';
import mapViewerStore from '@/store/MapViewerStore';
import View, { useView } from '@/views/View/View';

faker.locale = 'pl';

const comments: IComment[] = [
    {
        username: 'K. Borowicz',
        createdAt: '2 dzień temu',
        comment: 'Reasumując wszystkie aspekty kwintesencji tematu, dochodzę do fundamentalnej konkluzji, warto studiować.\n~ Mariusz pudzianowski',
        comments: [
            {
                username: 'O. Zborowski',
                createdAt: 'Przed chwilą',
                comment: 'To nie dałoby nic, nic by nie dało.\nhttps://www.youtube.com/watch?v=8AwVRlXsxlA&ab_channel=CACACACACA',
                comments: [
                    {
                        username: 'M. Pudzianowski',
                        createdAt: 'Przed chwilą',
                        comment: 'Odtworzyłem wczoraj po nocy oryginalną klasyfikację z tych zawodów. Policzyłem wszystko skrupulatnie i zrobiłem double check z regulaminem. Okazało się, że to i tak by nic nie dało.'
                    }, {
                        username: 'W. Mila',
                        createdAt: 'Przed chwilą',
                        comment: 'Skład rzeczy które i tak by nic nie dały:\n- wygrana bo on by musiał być 4 w kulach\n- mała różnica czasów\n- branie kuli od boku'
                    }
                ]
            }
        ]
    }, {
        username: 'B. Babiaczyk',
        createdAt: '1 dni temu',
        comment: 'Bez ryzyka nie ma gry.',
    }
];

const EventDetailsView: React.FC = (props) => {
    const { id } = useParams();
    const [event, setEvent] = useState<IEvent>(null);
    const isEventLoaded = event !== null;

    useEffect(() => {
        mapViewerStore.reset();
        getEvents({ id: +id }).then(events => {
            const event = events[0];
            setEvent(event);

            mapViewerStore.setEventPins([{
                id: event.id,
                color: event.sport.color,
                ...event.facility?.location
            }]);

            if (event.facility?.location) {
                const { lat, lng } = event.facility?.location;

                mapViewerStore.setBounds({
                    lat: lat - 0.1,
                    lng: lng - 0.1
                }, {
                    lat: lat + 0.1,
                    lng: lng + 0.1
                });
            }
        });
    }, []);

    const ParticipantsList = () => {
        return (
            <div className={styles.participantsListWrapper}>
                <div className={styles.participantsList}>
                    {
                        [...Array(++event.availableTicketsCount).keys()].map(i => {
                            const participant = event.participants?.[i];

                            if (participant) {
                                return <div className={styles.participantCell} key={i}>
                                    {i + 1}.&nbsp;<span>{participant.fullName}</span>
                                </div>;
                            } else {
                                return <div className={styles.participantCell + ' ' + styles.signUpCell} key={i}>
                                    {i + 1}.&nbsp;<span className={styles.signUp}>Zapisz się</span>
                                </div>;
                            }
                        })
                    }
                </div>
            </div>
        );
    };

    return (
        <View isLoaderVisible={!isEventLoaded}>
            {isEventLoaded && (
                <Fragment>
                    <header className={styles.header}>
                        {event.imageUrl && <img className={styles.backgroundImage} src={event.imageUrl} alt="" />}
                        <div className={styles.gradientOverlay}></div>
                        <div className={styles.userData}>
                            <img className={styles.userImage} src={prof} alt="" />
                            <div className={styles.userDetails}>
                                <div className={styles.userDetailsRow}>
                                    <h1>Organizator:</h1>
                                    <Icon svg={UserSvg}>{event.partner.fullName}</Icon>
                                    <StarRatings rating={event.partner.avarageRating} />
                                </div>

                                <div className={styles.userDetailsRow + ' ' + styles.contact}>
                                    <h1>Kontakt:</h1>
                                    <Icon svg={TelephoneSvg}>{event.partner.telephone ?? 'Nie podano'}</Icon>
                                    <Icon svg={MailSvg}>{event.partner.contactEmail ?? 'Nie podano'}</Icon>
                                    <Icon svg={FacebookSvg}>
                                        {event.partner.facebook ? (
                                            <Link href="https://www.facebook.com/groups/356092872309341">
                                                {event.partner.facebook}
                                            </Link>
                                        ) : (
                                            'Nie podano'
                                        )}
                                    </Icon>
                                </div>
                            </div>
                        </div>
                    </header>
                    <section className={styles.userDetailsRow + ' ' + styles.contactSM}>
                        <h1>Kontakt:</h1>
                        <Icon svg={TelephoneSvg}>{event.partner.telephone ?? 'Nie podano'}</Icon>
                        <Icon svg={MailSvg}>{event.partner.contactEmail ?? 'Nie podano'}</Icon>
                        <Icon svg={FacebookSvg}>
                            {event.partner.facebook ? (
                                <Link href="https://www.facebook.com/groups/356092872309341">
                                    {event.partner.facebook}
                                </Link>
                            ) : (
                                'Nie podano'
                            )}
                        </Icon>
                    </section>
                    <div className={styles.separator}></div>
                    <section>
                        <h1>Lista zapisanych:</h1>
                        <StackPanel padding="20px 0 20px 0" vertical>
                            <StackPanel>
                                <Icon svg={LocationSvg}>
                                    <b>{event.facility?.name}</b>,&nbsp;
                                    {event.facility?.city.name}&nbsp;{event.facility?.street}
                                </Icon>
                                <Icon svg={CalendarSvg}>28.10.2022, 15:00 - 16:30</Icon>
                            </StackPanel>
                            <ProgressBar progress={event.soldTicketsCount / event.availableTicketsCount * 100} />
                        </StackPanel>
                        <ParticipantsList />
                    </section>
                    <div className={styles.separator}></div>
                    <section>
                        <h1>Opis:</h1>
                        <div className={styles.description}>
                            {event.description ?? 'To wydarzenie nie posiada opisu'}
                        </div>
                    </section>
                    <div className={styles.separator}></div>
                    <section>
                        <h1>Komentarze:</h1>
                        <Comments comments={comments} />
                    </section>
                </Fragment>
            )}
        </View >
    );
};

export default EventDetailsView;