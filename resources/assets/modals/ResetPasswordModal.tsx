import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Modal from '@/components/Modal/Modal';
import useQuery from '@/hooks/useQuery';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { observer } from 'mobx-react';
import React, { useState } from 'react';

//TODO cały ten modal powinien być podpięty pod konkretny link np. /reset-password?token={TOKEN}

const ResetPasswordModal: React.FC = observer(() => {
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const query = useQuery();

    const resetPassword = async () => {
        setIsLoading(true);
        
        try {
            await userStore.resetPassword(password, confirmPassword, query.get('token'));

            modalsStore.setIsResetPasswordEnabled(false);
        } catch (err) {

        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            title="Reset hasła"
            isOpen={modalsStore.isResetPasswordEnabled}
            isLoading={isLoading}
            onClose={() => modalsStore.setIsResetPasswordEnabled(false)}
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
});

export default ResetPasswordModal;