import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/form/Button/Button';
import Input from '@/components/form/Input/Input';
import Modal from '@/components/Modal/Modal';
import useQuery from '@/hooks/useQuery';
import userStore from '@/store/UserStore';
import React, { useState } from 'react';
import { useLocation } from 'react-router-dom';

export interface ResetPasswordModalProps {
    isOpen: boolean;
    onClose: () => void;
}

//TODO cały ten modal powinien być podpięty pod konkretny link np. /reset-password?token={TOKEN}

const ResetPasswordModal: React.FC<ResetPasswordModalProps> = ({ isOpen, onClose }) => {
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const query = useQuery();

    const resetPassword = async () => {
        setIsLoading(true);
        
        try {
            await userStore.resetPassword(password, confirmPassword, query.get('token'));

            onClose();
        } catch (err) {

        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            title="Reset hasła"
            isOpen={isOpen}
            isLoading={isLoading}
            onClose={onClose}
            closeOnEsc={false}
            footerItems={[
                <OrangeButton key="2" onClick={resetPassword}>Zapisz</OrangeButton>
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