import { IGender } from '@/api/getGenders';
import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import SelectBox, { IOption } from '@/components/Form/SelectBox/SelectBox';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import appStore from '@/store/AppStore';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { observer } from 'mobx-react';
import React, { useState } from 'react';

const RegisterModal: React.FC = observer(() => {
    const [firstName, setFisrtname] = useState('');
    const [lastName, setLastname] = useState('');
    const [birthDate, setBirthDate] = useState('');
    const [genderId, setGender] = useState<number>(9);
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setConfirmPassword] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const register = async () => {
        setIsLoading(true);

        try {
            await userStore.register({
                firstName,
                lastName,
                birthDate,
                genderId,
                email,
                password,
                passwordConfirmation,
                acceptedAgreements: [1, 2]
            });

            modalsStore.setIsRegisterEnabled(false);
        } catch (err) {

        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            onEnter={() => register()}
            title="Zarejestruj się"
            isOpen={modalsStore.isRegisterEnabled}
            onClose={() => modalsStore.setIsRegisterEnabled(false)}
            width="450px"
            isLoading={isLoading}
            footerItems={[
                <Link key="1" onClick={() => modalsStore.setIsLoginEnabled(true)}>Zaloguj się</Link>,
                <OrangeButton key="2" onClick={() => register()}>Zarejestruj się</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Flexbox gap="10px">
                    <Input label="Imię" value={firstName} onChange={(v) => setFisrtname(v)} />
                    <Input label="Nazwisko" value={lastName} onChange={(v) => setLastname(v)} />
                </Flexbox>
                <Flexbox gap="10px">
                    <Input label="Data urodzenia" type="date" value={birthDate} onChange={(v) => setBirthDate(v)} />
                    <SelectBox
                        label="Płeć"
                        options={[
                            {
                                text: 'Nie chcę podawać',
                                value: null
                            },
                            ...appStore.genders.map(gender => {
                                return {
                                    text: gender.name,
                                    value: gender
                                };
                            })
                        ]}
                        onChange={([gender]) => setGender(gender.value?.id)}
                    />
                </Flexbox>
                <Input label="Adres e-mail" value={email} onChange={(v) => setEmail(v)} />
                <Input label="Hasło" type="password" value={password} onChange={(v) => setPassword(v)} />
                <Input label="Potwierdź hasło" type="password" value={passwordConfirmation} onChange={(v) => setConfirmPassword(v)} />
                <div style={{ fontSize: '12px', color: '#a1a1a1' }}>
                    Rejestrując się, akceptujesz&nbsp;<Link fixedColor>regulamin</Link> oraz&nbsp;
                    <Link fixedColor>politykę prywatyności</Link> serwisu nasza-nazwa.pl.
                </div>
            </Flexbox>
        </Modal>
    );
});

export default RegisterModal;