import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import userStore from '@/store/UserStore';
import React, { useEffect, useState } from 'react';

export interface RegisterModalProps {
    isOpen: boolean;
    onClose: () => void;
    onClickLoginButton: () => void;
}

const RegisterModal: React.FC<RegisterModalProps> = ({ isOpen, onClose, onClickLoginButton }) => {
    const [firstname, setFisrtname] = useState('');
    const [lastname, setLastname] = useState('');
    const [birthDate, setBirthDate] = useState('');
    const [gender, setGender] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const register = async () => {
        setIsLoading(true);

        try {
            await userStore.register({
                firstname, 
                lastname, 
                birthDate, 
                gender, 
                email, 
                password, 
                confirmPassword
            });

            onClose();
        } catch (err) {

        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            onEnter={() => register()}
            title="Zarejestruj się"
            isOpen={isOpen}
            onClose={onClose}
            width="450px"
            isLoading={isLoading}
            footerItems={[
                <Link key="1" onClick={() => onClickLoginButton()}>Zaloguj się</Link>,
                <OrangeButton key="2">Zarejestruj się</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Flexbox gap="10px">
                    <Input label="Imię" value={firstname} onChange={(v) => setFisrtname(v)} />
                    <Input label="Nazwisko" value={lastname} onChange={(v) => setLastname(v)} />
                </Flexbox>
                <Flexbox gap="10px">
                    <Input label="Data urodzenia" type="date" value={birthDate} onChange={(v) => setBirthDate(v)} />
                    <Input label="Płeć" value={gender} onChange={(v) => setGender(v)} />
                </Flexbox>
                <Input label="Adres e-mail" value={email} onChange={(v) => setEmail(v)} />
                <Input label="Hasło" type="password" value={password} onChange={(v) => setPassword(v)} />
                <Input label="Potwierdź hasło" type="password" value={confirmPassword} onChange={(v) => setConfirmPassword(v)} />
                <div style={{ fontSize: '12px', color: '#a1a1a1'}}>
                    Rejestrując się, akceptujesz&nbsp;<Link fixedColor>regulamin</Link> oraz&nbsp;
                    <Link fixedColor>politykę prywatyności</Link> serwisu nasza-nazwa.pl.
                </div>
            </Flexbox>
        </Modal>
    );
};

export default RegisterModal;