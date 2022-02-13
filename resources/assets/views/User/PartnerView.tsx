import deleteUserAvatar from '@/api/deleteUserAvatar';
import getPartner, { IPartner } from '@/api/getPartner';
import { BlackButton, OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Link from '@/components/Link/Link';
import Section from '@/components/Section/Section';
import noProfile from '@/static/images/noProfile.png';
import mapViewerStore from '@/store/MapViewerStore';
import userStore from '@/store/UserStore';
import { IPoint } from '@/types/IPoint';
import { runInAction } from 'mobx';
import { observer } from 'mobx-react';
import React, { Fragment, useEffect, useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import View from '../View/View';
import styles from './UserView.scss';

// Poznań
const defaultMarkerPosition: IPoint = {
    lat: 52.4035,
    lng: 16.9109
};

const PartnerView: React.FC = () => {
    const [partner, setPartner] = useState<IPartner>(null);
    const [editMode, setEditMode] = useState(null);
    const [newImageUrl, setNewImageUrl] = useState(null);
    const [newImageFile, setNewImageFile] = useState<File>(null);

    const companyNameRef = useRef<HTMLInputElement>();
    const emailRef = useRef<HTMLInputElement>();

    const websiteRef = useRef<HTMLInputElement>();
    const telephoneRef = useRef<HTMLInputElement>();
    const facebookRef = useRef<HTMLInputElement>();
    const instagramRef = useRef<HTMLInputElement>();

    const imagefileRef = useRef<HTMLInputElement>();
    const navigateTo = useNavigate();

    useEffect(() => {
        try {
            getPartner().then(partner => {
                setPartner(partner);
                setEditMode(false);
            }).catch(e => {
                console.error(e);
                setEditMode(true);
            });
        } catch (e) {
            console.error(e);
            setEditMode(true);
        }
    }, []);

    useEffect(() => {
        if (!userStore.user) {
            navigateTo('/');
        }

        mapViewerStore.reset();
    }, [userStore.user]);

    useEffect(() => {
        if (editMode) {
            companyNameRef.current.value = partner?.businessName;
            emailRef.current.value = partner?.contactEmail;
            telephoneRef.current.value = partner?.telephone;
            websiteRef.current.value = partner?.website;
            facebookRef.current.value = partner?.facebook;
            instagramRef.current.value = partner?.instagram;
        }
    }, [editMode]);

    const saveUserData = async () => {
        // const { avatarUrl, avatarId } = await editUser({
        //     email: emailRef.current.value,
        //     firstName: companyNameRef.current.value,
        //     lastName: lastnameRef.current.value,
        //     telephone: telephoneRef.current.value,
        //     facebookProfile: facebookRef.current.value,
        //     instagramProfile: instagramRef.current.value,
        //     website: websiteRef.current.value,
        // }, newImageFile);

        //TODO zapis do bazy

        runInAction(() => {
            //TODO update partnera
            userStore.user.email = emailRef.current.value;
            userStore.user.phoneNumber = telephoneRef.current.value;
            userStore.user.facebookProfile = facebookRef.current.value;
            userStore.user.instagramProfile = instagramRef.current.value;
            userStore.user.website = websiteRef.current.value;
        });

        setEditMode(false);
        setNewImageFile(null);
    };

    const header = (title: string) => {
        return (
            <Fragment>
                <h1>{title}</h1>
                <div className={styles.headerButtons}>
                    {editMode ? (
                        <Fragment>
                            <OrangeButton onClick={() => saveUserData()}>Zapisz zmiany</OrangeButton>
                            <BlackButton onClick={() => setEditMode(false)}>Anuluj</BlackButton>
                        </Fragment>
                    ) : (
                        <Fragment>
                            <BlackButton onClick={() => setEditMode(true)}>Edytuj dane</BlackButton>
                        </Fragment>
                    )}
                </div>
            </Fragment>
        );
    };

    const displayImage = (file: File) => {
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            setNewImageUrl(e.target.result);
            setNewImageFile(file);
        };

        reader.readAsDataURL(file);
    };

    if (!userStore.user || editMode === null) {
        return null;
    }

    let imageUrl = partner?.imageUrl;

    if (editMode) {
        imageUrl = newImageUrl ?? partner?.imageUrl;
    }

    return (
        <View
            title="Partnerstwo"
            withBackground
            headerContent={header}
        >
            <div className={styles.userDataWrapper + ' ' + (editMode ? styles.editMode : '')}>
                <div className={styles.leftColumn}>
                    <div style={{ width: 'min-content' }}>
                        <img src={imageUrl ?? noProfile} alt="" className={styles.image} />
                        {editMode && (
                            <Fragment>
                                <OrangeButton
                                    style={{ marginTop: '10px', width: '100%' }}
                                    onClick={() => imagefileRef.current.click()}
                                >
                                    Zmień zdjęcie
                                </OrangeButton>
                                <BlackButton
                                    onClick={async () => {
                                        //TODO usuwanie awatara partnera
                                        // if (user.avatarId) {
                                        //     await deleteUserAvatar(user.avatarId);
                                        // }

                                        setNewImageUrl(null);
                                        setNewImageFile(null);
                                    }}
                                    style={{ marginTop: '10px', width: '100%' }}
                                >
                                    Usuń zdjęcie
                                </BlackButton>
                                <input
                                    ref={imagefileRef}
                                    type="file"
                                    style={{ display: 'none' }}
                                    accept="image/jpeg, image/png"
                                    onChange={(e) => displayImage(e.currentTarget.files?.[0])}
                                />
                            </Fragment>
                        )}
                    </div>
                </div>
                <div className={styles.rightColumn}>
                    <Section title="Dane podstawowe" titleAlign="right" titleSize={15}>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Nazwa firmy</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={companyNameRef}></Input>
                                ) : (
                                    partner.businessName ?? 'Nie podano'
                                )}
                            </div>
                        </div>
                    </Section>
                    <Section title="Kontakt" titleAlign="right" titleSize={15}>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>E-mail</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={emailRef}></Input>
                                ) : (
                                    partner.contactEmail ?? 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Telefon</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={telephoneRef}></Input>
                                ) : (
                                    partner.telephone ?? 'Nie podano'
                                )}
                            </div>
                        </div>
                    </Section>
                    <Section title="Social media" titleAlign="right" titleSize={15}>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Strona internetowa</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={websiteRef}></Input>
                                ) : (
                                    partner.website ? <Link href={partner.website}>{partner.website}</Link> : 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Facebook</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={facebookRef}></Input>
                                ) : (
                                    partner.facebook ? <Link href={partner.facebook}>{partner.facebook}</Link> : 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Instagram</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={instagramRef}></Input>
                                ) : (
                                    partner.instagram ? <Link href={partner.instagram}>{partner.instagram}</Link> : 'Nie podano'
                                )}
                            </div>
                        </div>
                    </Section>
                </div>
            </div>
        </View>
    );
};

export default observer(PartnerView);