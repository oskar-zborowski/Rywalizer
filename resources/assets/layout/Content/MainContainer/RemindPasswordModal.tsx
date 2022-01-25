import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Modal from '@/components/Modal/Modal';
import userStore from '@/store/UserStore';
import React, { useState } from 'react';

export interface RemindPasswordModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const RemindPasswordModal: React.FC<RemindPasswordModalProps> = ({ isOpen, onClose }) => {
    const [email, setEmail] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const remindPassword = async () => {
        setIsLoading(true);
        
        try {
            await userStore.remindPassword(email);

            onClose();
        } catch (err) {

        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            title="Resetowanie hasła"
            isOpen={isOpen}
            onClose={onClose}
            isLoading={isLoading}
            width="450px"
            footerItems={[
                <OrangeButton key="2" onClick={remindPassword}>Wyślij</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Input label="Adres e-mail" value={email} onChange={(v) => setEmail(v)} />
                <div style={{ fontSize: '12px', color: '#a1a1a1'}}>
                    Na podany adres e-mail zostanie wysłany link umożliwiający zresetowania hasła.
                </div>
            </Flexbox>
        </Modal>
    );
};

export default RemindPasswordModal;