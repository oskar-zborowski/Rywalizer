import Flexbox from '@/components/Flexbox/Flexbox';
import { FacebookButton, GoogleButton, OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface LoginModalProps {
    isOpen: boolean;
    onClose: () => void;
    onClickRegisterButton: () => void;
}

const LoginModal: React.FC<LoginModalProps> = ({ isOpen, onClose, onClickRegisterButton }) => {
    const [login, setLogin] = useState('');
    const [password, setPassword] = useState('');

    return (
        <Modal
            title="Zaloguj się"
            isOpen={isOpen}
            onClose={() => onClose()}
            width="400px"
            footerItems={[
                <Link key="1" onClick={() => onClickRegisterButton()}>Zarejestruj się</Link>,
                <OrangeButton key="2">Zaloguj się</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <FacebookButton key="1">Zaloguj się przez Facebooka</FacebookButton>
                <GoogleButton key="2" style={{ marginTop: '5px' }}>Zaloguj się przez Google</GoogleButton>
                <div style={{ textAlign: 'center' }}>lub</div>
                <Input label="Login" value={login} onChange={(v) => setLogin(v)} />
                <Input label="Hasło" type="password" value={password} onChange={(v) => setPassword(v)} />
            </Flexbox>
        </Modal>
    );
};

export default LoginModal;