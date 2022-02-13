import editUser from '@/api/editUser';
import geocode, { IGeocodeResults } from '@/api/geocode';
import { IGender } from '@/api/getGenders';
import { BlackButton, OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import SelectBox, { useSelectBox } from '@/components/Form/SelectBox/SelectBox';
import Link from '@/components/Link/Link';
import Section from '@/components/Section/Section';
import noProfile from '@/static/images/noProfile.png';
import appStore from '@/store/AppStore';
import mapViewerStore from '@/store/MapViewerStore';
import userStore from '@/store/UserStore';
import { IPoint } from '@/types/IPoint';
import { runInAction } from 'mobx';
import { observer } from 'mobx-react';
import React, { Fragment, useEffect, useRef, useState } from 'react';
import { Navigate, useNavigate } from 'react-router-dom';
import View from '../View/View';
import styles from './UserView.scss';

// Poznań
const defaultMarkerPosition: IPoint = {
    lat: 52.4035,
    lng: 16.9109
};

const UserView: React.FC = () => {
    const user = userStore.user;

    const [location, setLocation] = useState<IGeocodeResults>(null);
    const [editMode, setEditMode] = useState(false);
    const [newImageUrl, setNewImageUrl] = useState(null);
    const [newImageFile, setNewImageFile] = useState<File>(null);
    const genderSelect = useSelectBox<IGender>();

    const nameRef = useRef<HTMLInputElement>();
    const lastnameRef = useRef<HTMLInputElement>();
    const birthDateRef = useRef<HTMLInputElement>();
    const locationRef = useRef<HTMLInputElement>();
    const emailRef = useRef<HTMLInputElement>();

    const websiteRef = useRef<HTMLInputElement>();
    const telephoneRef = useRef<HTMLInputElement>();
    const facebookRef = useRef<HTMLInputElement>();
    const instagramRef = useRef<HTMLInputElement>();
    const newPasswordRef = useRef<HTMLInputElement>();
    const confirmNewPasswordRef = useRef<HTMLInputElement>();

    const imagefileRef = useRef<HTMLInputElement>();
    const navigateTo = useNavigate();

    useEffect(() => {
        if (!user) {
            navigateTo('/');
        }

        mapViewerStore.reset();
    }, [user]);

    useEffect(() => {
        if (!user || !editMode) {
            mapViewerStore.setMarkers([]);
            return;
        }

        nameRef.current.value = user.firstName;
        lastnameRef.current.value = user.lastName;
        birthDateRef.current.value = user.birthDate;
        emailRef.current.value = user.email;
        telephoneRef.current.value = user.phoneNumber;
        websiteRef.current.value = user.website;
        facebookRef.current.value = user.facebookProfile;
        instagramRef.current.value = user.instagramProfile;

        if (user.addressCoordinates) {
            const { lat, lng } = user.addressCoordinates;
            locationRef.current.value = lat.toFixed(4) + '; ' + lng.toFixed(4);
        }

        if (!user.gender) {
            genderSelect.select(0);
        } else {
            genderSelect.select(opt => opt?.id === user.gender.id);
        }

        const marker = new google.maps.Marker({
            position: defaultMarkerPosition, //TODO user pos
            draggable: true,
        });

        marker.addListener('drag', async () => {
            const { lat, lng } = marker.getPosition().toJSON();
            locationRef.current.value = lat.toFixed(4) + '; ' + lng.toFixed(4);
        });

        marker.addListener('dragend', async () => {
            const location = await geocode(marker.getPosition().toJSON());
            setLocation(location);
        });

        mapViewerStore.setMarkers([marker]);
    }, [editMode]);

    useEffect(() => {
        genderSelect.setOptions([{
            text: 'Nie chcę podawać',
            value: null,
            isSelected: !user?.gender
        },
        ...appStore.genders.map(gender => {
            return {
                text: gender.name,
                value: gender,
                isSelected: user?.gender?.name == gender.name
            };
        })]);
    }, [appStore.genders]);

    const saveUserData = async () => {
        await editUser({
            email: emailRef.current.value,
            firstName: nameRef.current.value,
            lastName: lastnameRef.current.value,
            telephone: telephoneRef.current.value,
            birthDate: birthDateRef.current.value,
            addressCoordinates: location?.location ?? undefined,
            facebookProfile: facebookRef.current.value,
            instagramProfile: instagramRef.current.value,
            website: websiteRef.current.value,
            genderId: genderSelect.selectedOptions[0]?.value?.id,
            password: newPasswordRef.current.value || undefined,
            passwordConfirmation: confirmNewPasswordRef.current.value || undefined,
            administrativeAreas: location?.administrativeAreas ?? undefined
        }, newImageFile);

        //TODO zdjęcie

        runInAction(() => {
            userStore.user.email = emailRef.current.value;
            userStore.user.firstName = nameRef.current.value;
            userStore.user.phoneNumber = telephoneRef.current.value;
            userStore.user.birthDate = birthDateRef.current.value;
            userStore.user.facebookProfile = facebookRef.current.value;
            userStore.user.instagramProfile = instagramRef.current.value;
            userStore.user.website = websiteRef.current.value;
            userStore.user.gender = appStore.genders.find(g => g.id == genderSelect.selectedOptions[0]?.value?.id);
            userStore.user.avatarUrl = newImageUrl;
            userStore.user.addressCoordinates = location?.location;
        });

        setEditMode(false);
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
                            <BlackButton>Usuń konto</BlackButton>
                            <BlackButton onClick={() => setEditMode(true)}>Edytuj konto</BlackButton>
                            <OrangeButton>Zostań partnerem</OrangeButton>
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

    if (!user) {
        return null;
    }

    let imageUrl = user?.avatarUrl;

    if (editMode) {
        imageUrl = newImageUrl ?? user?.avatarUrl;
    }

    return (
        <View
            title="Moje konto"
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
                                    onClick={() => alert('TODO')}
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
                            <div className={styles.fieldName}>Imię</div>
                            <div className={styles.fieldValue}>
                                {editMode && user.canChangeName ? (
                                    <Input ref={nameRef}></Input>
                                ) : (
                                    user.firstName
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Nazwisko</div>
                            <div className={styles.fieldValue}>
                                {editMode && user.canChangeName ? (
                                    <Input ref={lastnameRef}></Input>
                                ) : (
                                    user.lastName
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Płeć</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <SelectBox
                                        dark
                                        {...genderSelect}
                                    />
                                ) : (
                                    user.gender?.name ?? 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Data urodzenia</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input type="date" ref={birthDateRef}></Input>
                                ) : (
                                    user.birthDate ?? 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Lokalizacja</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={locationRef}></Input>
                                ) : (
                                    user.addressCoordinates ? (
                                        user.addressCoordinates.lat.toFixed(4) + '; ' +
                                        user.addressCoordinates.lng.toFixed(4)
                                    ) : (
                                        'Nie podano'
                                    )
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
                                    user.email ?? 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Telefon</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={telephoneRef}></Input>
                                ) : (
                                    user.phoneNumber ?? 'Nie podano'
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
                                    user.website ? <Link href={user.website}>{user.website}</Link> : 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Facebook</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={facebookRef}></Input>
                                ) : (
                                    user.facebookProfile ? <Link href={user.facebookProfile}>{user.facebookProfile}</Link> : 'Nie podano'
                                )}
                            </div>
                        </div>
                        <div className={styles.fieldRow}>
                            <div className={styles.fieldName}>Instagram</div>
                            <div className={styles.fieldValue}>
                                {editMode ? (
                                    <Input ref={instagramRef}></Input>
                                ) : (
                                    user.instagramProfile ? <Link href={user.instagramProfile}>{user.instagramProfile}</Link> : 'Nie podano'
                                )}
                            </div>
                        </div>
                    </Section>
                    {editMode &&
                        <Section title="Konto" titleAlign="right" titleSize={15}>
                            <div className={styles.fieldRow}>
                                <div className={styles.fieldName}>Nowe hasło</div>
                                <div className={styles.fieldValue}>
                                    <Input type="password" ref={newPasswordRef}></Input>
                                </div>
                            </div>
                            <div className={styles.fieldRow}>
                                <div className={styles.fieldName}>Potwierdź nowe hasło</div>
                                <div className={styles.fieldValue}>
                                    <Input type="password" ref={confirmNewPasswordRef}></Input>
                                </div>
                            </div>
                        </Section>
                    }
                </div>
            </div>
        </View>
    );
};

export default observer(UserView);