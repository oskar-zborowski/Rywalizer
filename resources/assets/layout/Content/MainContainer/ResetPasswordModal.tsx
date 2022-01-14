import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface ResetPasswordModalProps {
    isOpen: boolean;
    onClose: () => void;
}

//TODO cały ten modal powinien być podpięty pod konkretny link np. /reset-password?token={TOKEN}

const ResetPasswordModal: React.FC<ResetPasswordModalProps> = ({ isOpen, onClose }) => {
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');

    return (
        <Modal
            title="Reset hasła"
            isOpen={isOpen}
            onClose={onClose}
            closeOnEsc={false}
            footerItems={[
                <OrangeButton key="2">Zapisz</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Input label="Nowe hasło" type="password" value={password} onChange={(v) => setPassword(v)} />
                <Input label="Potwierdź hasło" type="password" value={confirmPassword} onChange={(v) => setConfirmPassword(v)} />
            </Flexbox>
        </Modal>
    );
};

export default ResetPasswordModal;