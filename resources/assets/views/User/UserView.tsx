import { BlackButton } from '@/components/form/Button/Button';
import { UserStore } from '@/store/UserStore';
import { observer } from 'mobx-react';
import React from 'react';
import styles from './UserView.scss';

const UserView: React.FC<{store: UserStore}> = (props) => {
    const data = props.store.user;

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

export default observer(UserView);