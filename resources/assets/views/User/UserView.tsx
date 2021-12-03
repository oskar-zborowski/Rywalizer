import { BlackButton } from '@/components/form/Button/Button';
import useScrollbar from '@/components/MainContainer/Scrollbar/Scrollbar';
import React, { useEffect, useState } from 'react';
import styles from './UserView.scss';
import prof from '@/images/prof.png';
import axios from 'axios';

interface IUserData {
    firstName: string,
    lastName: string,
    email: string,
    avatar: string,
    birthDate: string,
    addressCoordinates: string,
    telephone: string,
    facebookProfile: string,
    lastLoggedIn: string,
    lastTimeNameChanged: string,
    lastTimePasswordChanged: string,
    genderType: {
        name: 'MALE' | 'FEMALE'
    },
    roleType: {
        name: 'ADMIN',
        accessLevel: '4'
    }
}

const UserView: React.FC = () => {
    // const { containerRef } = useScrollbar();
    const [data, setData] = useState<IUserData | null>(null);

    useEffect(() => {
        axios.get('/api/user').then(response => {
            setData(response.data.data.user);
        });
    }, []);

    console.log(data);

    return (
        <div className={styles.userView}>
            <header className={styles.header}>
                <span className={styles.title}>Moje konto</span>
                <BlackButton>Zostań partnerem</BlackButton>
            </header>
            <div className={styles.userDataWrapper}>
                <div className={styles.leftColumn}>
                    <img src={`/storage/avatars/${data?.avatar}`} alt="" className={styles.image} />
                </div>
                <div className={styles.rightColumn}>
                    <div className={styles.fieldName}>Imię i Nazwisko:</div>
                    <div className={styles.fieldValue}>{data?.firstName + ' ' + data?.lastName}</div>

                    <div className={styles.fieldName}>Płeć:</div>
                    <div className={styles.fieldValue}>{data?.genderType.name}</div>

                    <div className={styles.fieldName}>Urodziny:</div>
                    <div className={styles.fieldValue}>{data?.birthDate}</div>

                    <div className={styles.fieldName + ' ' + styles.withMargin}>E-mail:</div>
                    <div className={styles.fieldValue + ' ' + styles.withMargin}>{data?.email}</div>

                    <div className={styles.fieldName}>Telefon:</div>
                    <div className={styles.fieldValue}>{data?.telephone}</div>

                    <div className={styles.fieldName + ' ' + styles.withMargin}>Hasło:</div>
                    <div className={styles.fieldValue + ' ' + styles.withMargin}></div>

                    <div className={styles.fieldName}>Usuń konto:</div>
                    <div className={styles.fieldValue}></div>
                </div>
            </div>
        </div>
    );
};

export default UserView;