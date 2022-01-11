import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface RegisterModalProps {
    isOpen: boolean;
    onClose: () => void;
    onClickLoginButton: () => void;
}

const RegisterModal: React.FC<RegisterModalProps> = ({ isOpen, onClose, onClickLoginButton }) => {
    const [name, setName] = useState('');
    const [lastname, setLastname] = useState('');
    const [birthDate, setBirthDate] = useState('');
    const [gender, setGender] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');

    return (
        <Modal
            title="Zarejestruj się"
            isOpen={isOpen}
            onClose={onClose}
            width="450px"
            footerItems={[
                <Link key="1" onClick={() => onClickLoginButton()}>Zaloguj się</Link>,
                <OrangeButton key="2">Zarejestruj się</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Flexbox gap="10px">
                    <Input label="Imię" value={name} onChange={(v) => setName(v)} />
                    <Input label="Nazwisko" value={lastname} onChange={(v) => setLastname(v)} />
                </Flexbox>
                <Flexbox gap="10px">
                    <Input label="Data urodzenia" type="date" value={birthDate} onChange={(v) => setBirthDate(v)} />
                    <Input label="Płeć" value={gender} onChange={(v) => setGender(v)} />
                </Flexbox>
                <Input label="Adres e-mail" value={email} onChange={(v) => setEmail(v)} />
                <Input label="Hasło" type="password" value={password} onChange={(v) => setPassword(v)} />
                <Input label="Potwierdź hasło" type="password" value={confirmPassword} onChange={(v) => setConfirmPassword(v)} />
                <span style={{ fontSize: '12px', color: '#a1a1a1', textAlign: 'justify' }}>
                    Rejestrując się, akceptujesz <Link fixedColor>Regulamin</Link> oraz <Link fixedColor>politykę prywatyności</Link> serwisu nasza-nazwa.pl
                </span>
            </Flexbox>
        </Modal>
    );
};

export default RegisterModal;