import Flexbox from '@/components/Flexbox/Flexbox';
import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface LoginModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const LoginModal: React.FC<LoginModalProps> = ({ isOpen, onClose }) => {
    const [login, setLogin] = useState('');
    const [password, setPassword] = useState('');

    const close = () => {
        setLogin('');
        setPassword('');
        onClose();
    };

    return (
        <Modal
            title="Zaloguj się"
            isOpen={isOpen}
            onClose={() => close()}
            footerItems={[
                <OrangeButton key="1">Zaloguj się</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Input label="Adres e-mail lub nazwa użytkownika" value={login} onChange={(v) => setLogin(v)} />
                <Input label="Hasło" type="password" value={password} onChange={(v) => setPassword(v)} />
            </Flexbox>
        </Modal>
    );
};

export default LoginModal;