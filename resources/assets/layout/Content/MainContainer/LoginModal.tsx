import Flexbox from '@/components/Flexbox/Flexbox';
import { FacebookButton, GoogleButton, OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import userStore from '@/store/UserStore';
import React, { useState } from 'react';

export interface LoginModalProps {
    isOpen: boolean;
    onClose: () => void;
    onClickRegisterButton: () => void;
    onClickRemindPassword: () => void;
}

const LoginModal: React.FC<LoginModalProps> = ({ isOpen, onClose, onClickRegisterButton, onClickRemindPassword }) => {
    const [loginValue, setLogin] = useState('');
    const [passwordValue, setPassword] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const login = async () => {
        setIsLoading(true);
        
        try {
            await userStore.login(loginValue, passwordValue);
        } catch (err) {

        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            title="Zaloguj się"
            isOpen={isOpen}
            onClose={() => onClose()}
            width="400px"
            isLoading={isLoading}
            footerItems={[
                <Link key="1" onClick={() => onClickRegisterButton()}>Zarejestruj się</Link>,
                <OrangeButton key="2" onClick={login}>Zaloguj się</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <FacebookButton key="1">Zaloguj się przez Facebooka</FacebookButton>
                <GoogleButton key="2" style={{ marginTop: '5px' }}>Zaloguj się przez Google</GoogleButton>
                <div style={{
                    textAlign: 'center',
                    marginTop: '10px',
                    marginBottom: '-10px'
                }}>
                    lub
                </div>
                <Input label="Login" value={loginValue} onChange={(v) => setLogin(v)} />
                <Input label="Hasło" type="password" value={passwordValue} onChange={(v) => setPassword(v)} />
                <div style={{ fontSize: '12px', color: '#a1a1a1', textAlign: 'right' }}>
                    <Link fixedColor onClick={() => onClickRemindPassword()}>Nie pamiętasz hasła?</Link>
                </div>
            </Flexbox>
        </Modal>
    );
};

export default LoginModal;