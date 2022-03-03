import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Modal from '@/components/Modal/Modal';
import useQuery from '@/hooks/useQuery';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import axios from 'axios';
import { observer } from 'mobx-react';
import React, { useEffect, useState } from 'react';
import { Navigate, useNavigate } from 'react-router-dom';

const ResetPasswordModal: React.FC = observer(() => {
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [isOpen, setIsOpen] = useState(true);
    const query = useQuery();

    const resetPassword = async () => {
        setIsLoading(true);
        
        try {
            await userStore.resetPassword(password, confirmPassword, query.get('rptoken'));

            setIsOpen(false);
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
            onClose={() => setIsOpen(false)}
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
});

export default ResetPasswordModal;